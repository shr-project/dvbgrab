<?php
require_once("authenticate.php");
require_once("dblib.php");
require_once("view.inc.php");

//TODO: wrap this code to a function
//TODO: this script depends on some global variables:
// $usr_id
// $tv_date
// $grab_time_limit
// $_GET["tel_id"]
// $_GET["grb_id"]

$query = $_GET["query"];
$addition = "tv_date=$tv_date&query=$query";

switch ($_GET["action"]) {
	// zadani noveho grabu
	case "grab_add":
		$tel_id = (int)$_GET["tel_id"];
		// zjisti, zda porad existuje
		$SQL = "select t.tel_date_start, t.chn_id, g.grb_id from 
						television t left join grab g on (t.tel_id=g.tel_id)
					where
						t.tel_id=$tel_id";

		$rs = db_sql($SQL);
		if ($row = $rs->FetchRow()) {

			// TODO pred zjistenim poctu grabu nastavit semafor a po zadani grabu ho uvolnit
			
			// uzivatel vycerpal tydenni kvotu na graby
			if (get_user_grab($usr_id, $DB->UserDate($row[0],"W"))>= $grab_quota) {
				header("Location:$PHP_SELF?msg=grb_add_fail_quota&$addition#$tel_id");
				exit;
			}

			// pozadavek o grab na uz odvysilany porad
			if ($DB->UnixTimeStamp($row[0])<$grab_time_limit) {
				header("Location:$PHP_SELF?msg=grb_add_fail_time&$addition#$tel_id");
				exit;
			}

			// grab jiz existuje, pridat dalsiho usera
			if ($row[2]) {
				// check for duplicate request of the same user
				$SQL = "select * from request
					where grb_id = $row[2]
					and usr_id=$usr_id";
				$rs_check = db_sql($SQL);
				if ($rs_check->RecordCount() == 0) {
					$SQL = "insert into request set
										grb_id=$row[2],
										usr_id=$usr_id";
					db_sql($SQL);
				}

				header("Location:$PHP_SELF?msg=grb_add_ok&$addition#$tel_id");
				exit;

			// grab neexistuje a muzeme ho zadat
			} else {
				// zjisti cas nasledujiciho poradu na danem kanale -> to bude cas pro skonceni grabu
				$SQL = "select tel_date_start from television where
								chn_id=$row[1] and
								tel_date_start>'$row[0]'
							order by tel_date_start
							limit 1";
				$rs = db_sql($SQL);
				if (!($row2 = $rs->FetchRow())) {
					header("Location:$PHP_SELF?msg=grb_add_fail_tel&$addition#$tel_id");
					exit;
				}

				// zadame grab
				$SQL = "insert into grab set
							tel_id=$tel_id,
							grb_date_start=$DB->DBTimeStamp('$row[0]'),
							grb_date_end=$DB->DBTimeStamp('$row2[0]')";
				db_sql($SQL);

				// zjistime jeho grb_id
				$SQL = "select grb_id from grab where
							tel_id=$tel_id and
							grb_date_start=$DB->DBTimeStamp('$row[0]')";
				$rs = db_sql($SQL);
				$row = $rs->FetchRow();

				$SQL = "insert into request set
									grb_id=$row[0],
									usr_id=$usr_id";
				db_sql($SQL);

				header("Location:$PHP_SELF?msg=grb_add_ok&$addition#$tel_id");
				exit;
			}

		// porad s $tel_id neexistuje
		} else {
			header("Location:$PHP_SELF?msg=grb_add_fail_tel&$addition#$tel_id");
			exit;
		}
		break;

	case "grab_del":
		$grb_id = (int)$_GET["grb_id"];

		// zjisti, zda grab existuje
		$SQL = "select g.tel_id, g.grb_status, r.usr_id
				from grab g, request r
				where
					g.grb_id=r.grb_id and
					g.grb_id=$grb_id";

		// grab existuje
		$rs = db_sql($SQL);		
		if ($row = $rs->FetchRow()) {

			// grab uz skoncil nebo probiha
			if ($row[1] != 'scheduled') {
				header("Location:$PHP_SELF?msg=grb_del_fail_time&$addition#$row[0]");
				exit;
			}

			while ($row[2] != $usr_id) {
				if (!($row = $rs->FetchRow())) {
					// nejedna se o muj grab
					header("Location:$PHP_SELF?msg=grb_del_fail_owner&$addition#$row[0]");
					exit;
				}
			}

			// zadal jsem o ten porad jediny
			if (($rs->RecordCount()) == 1) {
				$SQL = "delete from request where grb_id=$grb_id";
				db_sql($SQL);
				$SQL = "delete from grab where grb_id=$grb_id";
				db_sql($SQL);
			}
			// ne je nas vic, takze jenom odeberu muj request
			else {
				$SQL = "delete from request where
						grb_id=$grb_id and usr_id=$usr_id";
				db_sql($SQL);
			}

			header("Location:$PHP_SELF?msg=grb_del_ok&$addition#$row[0]");
			exit;
		
		// grab s $grb_id neexistuje
		} else {
			header("Location:$PHP_SELF?msg=grb_del_fail_exist&$addition");
			exit;
		}
		break;

	default:
}

// vim: noexpandtab tabstop=4
?>
