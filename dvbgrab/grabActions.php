<?php
$query = $_GET["query"];

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
				header("Location:$PHP_SELF?msg=grb_add_fail_quota&tv_date=$tv_date#$tel_id");
				exit;
			}

			// pozadavek o grab na uz odvysilany porad
			if ($DB->UnixTimeStamp($row[0])<$grab_time_limit) {
				header("Location:$PHP_SELF?msg=grb_add_fail_time&tv_date=$tv_date#$tel_id");
				exit;
			}

			// grab jiz existuje, pridat dalsiho usera
			if ($row[2]) {
				$SQL = "insert into request set
                  grb_id=$row[2],
									usr_id=$usr_id,
									grb_enc='1'";
				//defaultne do MPEG4
				db_sql($SQL);

				status_update();

				header("Location:$PHP_SELF?msg=grb_add_ok&tv_date=$tv_date#$tel_id");
				exit;

			// grab neexistuje a muzeme ho zadat
			} else {

				// TODO zakazat grabovat posledni porad v tv programu
				// TODO omezit zadavani grabu pouze na $tv_days dopredu

				// zjisti cas nasledujiciho poradu na danem kanale -> to bude cas pro skonceni grabu
				$SQL = "select tel_date_start from television where
								chn_id=$row[1] and
								tel_date_start>'$row[0]'
							order by tel_date_start
							limit 1";
				$rs = db_sql($SQL);
				$row2 = $rs->FetchRow();

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
									usr_id=$usr_id,
									grb_enc='1'";
				db_sql($SQL);

				status_update();

				header("Location:$PHP_SELF?msg=grb_add_ok&tv_date=$tv_date#$tel_id");
				exit;
			}

		// porad s $tel_id neexistuje
		} else {
			header("Location:$PHP_SELF?msg=grb_add_fail_tel&tv_date=$tv_date");
			exit;
		}
		break;

	case "grab_add_me":
		$grb_id = (int)$_GET["grb_id"];
		// zjisti, zda grab existuje
		$SQL = "select grb_id, grb_date_start from grab where grb_id=$grb_id";
		$rs = db_sql($SQL);
		if ($row = $rs->FetchRow()) {
			// uzivatel vycerpal tydenni kvotu na graby
			if (get_user_grab($usr_id, $DB->UserDate($row[1],"W"))>= $grab_quota) {
				header("Location:$PHP_SELF?msg=grb_add_fail_quota&tv_date=$tv_date#$grb_id");
				exit;
			}

			// pozadavek o grab na uz odvysilany porad
			if ($DB->UnixTimeStamp($row[1])<$grab_time_limit) {
				header("Location:$PHP_SELF?msg=grb_add_fail_time&tv_date=$tv_date#$grb_id");
				exit;
			}

			// grab jiz existuje, pridat dalsiho usera
			$SQL = "insert into request set
                grb_id=$row[0],
								usr_id=$usr_id,
								grb_enc='1'";
			db_sql($SQL);

			status_update();

			header("Location:$PHP_SELF?msg=grb_add_ok&tv_date=$tv_date#$grb_id");
			exit;
		// grab neexistuje a pritom by mel
		} else {
			header("Location:$PHP_SELF?msg=grb_add_fail_tel&tv_date=$tv_date");
			exit;
		}
		break;

  case "grab_noenc":
    $grb_id = (int)$_GET["grb_id"];
    $SQL = "update request set grb_enc=0
								where grb_id=$grb_id and usr_id=$usr_id";
    db_sql($SQL);
    status_update();
		header("Location:$PHP_SELF?msg=grb_noenc_ok&tv_date=$tv_date#$grb_id");
    exit;
    break;

  case "grab_enc":
    $grb_id = (int)$_GET["grb_id"];
    $SQL = "update request set grb_enc=1
								where grb_id=$grb_id and usr_id=$usr_id";
    db_sql($SQL);
    status_update();
	  header("Location:$PHP_SELF?msg=grb_enc_ok&tv_date=$tv_date#$grb_id");
    exit;
    break;

	case "grab_edit":
		// TODO editace parametru grabu
		break;

	case "grab_del":

		$grb_id = (int)$_GET["grb_id"];

		// zjisti, zda grab existuje
		$SQL = "select t.tel_id, g.grb_date_start, r.usr_id from television t, grab g, request r
					where
						t.tel_id=g.tel_id and
						g.grb_id=r.grb_id and
						g.grb_id=$grb_id";

		// grab existuje
		$rs = db_sql($SQL);		
		if ($row = $rs->FetchRow()) {

			// grab uz skoncil, probiha, nebo je v kolizi s grabem, ktery probiha
			if ($DB->UnixTimeStamp($row[1])<$grab_time_limit) {
				header("Location:$PHP_SELF?msg=grb_del_fail_time&tv_date=$tv_date#$row[0]");
				exit;
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

			status_update();

			header("Location:$PHP_SELF?msg=grb_del_ok&tv_date=$tv_date#$row[0]");
			exit;
		
		// grab s $grb_id neexistuje
		} else {
			header("Location:$PHP_SELF?msg=grb_del_fail_exist&tv_date=$tv_date");
			exit;
		}
		break;

	default:
}
