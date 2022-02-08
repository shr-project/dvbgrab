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
<a href="<?=$PHP_SELF?>"><img class="logo" src="images/top.png" alt="logo" /></a>
</td>
<td class="right" valign="top">
<a href="http://sourceforge.net"><img src="http://sflogo.sourceforge.net/sflogo.php?group_id=184176&amp;type=6" width="210" height="62" border="0" alt="SourceForge.net Logo" /></a>
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
