<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/tvgrab.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2">
<?php
require_once("language.inc.php");
?>
<title><? echo $msgGlobalTitle ?></title>
</head>

<body>
<div align="center">
<a href="index.php" class="h"><img class="top" src="images/top.png" alt="<? echo $msgGlobalTitle ?>"></a>
</div>
<div class="status">
<span class="value" id="hodiny"><?=date("d.m. G:i")?></span>
<?php
$usr_name = "";
if (authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
    $SQL = "select usr_name from user where usr_id=".(int)$_COOKIE["usr_id"];
    $rs = db_sql($SQL);
    $row = $rs->FetchRow();
    $usr_name = $row[0];
    echo "<span class=\"item\"> :: $msgAccountLogged </span><span class=\"value\">$usr_name</span>";
    echo " <a class=\"item\" href=\"index.php?action=logout\">($msgAccountLogout)</a>";
    echo "<span class=\"item\"> :: $msgAccountRecordCount </span><span class=\"value\">";
  if ($menuitem != 1) {
    $tv_day = date("d");
    $tv_month = date("m");
    $tv_year = date("Y");
  }

  $week = date("W", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year));
  echo date("j.n.", mktime(0, 0, 0, $tv_month, $tv_day-strftime("%u", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year))+1, $tv_year));
  echo "-";
  echo date("j.n.", mktime(0, 0, 0, $tv_month, $tv_day+7-strftime("%u", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year)), $tv_year));

  echo " - ".get_user_grab($_COOKIE["usr_id"], $week)."/$grab_quota</span>";
} else {
    echo "<span class=\"item\"> :: $msgAccountNoLogged</span>";
}
?>
</div>
<div>&nbsp;</div>
<script language="JavaScript1.2" type="text/javascript">
<!--

function tick() {
  today = new Date();
  day = today.getDate();
  month = today.getMonth()+1; 
  hour = today.getHours();
  minute = today.getMinutes();
  minutes=((minute < 10)?"0":"")+minute;

  document.getElementById("hodiny").innerHTML = day+"."+month+". "+hour+':'+minutes;
  window.setTimeout("tick();", 1000);
}
window.onload = tick;
//-->
</script>
