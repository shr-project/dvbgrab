<?

function print_list_tv($usr_id,$tv_date,$query) {
  global $DB;
  global $PHP_SELF;
  
  $addition = "tv_date=$tv_date&query=$query&"; 

  $tv_day = substr($tv_date, 6, 2);
  $tv_month = substr($tv_date, 4, 2);
  $tv_year = substr($tv_date, 0, 4);

  $tel_date_from=$DB->DBTimeStamp(mktime(_Config_midnight, 0, 0, $tv_month, $tv_day, $tv_year));
  $tel_date_to=$DB->DBTimeStamp(mktime(_Config_midnight, 0, 0, $tv_month, $tv_day+1, $tv_year));

  // vyber vsechny stanice, pro ktere mame na $tv_date den program
  $SQL = "select c.chn_id, 
                 c.chn_name, 
                 c.chn_logo
          from channel c, 
               television t
          where c.chn_id=t.chn_id and
                tel_date_start > $tel_date_from and
                tel_date_start < $tel_date_to and
                c.chn_enabled = 1
          group by c.chn_id, c.chn_name, c.chn_logo, c.chn_order
          order by c.chn_order";
  $rsChannels = do_sql($SQL);

  $channel_array = array();
  $i = 0;
  require_once("telinfoJS.php");

  ?>
  <script type="text/javascript">
  <!--
    var telinfos = new telInfos();
  // -->
  </script>
  <?
  while ($rowChannel = $rsChannels->FetchRow()) {
    $channel_array[$i] = array();
    $channel_array[$i]["chn_id"] = $rowChannel[0];
    $channel_array[$i]["chn_name"] = $rowChannel[1];
    $channel_array[$i]["chn_logo"] = $rowChannel[2];
    $i++;
  }
  echo '<table width="100%" style="table-layout: fixed">';
  for ($channel=0; $channel<count($channel_array); $channel++)  {
    echo "<col width=\"".(100/count($channel_array))."\" />";
  }
  echo "\n<tr>\n";
  // zobraz loga stanic, sirka sloupce je 100% / pocet_stanic
  for ($channel=0; $channel<count($channel_array); $channel++)  {
    echo '<td>';
    $chn_logo = $channel_array[$channel]["chn_logo"];
    $chn_name = $channel_array[$channel]["chn_name"];
    echo "<img class=\"programLogo\" alt=\"$chn_name\" title=\"$chn_name\" src=\"images/logos/$chn_logo\"/>";
    echo "</td>";
  }
  echo "\n</tr>\n";
  
  // vyber vsechny porady pro aktualni den od _Config_midnight hodiny rano do _Config_midnight hodiny rano nasledujiciho dne
  // u kazdeho poradu oznac, jestli je to grab a jsem ho zadal a nebo jsem pro nej hlasoval
  $SQL = "select c.chn_name,
                 c.chn_logo,
                 t.chn_id,
                 t.tel_id,
                 t.tel_name,
                 t.tel_desc,
                 t.tel_typ,
                 t.tel_category,
                 t.tel_series,
                 t.tel_episode,
                 t.tel_part,
                 t.tel_date_start,
                 g.grb_id,
                 (select min(req_status) from request as r where r.grb_id=g.grb_id) as req_status,
                 (select usr_id from request as r left join userreq as u using (req_id) where r.grb_id=g.grb_id and u.usr_id=$usr_id) as my_grab,
                 ".$DB->SQLDate('H','tel_date_start')." as hour,
                 ".$DB->SQLDate('Ymd','tel_date_start')." as day,";
  if (_Config_db_type == "postgres") {
    $SQL .="floor(cast(".$DB->SQLDate('H','tel_date_start')." as numeric)/"._Config_hour_frac_item.") as hour_frac ";
  } else {
    $SQL .="floor(".$DB->SQLDate('H','tel_date_start')."/"._Config_hour_frac_item.") as hour_frac ";
  }
  $SQL .="from television t
               left join channel c using (chn_id)
               left join grab g using (tel_id)
          where tel_date_start > $tel_date_from and
                tel_date_start < $tel_date_to 
          order by day, hour_frac, chn_order, tel_date_start";

  $rs = do_sql($SQL);

  if ($rs->RecordCount() == 0) {
    echo '<tr><td><span class="warning">'._MsgProgNotAvailable.'</span></td></tr>';
  } else {
    $show_link=false;
    $show_logo=false;
    $use_diacritics=false;
    $highlight_strings=null;
    $query=null;

    $row = $rs->FetchRow();
    while ($row) {
      $firstRow = $row;
      echo "<tr>\n";
      
      $akt_hour_frac = $row["hour_frac"];

      // projdeme vsechny stanice pro casove odbobi $hour_frac
      for ($channel=0; $channel<count($channel_array); $channel++) {
        $akt_chn_id = $channel_array[$channel]["chn_id"];
        echo "<td valign=\"top\"><table>\n";
        // pokud v casovem obdobi $hour_frac na stanici $channel nic neni
        if (!$row || $row["hour_frac"] != $akt_hour_frac || $row["chn_id"] != $akt_chn_id) {
          echo '<tr><td colspan="2">&nbsp;</td></tr>';
        } else {
          // vypiseme porady v casovem obdobi $hour_frac na stanici $channel
          do {
            if ($row["hour_frac"] != $akt_hour_frac || $row["chn_id"] != $akt_chn_id) {
              break;
            }
            show_television_row($row, $addition, $highlight_strings, $use_diacritics, $show_link, $show_logo, $channel==count($channel_array)-1);
          } while ($row = $rs->FetchRow());
        }
        echo "</table></td>\n";
      }
      echo "</tr>\n";
      if ($row == $firstRow) {
        $row = $rs->FetchRow();
      }
    }
  }

  $next_tv_date = date("Ymd", mktime(0, 0, 0, $tv_month, $tv_day+1, $tv_year));
  $prev_tv_date = date("Ymd", mktime(0, 0, 0, $tv_month, $tv_day-1, $tv_year));
  echo '</table>'."\n";
  echo '<table width="100%">';
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
  echo '<tr><td align="left">'."\n";
  echo '<a href="'.$PHP_SELF.'?tv_date='.$prev_tv_date.'">'._MsgProgPrevDay.'</a>';
  echo '</td><td align="right">'."\n";
  echo '<a href="'.$PHP_SELF.'?tv_date='.$next_tv_date.'">'._MsgProgNextDay.'</a>';
  echo '</td></tr>'."\n";
  echo '</table>'."\n";
}
?>
