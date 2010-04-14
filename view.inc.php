<?php

require_once("dolib.inc.php");
require_once("charset.inc.php");
require_once("language.inc.php");

/**
* Shows HTML for the TV program.
* @param tel_id television id
* @param tel_date_start unix timestamp of the television start
* @param tel_name short television name
* @param tel_desc television description
* @param grb_id grab id or empty when this television is not grabbed yet
* @param req_status grab status or empty
* @param my_grab true when it is grab also for my
* @param addition GET links (e.g., "tv_date=xxxx")
*/
function show_television($tel_id, $tel_date_start, $tel_name, $tel_desc, $tel_category, $grb_id, $req_status, $my_grab, $addition="", $show_logo, $chn_logo, $chn_name,$tv_date, $show_link, $last=false, $datetime, $hi) {
  echo "<tr";
    show_grab_class($grb_id, $req_status, false);
  echo ">\n";

  show_television_date($tel_id, $tel_date_start, $req_status, $my_grab, $grb_id, $datetime, $hi);
  if ($show_logo) {
    echo "<td valign=\"top\"><img class=\"programLogo\" alt=\"$chn_name\" title=\"$chn_name\" src=\"images/logos/$chn_logo\"/></td>";
  }

  echo "<td valign=\"top\">\n";
  echo '<span class="programName'.$hi.'"';
  echo ' onmouseover="telinfos.show('.$tel_id.'); setTimeout(\'telinfos.loadMe('.$tel_id.')\',200);"';
  echo ' onmouseout="telinfos.hide('.$tel_id.')"';
  echo ">\n";
  
  show_grab_add_link($tel_id, $tel_date_start, $tel_name, $my_grab, $addition, $hi);
  echo '</span>';
  echo '<div class="telInfo" style="margin-top: 20pt; margin-left: 30pt; '.(($last)?'right: 5pt; ':'').'display:none; position: absolute" id="telinfo_'.$tel_id.'"></div>';
  echo "\n<br />\n";

  if(!empty($tel_category)) {
    echo '<span class="programCategory'.$hi.'">'.$tel_category.'</span><br />';
  }
  echo '<span class="programDesc'.$hi.'">'.$tel_desc.'</span>';

  echo "\n<br />\n";
  if (show_grab_del_link($grb_id, $req_status, $my_grab, $addition, $hi)) {
    echo "\n<br />\n";
  }
  if ($show_link) {
    echo "<a class=\"programLink".$hi."\" href=\"tvprog.php?tv_date=".$tv_date."#".$tel_id."\">"._MsgGrabLinkShow."</a>";
  }
  echo "</td>\n</tr>\n";
}

function show_television_row($row, $addition, $highlight_strings, $use_diacritics, $show_link, $show_logo, $last=false) {
  global $DB, $usr_id;
  $grb_id = $row["grb_id"];
  $req_status = $row["req_status"];
  $my_grab = ($row["my_grab"]==$usr_id)?true:false;
  $tel_id = $row["tel_id"];
  $tel_date_start = $DB->UnixTimeStamp($row["tel_date_start"]);
  $tv_date = date("Ymd", $tel_date_start-((date("G", $tel_date_start)<_Config_midnight)?1:0)*24*3600);
  $tel_name = $row["tel_name"];
  $tel_desc = $row["tel_desc"];
  $tel_series = $row["tel_series"];
  $tel_episode = $row["tel_episode"];
  $tel_part = $row["tel_part"];
  $tel_typ = $row["tel_typ"];
  $tel_category = $row["tel_category"];
  if (!empty($tel_series) || !empty($tel_episode) || !empty($tel_part)) {
    $tel_name .= " ";
  }
  if (!empty($tel_series)) {
    $tel_name .= "S$tel_series";
  }
  if (!empty($tel_episode)) {
    $tel_name .= "E$tel_episode";
  }
  if (!empty($tel_part)) {
    $tel_name .= "P$tel_part";
  }
  if (!empty($tel_typ)) {
    switch ($tel_typ) {
      case "PREMIERE": $tel_name .= " "._MsgProgPremiere; break;
      case "LAST-CHANCE" : $tel_name .= " "._MsgProgLastChance; break;
      case "PREVIOUSLY-SHOWN" : $tel_name .= " "._MsgProgPreviouslySchown; break;
      case "NEW" : $tel_name .= " "._MsgProgNew; break;
      default : $tel_name .= " ".$tel_category; break;
    }
  }
  $tel_name = htmlspecialchars($tel_name);
  $tel_desc = htmlspecialchars($tel_desc);

  if (!empty($req_status)) {
    $hi="Hi";
  } else {
    $hi="";
  }

  if (!empty($highlight_strings)) {
    $tel_name = str_match_array_ascii($tel_name,
        $highlight_strings, '<span class="match'.$hi.'">','</span>', $use_diacritics);
  }
  if (!empty($highlight_strings)) {
    $tel_desc = str_match_array_ascii($tel_desc,
        $highlight_strings, '<span class="match'.$hi.'">','</span>', $use_diacritics);
  }
  $chn_name = $row["chn_name"];
  $chn_logo = $row["chn_logo"];

  show_television($tel_id, $tel_date_start, $tel_name, $tel_desc, $tel_category, $grb_id, $req_status, $my_grab, $addition, $show_logo, $chn_logo, $chn_name, $tv_date, $show_link, $last, (!empty($highlight_strings)), $hi);
}

/**
* Shows CSS class for the grab status.
*/
function show_grab_class($grb_id, $req_status, $my_grab) {
  if ($grb_id && !empty($req_status)) {
    echo " class=\"status-".$req_status.(($my_grab)?"-my":"")."\"";
  }
}

function show_television_date($tel_id, $tel_date_start, $req_status, $my_grab, $grb_id, $datetime, $hi="") {
  echo "<td class=\"programDate".$hi;
  if ($grb_id && !empty($req_status)) {
    echo " status-".$req_status.(($my_grab)?"-my":"");
  }
  echo "\">";
  $format = "H:i";
  if ($datetime) {
    $format = "Y-m-d H:i";
  }

  echo "<a name=\"$tel_id\"></a>".ereg_replace("^0","&nbsp;",date($format, $tel_date_start))."</td>";
}

/**
* Shows link to grab the television
* when it is not already my grab.
* @param text text to display for the link body
*/
function show_grab_add_link($tel_id, $tel_date_start, $text, $my_grab, $addition="", $hi="") {
  global $PHP_SELF;
  $grab_time_limit = time() - _Config_grab_date_stop_shift*60;
  //if (!$my_grab && $tel_date_start >= $grab_time_limit) {
  if ($tel_date_start >= $grab_time_limit) {
    echo "<a onclick=\"return confirm('"._MsgGrabConfirmStart." ".strip_tags($text)." "._MsgGrabConfirmGrab."')\" ".
      "href=\"$PHP_SELF?action=grab_add&amp;".$addition."tel_id=$tel_id\"".
      " title=\""._MsgGrabLinkGrab."\" class=\"programName".$hi."\">";
    echo $text;
    echo "</a>";
  }
  else {
    echo $text;
  }
}


/**
* Shows link to delete my grab.
* @return true whent the link was added
*/
function show_grab_del_link($grb_id, $req_status, $my_grab, $addition, $hi="") {
  $result = false;
  if ($req_status == 'scheduled' && $grb_id && $my_grab) {
    echo "<a class=\"programDel".$hi."\" href=\"$PHP_SELF?action=grab_del".
      "&amp;".$addition."grb_id=$grb_id\">"._MsgGrabLinkStorno."</a>";
    $result = true;
  }
  return $result;
}

/**
* Returns grab basename.
* Basename = filename without path and suffix.
*/
function get_grab_basename($tel_id) {
    global $DB;

    $SQL = "select ch.chn_name, t.tel_date_start, t.tel_name, t.tel_id, t.tel_series, t.tel_episode, t.tel_part
        from television t
             left join channel ch on (ch.chn_id=t.chn_id)
        where t.tel_id = $tel_id";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    if (!$row) {;
        return false;
    }
    $tel_series = $row["tel_series"];
    $tel_episode = $row["tel_episode"];
    $tel_part = $row["tel_part"];


    $channel = strtr(strtolower(strip_diacritics($row[0]))," ","_");
    $timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1]), "Ymd-Hi");
    if (_Config_grab_backend_strip_diacritics == "1") {
      $tel_name = strip_diacritics($row[2]);
    } else {
      $tel_name = $row[3];
    }
    if (!empty($tel_series) || !empty($tel_episode) || !empty($tel_part)) {
      $tel_name .= "_";
    }
    if (!empty($tel_series)) {
      $tel_name .= "S$tel_series";
    }
    if (!empty($tel_episode)) {
      $tel_name .= "E$tel_episode";
    }
    if (!empty($tel_part)) {
      $tel_name .= "P$tel_part";
    }

    $rs->Close();

    return "DVB-$timestamp-$channel-".
        ereg_replace("[/ ()?&:'´]", "_", $tel_name);
}

// vim: noexpandtab tabstop=4
?>
