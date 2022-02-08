<?php
require("authenticate.php");
require_once("dblib.php");
require_once("const.php");
require_once("config.php");
require_once("language.inc.php");
require_once("view.inc.php");
require("header.php");


// $type = sched  ... show all sheduled records
// $type = done   ... show all finished records
// $type = mygrab ... show my records

$type = $_GET["type"];
switch ($type) {
	default:
		$type = "sched";
	case "sched":
		$menuitem = 2;
		break;

	case "done":
		$menuitem = 3;
		break;

	case "mygrab":
		$menuitem = 4;
		break;

}
require("menu.php");

echo "<td valign=\"top\">";

if ($type == "sched") echo "<h2 class=\"planList\">".$msgPlanListSchedTitle."</h2>\n";
if ($type == "done") echo "<h2 class=\"planList\">".$msgPlanListDoneTitle."</h2>\n";
if ($type == "mygrab") echo "<h2 class=\"planList\">".$msgPlanListMygrabTitle."</h2>\n";

if ($type == "sched") {
	$SQL = "select count(*) from grab where grb_status='scheduled'";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo $msgPlanSchedCount.": $row[0]<br />\n";
}

if ($type == "done") {
	$SQL = "select count(*) from grab where grb_status='done'";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo $msgPlanDoneCount.": $row[0]<br />\n";
	echo "$msgPlanDoneInfo<br />\n";
}

if ($type == "mygrab") {
	$SQL = "select count(*) from grab g, request r where 
					g.grb_id=r.grb_id and
					g.grb_status='scheduled' and
					r.usr_id=$usr_id";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo $msgPlanSchedCount.": $row[0]<br />\n";

	$SQL = "select count(*) from grab g, request r where 
					g.grb_id=r.grb_id and
					g.grb_status='done' and
					r.usr_id=$usr_id";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	echo $msgPlanDoneCount.": $row[0]<br />\n";
	echo "$msgPlanDoneInfo<br />\n";
}

// date of oldest and finished record 
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
				u.usr_id, u.usr_name, u.usr_email, r.req_output
			from 
				channel c inner join television t on (c.chn_id=t.chn_id) 
				inner join grab g on (t.tel_id=g.tel_id)
				inner join request r on (g.grb_id=r.grb_id)
				inner join user u on (r.usr_id=u.usr_id)
			where";

if ($type == "sched") $SQL .= " g.grb_status='scheduled' or g.grb_status='processing'";
if ($type == "done") {
	$SQL .= " g.grb_status='done'";
}
if ($type == "mygrab") $SQL .= " g.grb_status<>'deleted' and u.usr_id=$usr_id and g.grb_date_start >='$grab_datetime'";

$SQL .= " order by year(g.grb_date_start)".(($type=="sched")?"":" desc").", if(hour(g.grb_date_start)<$midnight, dayofyear(g.grb_date_start)-1, dayofyear(g.grb_date_start))".(($type=="sched")?"":" desc").", if(hour(g.grb_date_start)<$midnight, hour(g.grb_date_start)+24, hour(g.grb_date_start)), minute(g.grb_date_start), c.chn_order, u.usr_name";

$res = db_sql($SQL);

if ($res->RecordCount = 0) {
	echo $msgPlanNothing; 
	require("footer.php");
	exit;
}

$old_grb_day = "";
$old_tel_id = "";
echo "<table class=\"grabList\">\n";

global $DB;
while ($row = $res->FetchRow()) {
	$tel_id = $row['tel_id'];
	if ($tel_id != $old_tel_id) {
		if ($old_tel_id != "") {
			$grb_date_start = $DB->UnixTimeStamp($old_row["grb_date_start"]);
			$grb_date_end = $DB->UnixTimeStamp($old_row["grb_date_end"]);
			$old_grb_day = show_grab_day($grb_date_start, $old_grb_day);
			show_planned_grab($grb_date_start, $grb_date_end,
				$old_row['grb_id'], $old_row['grb_status'],
				$old_row['chn_name'], $old_row['tel_id'], $old_row['tel_name'],
				$planned_requests);
		}
		$old_tel_id = $tel_id;
		$old_row = $row;
		$planned_requests = array();
	}

	$planned_requests[] = array('usr_id' => $row['usr_id'],
		'usr_name' => $row['usr_name'],
		'usr_email' => $row['usr_email'],
		'req_output' => $row['req_output']);
}
// Last record
if ($old_tel_id != "") {
	$grb_date_start = $DB->UnixTimeStamp($old_row["grb_date_start"]);
	$grb_date_end = $DB->UnixTimeStamp($old_row["grb_date_end"]);
	$old_grb_day = show_grab_day($grb_date_start, $old_grb_day);
	show_planned_grab($grb_date_start, $grb_date_end,
		$old_row['grb_id'], $old_row['grb_status'],
		$old_row['chn_name'], $old_row['tel_id'], $old_row['tel_name'],
		$planned_requests);
}

echo "</table>\n";
echo "</td></tr></table>\n";
require("footer.php");

// vim: noexpandtab tabstop=4
?>
