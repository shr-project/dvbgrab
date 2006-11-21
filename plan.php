<?php
require_once("dolib.inc.php");
require_once("const.php");
require_once("config.php");
require_once("language.inc.php");
require_once("view.inc.php");
require("authenticate.php");

// $type = sched  ... show all sheduled records
// $type = done   ... show all finished records
// $type = mygrab ... show my records

$type = $_GET["type"];
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
    $type = "sched";
    $menuitem = 2;
    break;

}
require("header.php");
echo '<table width="100%">';
echo '<tr>';
echo '<td valign="top">';

if ($type == "sched") {
  $SQL = "select count(distinct(grb_id)) from request where req_status='scheduled'";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  echo "<h2>"._MsgPlanListSchedTitle."</h2>\n";
  echo _MsgPlanSchedCount.": $row[0]<br />\n";
}

if ($type == "done") {
  $SQL = "select count(distinct(grb_id)) from request where req_status='done'";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  echo "<h2>"._MsgPlanListDoneTitle."</h2>\n";
  echo _MsgPlanDoneCount.": $row[0]<br />\n";
}

if ($type == "mygrab") {
  echo "<h2>"._MsgPlanListMygrabTitle."</h2>\n";
  $SQL = "select count(distinct(grb_id)) from request where req_status='scheduled' and usr_id=$usr_id";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  echo _MsgPlanSchedCount.": $row[0]<br />\n";

  $SQL = "select count(distinct(grb_id)) from request where req_status='done' and usr_id=$usr_id";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  echo _MsgPlanDoneCount.": $row[0]<br />\n";
}

// date of oldest and finished record 
$SQL = "select grb_date_start 
        from grab g,request r 
        where req_status='done' and g.grb_id=r.grb_id
        order by g.grb_date_start
        limit 1";
$rs = do_sql($SQL);
if ($row = $rs->FetchRow()) {
  $grab_datetime = $row[0];
} else {
  $grab_datetime = '0000-00-00 00:00:00';
}

$SQL = "select g.grb_id, 
               r.req_status, 
               t.tel_name,
               c.chn_name, 
               c.chn_logo, 
               grb_date_start, 
               grb_date_end, 
               g.tel_id as tel_id,
               u.usr_id, 
               u.usr_name, 
               u.usr_email, 
               r.req_output
        from channel c inner join television t on (c.chn_id=t.chn_id) 
             inner join grab g on (t.tel_id=g.tel_id)
             inner join request r on (g.grb_id=r.grb_id)
             inner join usergrb u on (r.usr_id=u.usr_id)
        where";

if ($type == "sched")  $SQL .= " r.req_status='scheduled' or r.req_status='processing'";
if ($type == "done")   $SQL .= " r.req_status='done'";
if ($type == "mygrab") $SQL .= " u.usr_id=$usr_id";
// and g.grb_date_start >='$grab_datetime'";

$SQL .= " order by g.grb_date_start".(($type=="sched")?"":" desc").", c.chn_order";

if ($type == "done")   $SQL .= " limit 100";
if ($type == "mygrab") $SQL .= " limit 100";

$res = do_sql($SQL);

require_once("grabinfoJS.php");

?>
<script type="text/javascript">
<!--
  var grabinfos = new grabInfos(); 
// -->
</script>
<?
if ($res->RecordCount() == 0) {
  echo _MsgPlanNothing;
} else {
  $old_grb_day = "";
  echo "<table class=\"grabList\">\n";
  
  global $DB;
  
  while ($row = $res->FetchRow()) {
    $grb_id = $row[0];
    $grb_timeStamp=$DB->UnixTimeStamp($row["grb_date_start"]);
    if ($DB->UserTimeStamp($row["grb_date_start"],"G")<_Config_midnight) {
      $grb_timeStamp-=24*3600;
    }
    $grb_day = $dow[$DB->UserTimeStamp($grb_timeStamp,"l")].$DB->UserDate($grb_timeStamp,", d. m. Y");
    if ($grb_day != $old_grb_day) {
  
      if ($old_grb_day != "") {
        echo "<tr><td colspan=\"6\">&nbsp;</td></tr>\n";
      }
      echo "<tr><th colspan=\"6\">&nbsp;&nbsp;&nbsp;$grb_day</th></tr>\n";
    }
    if (!empty($row[1])) {
      $hi="Hi";
    } else {
      $hi="";
    }

    $old_grb_day = $grb_day;


    echo "  <tr";
    show_grab_class($grb_id, $row['req_status'], $row['usr_id'] == $usr_id);
    echo " onmouseover=\"grabinfos.show($grb_id); setTimeout('grabinfos.loadMe($grb_id)',200);\"";
    echo " onmouseout=\"grabinfos.hide($grb_id)\">\n";
    echo "    <td width=\"30\">&nbsp;</td>\n";
    echo "    <td><img class=\"programLogo\" alt=\"".$row["chn_name"]."\" title=\"".$row["chn_name"]."\" src=\"images/logos/".$row["chn_logo"]."\"></td>\n";
    echo "    <td width=\"110\" class=\"programDate".$hi."\">";
//    if ($row["req_status"] == "missed") {
//      echo "&nbsp;&nbsp;&nbsp;"._MsgPlanGrabMissed;
//    } else {
      echo $DB->UserTimeStamp($row["grb_date_start"],"H:i")."-".$DB->UserTimeStamp($row["grb_date_end"],"H:i");
//    }
    echo "    </td>\n";
  
    echo "    <td><a class=\"programName".$hi."\" href=\"tvprog.php?tv_date=".date("Ymd", $grb_timeStamp).
      "#".$row["tel_id"]."\">".htmlspecialchars($row["tel_name"])."</a><div class=\"grabInfo\" style=\"margin-left: 30pt; display:none; position: absolute\" id=\"grabinfo_".$grb_id."\"></div></td>\n";
  
    if ($type != "mygrab") {
//      echo "    <td width=\"20\">&nbsp;</td>\n";
      echo "    <td><a class=\"programLink\" href=\"mailto:".str_replace("@", "@NOSPAM.", $row["usr_email"])."\">".$row["usr_name"]."</a></td>\n";
    } else {
      if ($row["req_status"] == "done") {
        if ($row["req_output"] != "") {
          echo "    <td><a class=\"programLink\" href=\"".$row["req_output"]."\">"._MsgPlanGrabLink."</a></td>\n";
        } else {
          echo "    <td class=\"programLink\">"._MsgPlanGrabLinkNone."</td>\n";
        }
      } else if ($row["req_status"] == "deleted") {
        echo "    <td class=\"programLink\">"._MsgPlanGrabDeleted."</td>\n";
      } else {
        echo "    <td>&nbsp;</td>\n";
      }
    }
//    echo "  <tr><td colspan=\"8\"><div style=\"display:none\" id=\"planGrab".$grb_id."\"></div></td></tr>";
    echo "  </tr>\n";
  }    
  echo "</table>\n";
}
echo "</td>\n";
echo "<td valign=\"top\" class=\"legend\">\n";
require_once("legend.inc.php");
echo "</td>\n";
echo "</tr>\n</table>\n";
echo "<br /><br /><br /><br /><br /><br />";
echo "<br /><br /><br /><br /><br /><br />";
echo "<br /><br /><br /><br /><br /><br />";
echo "<br /><br /><br /><br /><br /><br />";
echo "<br /><br /><br /><br /><br /><br />";
require("footer.php");
?>
