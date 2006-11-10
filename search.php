<?php
require_once("charset.inc.php");

global $DB;  // pripojeni do databaze

function print_results($usr_id,$query) {
  global $DB;
  $MAX_SEARCH_RESULTS = 50;
  $query_array = explode(" ", $query);

  if (sizeof($query_array) == 0) {
    echo _MsgSearchErrorNoInput;
    include("footer.php");
    exit;
  }
  if (sizeof($query_array) > 10) {
    echo _MsgSearchErrorManyInput;
    include("footer.php");
    exit;
  }

  // kdyz $query obsahuje diakritiku, tak budeme hledat s diakritikou
  $use_diacritics = 0;
  for ($i=0; $i<sizeof($query_array); $i++) {
    if (is_diacritics_used($query_array[$i])) {
      $use_diacritics = 1;
    }
  }

  // vytvorime dotaz na vyhledani retezcu
  $query_sql = "";
  reset($query_array);
  for ($i=0; $i<sizeof($query_array); $i++) {
    if ($use_diacritics) {
      $query_sql .= "((lower(t.tel_name) like lower('%".$query_array[$i]."%')) or ";
      $query_sql .= " (lower(t.tel_desc) like lower('%".$query_array[$i]."%')))";
    } else {
      $query_sql .= "((lower(".sql_strip_diacritics("t.tel_name").") like lower('%".$query_array[$i]."%')) or ";
      $query_sql .= " (lower(".sql_strip_diacritics("t.tel_desc").") like lower('%".$query_array[$i]."%')))";
    }
  }

  $SQL = "select c.chn_name, 
                 c.chn_logo,
                 t.tel_id, 
                 t.tel_name, 
                 t.tel_desc,
                 t.tel_typ,
                 t.tel_category,
                 t.tel_series,
                 t.tel_episode,
                 t.tel_part,
                 tel_date_start,
                 g.grb_id,
                 r.req_status,
                 ".$DB->IfNull('r.usr_id',"'0'")." as my_grab
          from channel c inner join television t on (c.chn_id=t.chn_id)
               left join grab g on (t.tel_id=g.tel_id)
               left join request r on (g.grb_id=r.grb_id and r.usr_id=$usr_id)
          where $query_sql and
                tel_date_start>=".$DB->sysTimeStamp."
          order by tel_date_start";

  //echo $SQL;
  $rs = do_sql($SQL);

  $res_count = $rs->RecordCount();
  echo _MsgSearchResultsCount." $res_count<br />";
  if ($res_count > $MAX_SEARCH_RESULTS) {
    echo _MsgSearchResultsCountsLimit." $MAX_SEARCH_RESULTS";
  }
  if ($res_count > 0) {
    require_once("telinfoJS.php");

    ?>
    <script type="text/javascript">
    <!--
      var telinfos = new telInfos();
    // -->
    </script>
    <?

    echo "<table width=\"100%\">";
    $cur_res = 1;
    $show_link = true;
    $show_logo = true;
    while ($row = $rs->FetchRow()) {
      show_television_row($row,$query,$query_array,$use_diacritics,$show_link,$show_logo);
      $cur_res++;  
      if ($cur_res > $MAX_SEARCH_RESULTS) {
        break;
      }
    }
    echo "</table>";
  }
} 
?>
