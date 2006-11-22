<?php
require_once("authentication.php");
require_once("dolib.inc.php");
require_once("language.inc.php");

$menuitem=8;
require_once("header.php");
?>

<h2><? echo _MsgMenuNews?></h2>
<table left="0" width="100%" border="0" cellspacing="5" cellpadding="5">
<col width="20%">
<col width="80%">
<?
$SQL = "select * from news order by news_date";

$rs = do_sql($SQL);
while ($row = $rs->FetchRow()) {
  echo '<tr>';
  echo '<td align="right">';
  echo $DB->UserTimeStamp($row[0],"Y-m-d H:i");
  echo '</td>';
  echo '<td align="left">';
  echo $row[1];
  echo '</td>';
  echo '<tr>';
}
echo '</table>';
echo '</td>';

require("footer.php");

// vim: noexpandtab tabstop=4
?>
