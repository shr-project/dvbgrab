<?php
require_once("charset.inc.php");

global $DB;  // pripojeni do databaze

function print_results($usr_id,$query,$tv_date) {
  global $DB;
  $MAX_SEARCH_RESULTS = 50;
  $addition = "tv_date=$tv_date&query=$query&";
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

  // vytvorime dotaz na vyhledani retezcu
  $query_sql = "";
  foreach ($query_array as $query) {
    $lowerQuery = mb_strtolower($query, "utf-8");
    if (is_diacritics_used($lowerQuery)) {
      $query_sql .= "((t.tel_name like '%".$query."%') or ";
      $query_sql .= " (t.tel_desc like '%".$query."%'))";
    } else {
      $query_sql .= "((lower(".sql_strip_diacritics("t.tel_name").") like '%".$lowerQuery."%') or ";
      $query_sql .= " (lower(".sql_strip_diacritics("t.tel_desc").") like '%".$lowerQuery."%'))";
    }
    $query_sql .= " and ";
  }
  $query_sql .= " (1=1) ";

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
                 (select min(req_status) from request as r where r.grb_id=g.grb_id) as req_status,
                 (select usr_id from request as r left join userreq as u using (req_id) where r.grb_id=g.grb_id and u.usr_id=$usr_id) as my_grab
          from television t
               left join channel c on (c.chn_id=t.chn_id)
               left join grab g on (g.tel_id=t.tel_id)
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
      show_television_row($row,$addition,$query_array,$use_diacritics,$show_link,$show_logo);
      $cur_res++;  
      if ($cur_res > $MAX_SEARCH_RESULTS) {
        break;
      }
    }
    echo "</table>";
  }
} 
?>
