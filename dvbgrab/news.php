<?php
require("authentication.php");
require("dblib.php");


require("status.inc.php");
require("header.php");

$menuitem = "";
require("menu.php");
require_once("language.inc.php");
?>

<td valign="top">
<table left="0" width="100%" border="0" cellspacing="5" cellpadding="5">
<col width="20%">
<col width="80%">
<tr>
  <td align="right">&nbsp;4. &nbsp;9. 2005 22:00</td>
  <td align="left"><? echo $msgNews1 ?></td>
</tr>
<tr>
  <td align="right">&nbsp;4. &nbsp;9. 2005 23:00</td>
  <td align="left"><? echo $msgNews2 ?></td>
</tr>
<tr>
  <td align="right">&nbsp;4. &nbsp;9. 2005 01:00</td>
  <td align="left"><? echo $msgNews3 ?></td>
</tr>
<tr>
  <td align="right">&nbsp;4. &nbsp;9. 2005 02:30</td>
  <td align="left"><? echo $msgNews4 ?></td>
</tr>
<tr>
  <td align="right">27. &nbsp;9. 2005 21:10</td>
  <td align="left"><? echo $msgNews5 ?></td>
</tr>
<tr>
  <td align="right">&nbsp;5. &nbsp;5. 2005 22:00</td>
  <td align="left"><? echo $msgNews6 ?></td>
</tr>
<tr>
  <td align="right">26. 11. 2005 22:00</td>
  <td align="left"><? echo $msgNews7 ?></td>
</tr>
</table>
</td>
<?php
require("footer.php");

// vim: noexpandtab tabstop=4
?>
