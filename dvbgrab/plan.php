<?php
require("dblib.php");
require("authenticate.php");
require("const.php");
require("config.php");
require("status.inc.php");
require("header.php");

// $type = sched  ... zobrazime vsechny naplanovane graby
// $type = done   ... zobrazime vsechny hotove graby
// $type = mygrab ... zobrazime vsechny moje graby
$type = $_GET["type"];
if ($type != "sched" && $type != "done" && $type != "mygrab") $type = "sched";
switch ($type) {
	case "sched":
		$menuitem = 2;
		break;

	case "done":
		$menuitem = 3;
		break;

	case "mygrab":
		$menuitem = 4;
		break;

	default:
}
require("menu.php");

echo "<td valign=\"top\">";

if ($type == "sched") echo "<h2 class=\"planList\">Seznam naplánovaných grabù</h2>\n";
if ($type == "done") echo "<h2 class=\"planList\">Seznam hotových grabù</h2>\n";
if ($type == "mygrab") echo "<h2 class=\"planList\">Seznam mých grabù</h2>\n";

if ($type == "sched") {
	$SQL = "select count(*) from grab where grb_status='scheduled'";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo "Plánovaných grabù: $row[0]<br />\n";
}

if ($type == "done") {
	$SQL = "select count(*) from grab where grb_status='done'";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo "Hotových grabù: $row[0]<br />\n";
}

if ($type == "mygrab") {
	$SQL = "select count(*) from grab g, request r where 
					g.grb_id=r.grb_id and
					g.grb_status='scheduled' and
					r.usr_id=$usr_id";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo "Plánovaných grabù: $row[0]<br />\n";

	$SQL = "select count(*) from grab g, request r where 
					g.grb_id=r.grb_id and
					g.grb_status='done' and
					r.usr_id=$usr_id";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo "Hotových grabù: $row[0]<br /><br />\n";
}

// zjisti datum nejstarsiho hotoveho grabu 
$SQL = "select grb_date_start from grab where grb_status='done'
			order by grb_date_start limit 1";
$rs = db_sql($SQL);
if ($row = $rs->FetchRow()) {
	$grab_datetime = $row[0];
} else {
	$grab_datetime = '0000-00-00 00:00:00';
}

$SQL = "select g.grb_id, g.grb_status, t.tel_name,
				c.chn_name, grb_date_start, grb_date_end, g.tel_id as tel_id,
				u.usr_id, u.usr_name, u.usr_email, r.grb_enc
			from 
				channel c inner join television t on (c.chn_id=t.chn_id) 
				inner join grab g on (t.tel_id=g.tel_id)
				inner join request r on (g.grb_id=r.grb_id)
				inner join user u on (r.usr_id=u.usr_id)
			where";

if ($type == "sched") $SQL .= " g.grb_status='scheduled' or g.grb_status='processing'";
if ($type == "done") $SQL .= " g.grb_status='done'";
if ($type == "mygrab") $SQL .= " g.grb_status<>'deleted' and u.usr_id=$usr_id and g.grb_date_start >='$grab_datetime'";

$SQL .= " order by year(g.grb_date_start)".(($type=="sched")?"":" desc").", if(hour(g.grb_date_start)<$midnight, dayofyear(g.grb_date_start)-1, dayofyear(g.grb_date_start))".(($type=="sched")?"":" desc").", if(hour(g.grb_date_start)<$midnight, hour(g.grb_date_start)+24, hour(g.grb_date_start)), minute(g.grb_date_start), c.chn_order";

if ($type == "done") $SQL .= " limit 100";
if ($type == "mygrab") $SQL .= " limit 100";

$res = db_sql($SQL);

if ($res->RecordCount = 0) {
	echo "Nenalezeny ¾ádné záznamy."; 
	require("footer.php");
	exit;
}

$old_grb_day = "";
echo "<table class=\"grabList\">\n";

global $DB;

while ($row = $res->FetchRow()) {
  $grb_timeStamp=$DB->UnixTimeStamp($row["grb_date_start"]);
  if ($DB->UserTimeStamp($row["grb_date_start"],"G")<$midnight) {
		$grb_timeStamp-=24*3600;
	}
	$grb_day = $dow[$DB->UserTimeStamp($grb_timeStamp,"l")].$DB->UserDate($grb_timeStamp,", d. m. Y");

	if ($grb_day != $old_grb_day) {
		if ($old_grb_day != "") {
			echo "<tr><td colspan=\"".(($type=="mygrab")?"5":"7")."\">&nbsp;</td></tr>\n";
		}
		echo "<tr><th colspan=\"".(($type=="mygrab")?"5":"7")."\">&nbsp;&nbsp;&nbsp;$grb_day</th></tr>\n";
	}
	$old_grb_day = $grb_day;

	echo "	<tr>\n";
	echo "		<td width=\"30\">&nbsp;</td>\n";
	echo "		<td width=\"12\" align=\"center\" class=\"status-";
  if ($row["grb_status"] != "scheduled") {
    echo $row["grb_status"]."\">\n";
  } else {
    // oznacime graby, ktere maji oznaceny ostatni a ja ne
    if ($row["usr_id"]==$usr_id) {
      // muj request
      if ($row["grb_enc"])
        echo "myscheduled\">\n";
      else
        echo "mynocomprim\">\n";
    } else {
      echo "scheduled\">\n";
    }
  }
	echo "			".(($row["usr_id"]==$usr_id)?"<img alt=\"moje\" src=\"images/dot.gif\">":"&nbsp;");
	echo "		</td>\n";
	echo "		<td width=\"60\">&nbsp;&nbsp;<img alt=\"".$row["chn_name"]."\"".
		" src=\"images/".$channel_logo[$row["chn_name"]]."\"></td>\n";

	echo "<td width=\"110\"><b>";
	if ($row["grb_status"] == "missed") {
		echo "&nbsp;&nbsp;&nbsp;negrabnuto";
	} else echo ($DB->UserTimeStamp($row["grb_date_start"],"H:i")."-".$DB->UserTimeStamp($row["grb_date_end"],"H:i"));
	echo "</b></td>\n";

	echo "		<td><a href=\"tvprog.php?tv_date=".
		date("Ymd", $row["grb_date_start"]-((date("G", $row["grb_date_start"])<$midnight)?1:0)*24*3600).
		"#".$row["tel_id"]."\">".htmlspecialchars($row["tel_name"])."</a>".
		"</td>\n";

	if ($type != "mygrab") {
		echo "		<td width=\"20\">&nbsp;</td>\n";
		echo "		<td><a href=\"mailto:".str_replace("@", "@NOSPAM.", $row["usr_email"])."\">".$row["usr_name"]."</a></td>\n";
	}
	echo "	</tr>\n";
}		
echo "</table>\n";
echo "</td></tr></table>\n";
require("footer.php");

// vim: noexpandtab tabstop=4
?>
