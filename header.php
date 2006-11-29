<?php
require_once("header.inc.php");
?>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="menu">
<?php
//$menuitem = "";
require_once("menu.php");
global $PHP_SELF;
?>
</td>
<td class="top">
<div class="status">
<table width="100%">
<tr><td class="left">
<a href="<?=$PHP_SELF?>"><img class="logo" src="images/top.black.png" alt="logo" /></a>
</td>
<td class="right" valign="top">
<span class="value" id="hodiny"><?=date("d.m. G:i")?></span>
<?php
$usr_name = "";
if (authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
  $SQL = "select usr_name from userinfo where usr_id=".(int)$_COOKIE["usr_id"];
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  $usr_name = $row[0];
  $SQL = "update userinfo set usr_last_activity = $DB->sysTimeStamp where usr_name='$usr_name'";
  do_sql($SQL);
  echo "<span class=\"item\"> :: "._MsgAccountLogged." </span><span class=\"value\">$usr_name</span>";
  echo " <a class=\"item\" href=\"index.php?action=logout\">("._MsgAccountLogout.")</a>";
  echo "<br />";
  echo "<span class=\"item\"> :: "._MsgAccountRecordCount." </span><span class=\"value\">";
  if ($menuitem != 1) {
    $tv_day = date("d");
    $tv_month = date("m");
    $tv_year = date("Y");
  }

  $week = date("W", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year));
  echo date("j.n.", mktime(0, 0, 0, $tv_month, $tv_day-strftime("%u", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year))+1, $tv_year));
  echo "-";
  echo date("j.n.", mktime(0, 0, 0, $tv_month, $tv_day+7-strftime("%u", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year)), $tv_year));

  echo " - ".get_user_grab($_COOKIE["usr_id"], $week)."/"._Config_grab_quota."</span>";
} else {
  echo "<span class=\"item\"> :: "._MsgAccountNoLogged."</span>";
}

?>
<br />
<a href="<?=$PHP_SELF."?lang=cs"?>"><img src="images/cs.gif" alt="cs.gif" /></a>
<a href="<?=$PHP_SELF."?lang=sk"?>"><img src="images/sk.gif" alt="sk.gif" /></a>
<a href="<?=$PHP_SELF."?lang=en"?>"><img src="images/en.gif" alt="en.gif" /></a>
<a href="<?=$PHP_SELF."?lang=fr"?>"><img src="images/fr.gif" alt="fr.gif" /></a>
</td></tr></table>
</div>
<div class="main">
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript" />
<script type="text/javascript">
<!--
_uacct = "UA-986588-2";
urchinTracker();

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
