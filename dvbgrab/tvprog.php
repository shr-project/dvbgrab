<?php
require("dblib.php");
require("authenticate.php");
require("config.php");
require_once("status.inc.php");


// kontrola parametru $_GET["tv_date"]
$tv_date =  date("Ymd", time()-((date("G")<$midnight)?1:0)*24*3600);
if (ereg("([0-9]{4})([0-9]{2})([0-9]{2})", $_GET["tv_date"], $regs)
	&& $regs[1]>=2003 &&  $regs[1]<2050 && $regs[2]>=1 && $regs[2]<=12 && $regs[3]>=1 && $regs[3]<=31
	&& date("Ymd", mktime(0, 0, 0, $regs[2], $regs[3], $regs[1])) == $_GET["tv_date"]) {

	$tv_date = $_GET["tv_date"];
}

$tv_day = substr($tv_date, 6, 2);
$tv_month = substr($tv_date, 4, 2);
$tv_year = substr($tv_date, 0, 4);

global $DB;  // pripojeni do databaze

// nejdrive od ted
$grab_time_limit = time();

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

require("const.php");
require("header.php");

$menuitem = 1;
require("menu.php");
?>
<td>
<h2 class="planList">Televizní program - <?
echo $dow[date("l", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year))];
echo date(" j. n. Y", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year));
?></h2>

<form method="get" action="<?=$PHP_SELF?>">

	<script type="text/javascript" language="javascript">
	<!--
		function change_date(f) {
			selectItem = f["tv_date"];
			location.href = "?tv_date="+selectItem[selectItem.selectedIndex].value;
		}
	//-->
	</script>

	Zobrazit televizní program pro den 
	<select name="tv_date" onchange="change_date(this.form)">
<?php
	// TODO nabidnout televizni program pouze pro dny, pro ktere mame zaznamy v db a max na $tv_days dopredu
	// TODO televizni program dozadu asi na tyden nebo max dokud tam existuji hotove graby
	$day = date("d");
	$month = date("m");
	$year = date("Y");

	// vypis datumy od dneska-$grab_history na $tv_days dopredu
	for ($i=-$grab_history-((date("h")<$midnight)?1:0);$i<$tv_days-1;$i++) {
		$my_date = date("Ymd", mktime(0, 0, 0, $month, $day+$i, $year));
		$show_date = $dow_short[date("D", mktime(0, 0, 0, $month, $day+$i, $year))];
		$show_date .= "&nbsp;&nbsp;&nbsp;".date("j. n. Y", mktime(0, 0, 0, $month, $day+$i, $year));
?>
		<option value="<?=$my_date?>"<?=(($i==0)?" class=\"today\"":"")?><?=(($my_date==$tv_date)?" selected":"")?>><?=$show_date?>

	<? } ?>
	</select>
	<input type="submit" value="Zobrazit">
</form>

<form method="get" action="search.php">
Hledej v tv programu: 
<input type="text" name="query">
<input type="submit" value="Hledej">
</form>

<table width="100%" border="0" cellspacing="0">
<tr>
	<td class="status-scheduled">&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;bude se grabovat</td>
</tr>
<tr>
	<td class="status-myscheduled">&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;bude se grabovat pro mì</td>
</tr>
<tr>
	<td class="status-mynocomprim">&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;bude se grabovat pro mì bez komprese</td>
</tr>
<tr>
	<td class="status-done">&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;hotové graby</td>
</tr>
<tr>
	<td class="status-missed">&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;negrablo se</td>
</tr>
<tr>
	<td class="status-error">&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;chyba pøi grabování</td>
</tr>
<tr>
	<td class="status-processing">&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;právì se grabuje</td>
</tr>
<tr>
	<td align="center"><img alt="moje" src="images/dot.gif"></td>
	<td>&nbsp;moje graby</td>
</tr>
</table>

</td>
</tr>
<tr>

<?
// vyber vsechny porady pro aktualni den od $midnight hodiny rano do $midnight hodiny rano nasledujiciho dne
// u kazdeho poradu oznac, jestli je to grab a jsem ho zadal a nebo jsem pro nej hlasoval
$SQL = "select t.tel_id, t.chn_id, t.tel_name, t.tel_desc, t.tel_date_start,
				g.grb_id, g.grb_date_start, g.grb_status, not isnull(r.usr_id) as my_grab, r.grb_enc,
				floor(hour(t.tel_date_start)/$hour_frac_item) as hour_frac
			from channel c inner join television t on (c.chn_id=t.chn_id)
				left join grab g on (t.tel_id=g.tel_id)
				left join request r on (g.grb_id=r.grb_id and r.usr_id=$usr_id)
			where 
				(date_format(tel_date_start, '%Y%m%d')='$tv_date' and 
					hour(tel_date_start)>=$midnight) or
				(date_format(date_sub(tel_date_start, interval 1 day), '%Y%m%d')='$tv_date' and 
					hour(tel_date_start)<$midnight)
			order by to_days(tel_date_start), hour_frac, chn_order, tel_date_start";
$rs = db_sql($SQL);

if ($rs->RecordCount() == 0) {
	echo "Televizní program pro tento den není k dispozici!"; 
	require("footer.php");
	exit;
}

// vyber vsechny stanice, pro ktere mame na $tv_date den program
$SQL = "select c.chn_id, c.chn_name 
			from channel c, television t
			where
				c.chn_id=t.chn_id and
				(date_format(t.tel_date_start, '%Y%m%d')='$tv_date' and 
					hour(tel_date_start)>=$midnight) or
				(date_format(date_sub(t.tel_date_start, interval 1 day), '%Y%m%d')='$tv_date' and 
					hour(tel_date_start)<$midnight)
			group by c.chn_id
			order by c.chn_order";
$rs2 = db_sql($SQL);

$channel_array = "";
$i = 0;
while ($row2 = $rs2->FetchRow()) {
	$channel_array[$i]["chn_id"] = $row2[0];
	$channel_array[$i]["chn_name"] = $row2[1];
	$i++;
}
?>
<table left="0" width="100%" border="0" cellspacing="0">
<tr>
<?php

// zobraz loga stanic, sirka sloupce je 100% / pocet_stanic
for ($channel=0; $channel<count($channel_array); $channel++)  {
echo '<td  height="50" width="',100/count($channel_array),'%" align="center">';
?>
		<img align="middle" alt="<?=$channel_array[$channel]["chn_name"]?>" title="<?=$channel_aray[$channel]["chn_name"]?>" src="images/<?=$channel_logo[$channel_array[$channel]["chn_name"]]?>">
	</td>
<? } ?>
</tr>

<?php

$row = $rs->FetchRow();
while ($row) {
  echo "<tr>\n";
	$hour_frac = $row["hour_frac"];

	// projdeme vsechny stanice pro casove odbobi $hour_frac
	for ($channel=0; $channel<count($channel_array); $channel++) {
	
	echo "<td valign=\"top\"><table>\n";
		// pokud v casovem obdobi $hour_frac na stanici $channel nic neni
		if (!$row || $row["hour_frac"] != $hour_frac || $row["chn_id"] != $channel_array[$channel]["chn_id"]) {
		?>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<?php
		} else {
			// vypiseme porady v casovem obdobi $hour_frac na stanici $channel
			do {
    ?>
		<tr<?
				// pokud se jedna o grab, tak ho barevne oznac
				if ($row["grb_id"]) {
					if ($row["grb_status"] <> 'scheduled') {
					  echo " class=\"status-".$row["grb_status"]."\"";
					} else {
	          // oznacime graby, ktere maji oznaceny ostatni a ja ne
  	        $SQL = "select grb_id, grb_enc from request where grb_id=".$row["grb_id"]." and usr_id=$usr_id ";
    	      $rs3 = db_sql($SQL);
      	    if ($row3 = $rs3->FetchRow()) {
						// muj request
      	      if ($row3[1])
					  		echo " class=\"status-myscheduled\"";
							else
					  		echo " class=\"status-mynocomprim\"";
						} else {
					  	echo " class=\"status-scheduled\"";
						}
					}
				}?>>
			<td class="datum" valign="top" align="center">
				<a name="<?=$row["tel_id"]?>"></a><?=date("G:i",$DB->UnixTimeStamp($row["tel_date_start"]))?>
				<?php

				// pokud se jedna o muj grab
				if ($row["my_grab"]) echo "<br><img alt=\"moje\" src=\"images/dot.gif\">";
				?>
			</td>
			<td valign="top">
				<b><?php

				// pokud se nejedna o grab a je mozno ho zadat, tak to umoznim
				if (!$row["grb_id"] && $DB->UnixTimeStamp($row["tel_date_start"]) >= $grab_time_limit) {
    			echo "<a onclick=\"return confirm('Chcete poøad ".htmlspecialchars($row["tel_name"])." vá¾nì grabnout?')\" ".
	      	"href=\"$PHP_SELF?action=grab_add&amp;tel_id=".$row["tel_id"]."&amp;tv_date=$tv_date\"".
			    " title=\"grabnout\" class=\"program\">".
			    htmlspecialchars($row["tel_name"])."</a>";
				} else if ($row["grb_id"] && $DB->UnixTimeStamp($row["tel_date_start"]) >= $grab_time_limit && !$row["my_grab"]) {
				// grab existuje, jeste neprobehl a ja jsem ho jeste nerequestoval
  				echo "<a onclick=\"return confirm('Chcete poøad ".htmlspecialchars($row["tel_name"])." vá¾nì taky grabnout?')\" ".
  				"href=\"$PHP_SELF?action=grab_add_me&amp;grb_id=".$row["grb_id"]."&amp;tv_date=$tv_date\"".
  				" title=\"grabnout\" class=\"program\">".
	  			htmlspecialchars($row["tel_name"])."</a>";
		  	} else echo htmlspecialchars($row["tel_name"]);

				?>
				</b><br><font size="1"><i><?=$row["tel_desc"]?></i></font>
				<?php
				// svuj grab s mohu zrusit, pokud ho pozadoval jeste nekdo dalsi tak se stejne nahraje
				if ($row["grb_id"] && $row["my_grab"]) {
					echo "<br /><a class=\"program\" href=\"$PHP_SELF?action=grab_del&amp;grb_id=".
				  	$row["grb_id"]."&amp;tv_date=$tv_date\">zru¹it&nbsp;grab</a>&nbsp;...&nbsp;";
        }
				// pro svuj grab muzu nastavit, ze se ma komprimovat do MPEG4
				if ($row["grb_id"] && $row["my_grab"] && !$row["grb_enc"]) {
  				echo "<a onclick=\"return confirm('Chcete poøad ".htmlspecialchars($row["tel_name"])." doopravdy rovnou zkomprimovat do MPEG4?')\" ".
  				"href=\"$PHP_SELF?action=grab_enc&amp;grb_id=".$row["grb_id"]."&amp;tv_date=$tv_date\"".
  				" title=\"grabnout\" class=\"program\">do MPEG4</a>";
//					echo "<a class=\"program\" href=\"$PHP_SELF?action=grab_enc&amp;grb_id=".
//				  	$row["grb_id"]."&amp;tv_date=$tv_date\">komprimovat</a>";
        }
				// pro svuj grab muzu nastavit, ze se nema komprimovat do MPEG4
				if ($row["grb_id"] && $row["my_grab"] && $row["grb_enc"]) {
  				echo "<a onclick=\"return confirm('Chcete poøad ".htmlspecialchars($row["tel_name"]).
						" doopravdy jenom nahrát a nechat v transport streamu (.ts)?')\" ".
  					"href=\"$PHP_SELF?action=grab_noenc&amp;grb_id=".$row["grb_id"]."&amp;tv_date=$tv_date\"".
  					" title=\"grabnout\" class=\"program\">do TS</a>";
//					echo "<a class=\"program\" href=\"$PHP_SELF?action=grab_noenc&amp;grb_id=".
//				  	$row["grb_id"]."&amp;tv_date=$tv_date\">nekomprimovat</a>";
        }
				?>
			</td>
		</tr>
				<?php
			} while (($row = $rs->FetchRow()) && $row["hour_frac"] == $hour_frac && $row["chn_id"] == $channel_array[$channel]["chn_id"]);
		} // if
?>
		</table>
	</td>
<?	} // for ?>	
</tr>
<? } // while ?>
</table>


<?php
if (isset($_GET["msg"])) {
?>
<script type="text/javascript">
<!--
<?php
	switch ($_GET["msg"]) {
		case "grb_add_fail_quota":
			echo "alert(\"ERROR: tento týden ji¾ nelze zadávat dal¹í graby\");\n";
			break;
		case "grb_add_fail_time":
			echo "alert(\"ERROR: po¾adavek o grab na u¾ odvysílaný poøad\");\n";
			break;
		case "grb_add_fail_exist":
			echo "alert(\"ERROR: grab ji¾ existuje\");\n";
			break;
		case "grb_add_fail_tel":
			echo "alert(\"ERROR: daný poøad neexistuje\");\n";
			break;
		case "grb_del_fail_time":
			echo "alert(\"ERROR: grab u¾ skonèil, nebo probíhá\");\n";
			break;
		case "grb_del_fail_exist":
			echo "alert(\"ERROR: daný grab neexistuje\");\n";
			break;
		default:
} ?>
//-->
</script>
<? } ?>
<table width="100%">
<tr>
	<td align="left">
		<a href="<?=$PHP_SELF?>?tv_date=<?=date("Ymd", mktime(0, 0, 0, $tv_month, $tv_day-1, $tv_year))?>">Pøedchozí den</a>
	</td>
	<td align="right">
		<a href="<?=$PHP_SELF?>?tv_date=<?=date("Ymd", mktime(0, 0, 0, $tv_month, $tv_day+1, $tv_year))?>">Následující den</a>
	</td>
</tr>
</table>

<?php
require("footer.php");

// vim: noexpandtab tabstop=4
?>
