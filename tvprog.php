<?php
require("authenticate.php");
require_once("dolib.inc.php");
require_once("config.php");
require_once("language.inc.php");
require_once("view.inc.php");

// kontrola parametru $_GET["tv_date"]
$tv_date =  date("Ymd", time()-((date("G")<_Config_midnight)?1:0)*24*3600);
if (ereg("([0-9]{4})([0-9]{2})([0-9]{2})", $_GET["tv_date"], $regs)
    && $regs[1]>=2003 && $regs[1]<2050 && $regs[2]>=1 && $regs[2]<=12 && $regs[3]>=1 && $regs[3]<=31
    && date("Ymd", mktime(0, 0, 0, $regs[2], $regs[3], $regs[1])) == $_GET["tv_date"]) {

  $tv_date = $_GET["tv_date"];
}

global $DB;  // pripojeni do databaze

require("actions.php");
grabAction($_GET["action"],$_GET["query"],$_GET["tv_date"],$_GET["tel_id"],$_GET["grb_id"]);

require("const.php");
$menuitem = 1;
require("header.php");

$tv_day = substr($tv_date, 6, 2);
$tv_month = substr($tv_date, 4, 2);
$tv_year = substr($tv_date, 0, 4);
echo '<table width="100%">';
echo "\n<tr>\n";
echo "\n<td valign=\"top\">\n";
echo "<h2>"._MsgProgTitle." - ";
echo $dow[date("l", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year))];
echo date(" j. n. Y", mktime(0, 0, 0, $tv_month, $tv_day, $tv_year));
?>
</h2>
<script type="text/javascript">
<!--
  function change_date(f) {
    selectItem = f["tv_date"];
    location.href = "?tv_date="+selectItem[selectItem.selectedIndex].value;
  }
//-->
</script>

<form method="get" action="<?=$PHP_SELF?>">
<table width="100%">
  <tr>
    <th><? echo _MsgProgTitleDay ?></th>
    <td align="right">
      <select name="tv_date" onchange="change_date(this.form)">
        <?php
        $SQL = "select max(tel_date_start) from television";
        $rs = do_sql($SQL);
        $row = $rs->FetchRow();
        $datediff = ($DB->UnixTimeStamp($row[0]) - time()) / (24*3600);
        $midnight_shift = (date("h")<_Config_midnight)?1:0;
        $future_limit = min(_Config_tv_days, $datediff + $midnight_shift);
        $history_limit = -_Config_grab_history - $midnight_shift;

        $day = date("d");
        $month = date("m");
        $year = date("Y");

        // vypis datumy od (dnes - $history_limit) na $future_limit dopredu
        for ($i=$history_limit; $i<$future_limit; $i++) {
          $akt_date = date("Ymd", mktime(0, 0, 0, $month, $day+$i, $year));
          $show_date = $dow_short[date("D", mktime(0, 0, 0, $month, $day+$i, $year))];
          $show_date .= date(" j. n. Y", mktime(0, 0, 0, $month, $day+$i, $year));
          echo '<option value="'.$akt_date.'"'.(($i==0)?' class="today"':'').(($akt_date==$tv_date)?' selected="selected"':'').'>';
          echo $show_date;
          echo "</option>\n";
        }
        ?>
      </select>
    </td>
    <td align="left">
      <input type="submit" value="<? echo _MsgProgShowButton ?>"/>
    </td>
  </tr>
  <tr>
    <th>
      <? echo _MsgProgSearch ?>
    </th>
    <td align="right">
      <input type="text" size="20" name="query" value="<?= $_GET["query"] ?>" />
    </td>
    <td align="left">
      <input type="submit" value="<? echo _MsgProgSearchButton ?>" />
    </td>
  </tr>
  <?
  $msg = $_GET["msg"];
  if (!empty($msg)) {
    echo "<tr><td colspan=\"3\">\n";
      printMsg($msg);
    echo "</td></tr>\n";
  }
  ?>
</table>
</form>

<?
$query = $_GET["query"];
if (!empty($query)) {
  require_once("search.php");
  print_results($usr_id,$query);
} else {
  require_once("listtv.php");
}
?>
</td>
<td class="legend">
<? require("legend.inc.php"); ?>
</td>
</tr>
</table>
<!-- konec zahlavi -->
<?php
if (empty($query)) {
  echo "</div>\n</td>\n</tr>\n"; // zavru main div a ukoncim radek s menu
  echo "<tr>\n<td colspan=\"3\"><div>\n";
  print_list_tv($usr_id,$tv_date);
}
require("footer.php");
?>
