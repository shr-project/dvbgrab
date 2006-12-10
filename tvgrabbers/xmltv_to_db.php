#!/usr/bin/php -q
<?php
require_once "../dolib.inc.php";
require_once "../config.php";
require_once "func.inc.php";
require_once("../lang/lang."._Config_grab_backend_lang.".inc.php");


$ok = true;
$globalOk = true;
$chn_id = "";
$tel_date_start = "";
$tel_date_end = "";
$tel_name = "";
$tel_desc = "";
$tel_typ = "";
$tel_category = "";
$tel_series = "";
$tel_episode = "";
$tel_part = "";

$tag_name = "";
$tel_ep_string = "";
$tel_date_start_raw = "";

function parseDate($date, $offset=0) {
  global $DB;
//  echo "$date => ".$DB->DBTimeStamp(strtotime($date)+$offset)."\n";
  return $DB->DBTimeStamp(strtotime($date)+$offset);
}
function parseEpString($ep) {
  global $tel_series,$tel_episode,$tel_part;
  if (! strpos($ep, ".")) {
    $tel_episode=$ep;
  } else {
    $tok = strtok($ep, ".");
    if (empty($tok)) {
      $tel_series = "";
    } else if (! strpos($tok, "/")) {
      $tel_series = $tok+1;
    } else {
      $tokk = strtok($tok, "/");
      $tel_series = "".$tokk+1;
      $tokk = strtok("/");
      $tel_series .= "/".$tokk;
    }
    $tok = strtok(".");
    if (empty($tok)) {
      $tel_episode = "";
    } else if (! strpos($tok, "/")) {
      $tel_episode = $tok+1;
    } else {
      $tokk = strtok($tok, "/");
      $tel_episode = "".$tokk+1;
      $tokk = strtok("/");
      $tel_episode .= "/".$tokk;
    }
    $tok = strtok(".");
    if (empty($tok)) {
      $tel_part = "";
    } else if (! strpos($tok, "/")) {
      $tel_part = $tok+1;
    } else {
      $tokk = strtok($tok, "/");
      $tel_part = "".$tokk+1;
      $tokk = strtok("/");
      $tel_part .= "/".$tokk;
    }
  }
  $tel_series = "'".ereg_replace("[[:space:]]*","",$tel_series)."'";
  $tel_episode = "'".ereg_replace("[[:space:]]*","",$tel_episode)."'";
  $tel_part = "'".ereg_replace("[[:space:]]*","",$tel_part)."'";
}

function stripSpaces($string) {
  $string = ereg_replace("^[[:space:]]*","",$string);
  $string = ereg_replace("[[:space:]]*$","",$string);
  $string = ereg_replace("[[:space:]]+"," ",$string);
  $string = readySql_fp($string);
  return $string;
}
function startElement($parser, $name, $attrs) 
{
  global $DB, $chn_id, $chn_xmltv_id, $tel_date_start, $tel_date_end, $tel_date_start_raw, $ok, $tag_name, $tel_typ;
  //echo $name;
  if ($name == "PROGRAMME") {
    foreach ($attrs as $k => $v) {
      switch($k) {
        case "CHANNEL": 
          $attr = $v;
          if (!empty($attr)) {
            $chn_xmltv_id=$attr;
            $rs=do_sql("SELECT chn_id FROM channel WHERE chn_xmltv_name='$attr'");
            if ($rs->RecordCount() == 1) {
              $row=$rs->FetchRow();
              $chn_id = $row[0];
            } else if ($rs->RecordCount() == 0) {
              $ok = false;
              echo _MsgXmlTvFormatErrorNoChn."\n";
            } else {
              $ok = false;
              echo _MsgXmlTvFormatErrorManyChn."\n";
            }
          } else {
            $ok = false;
            echo _MsgXmlTvFormatErrorNoneChn."\n";
          }
          break;
        case "START":
          $attr = $v;
          if ($attr) {
            $tel_date_start = parseDate($attr);
            $tel_date_start_raw = $attr;
          } else {
            $ok = false;
            echo _MsgXmlTvFormatErrorNoDateStart."\n";
          }
          break;
        case "STOP":
          $attr = $v;
          if ($attr) {
            $tel_date_end = parseDate($attr);
          }
          break;
      }
    }
  } else if ($name == "EPISODE-NUM" || $name == "CATEGORY" || $name == "TITLE" || $name == "DESC" || $name == "DISPLAY-NAME") {
    $tag_name = $name;
  } else if ($name == "PREVIOUSLY-SHOWN" || $name == "PREMIERE" || $name == "LAST-CHANCE" || $name == "NEW") { 
    $tel_typ = $name;
  } 
}

function endElement($parser, $name) {
  if ($name == "PROGRAMME") {
    checkRow();
    insertRow();
    cleanRow();
  } else if ($name = "EPISODE-NUM" || $name = "CATEGORY" || $name = "TITLE" || $name = "DESC" || $name == "DISPLAY-NAME") {
    $tag_name = "";
  }
}

function characterData($parser, $data) {
  global $tag_name,$tel_ep_string,$tel_category,$tel_name,$tel_desc,$ok;
  switch($tag_name) {
    case "EPISODE-NUM" : $tel_ep_string .= $data; break;
    case "CATEGORY"    : $tel_category .= $data; break;
    case "TITLE"       : $tel_name .= $data; break;
    case "DESC"        : $tel_desc .= $data; break;
    case "DISPLAY-NAME": /* ignore */ break;
    default            : if (!empty($data) && !preg_match('/\s*/',$data)) {$ok = false; echo _MsgXmlTvFormatErrorData." tag_name='$tag_name' data='$data'"."\n"; }  break;
  }
//  echo " tag_name='$tag_name' data='$data'"."\n";
}

function checkRow() {
  global $ok,$chn_id,$tel_date_start,$tel_date_start_raw,$tel_date_end,$tel_name,$tel_desc,$tel_typ,$tel_category,$tel_ep_string;
  if (!$ok) {
    return;
  }
  if (empty($tel_date_end)) {
    $tel_date_end = parseDate($tel_date_start_raw,_Config_record_time_after_last);
  }
  parseEpString($tel_ep_string);
  $tel_ep_string = ereg_replace("[[:space:]]+", " ", $tel_ep_string);
  if (empty($chn_id) || empty($tel_date_start) || empty($tel_date_end) || empty($tel_name)) {
    $ok=false;
    echo _MsgXmlTvFormatErrorNotAll."\n";
    echo "chn_id: ".$chn_id."\n";
    echo "tel_date_start: ".$tel_date_start."\n";
    echo "tel_date_end: ".$tel_date_end."\n";
    echo "tel_name: ".$tel_name."\n";
  }
  $tel_name=stripSpaces($tel_name);
  $tel_desc=stripSpaces($tel_desc);
  $tel_typ=stripSpaces($tel_typ);
  $tel_category=stripSpaces($tel_category);
}

function insertRow() {
  global $ok,$chn_id,$chn_xmltv_id,$tel_date_start,$tel_date_end,$tel_name,$tel_desc,$tel_typ,$tel_category,$tel_series,$tel_episode,$tel_part;
  if (!$ok) {
    $globalOk = false;
    return;
  }

  $SQL = "SELECT * FROM television WHERE chn_id=$chn_id and tel_date_start=$tel_date_start and tel_name=$tel_name";
  $rs = do_sql($SQL);
  if ($rs->RecordCount() != 0) {
    echo _MsgXmlTvIgnored.": $chn_xmltv_id\n\t$tel_date_start: $tel_name\n\n";
    return; // Ignore if programm in this time exists with the same name
  } 

  $SQL = "SELECT * FROM television WHERE chn_id=$chn_id and tel_date_start=$tel_date_start";
  $rs = do_sql($SQL);
  if ($rs->RecordCount() != 0) {
    echo _MsgXmlTvUpdated.": $chn_xmltv_id\n\t$tel_date_start: $tel_name\n\n";
    $SQL = "UPDATE television SET tel_date_end=$tel_date_end, tel_name = $tel_name, tel_desc=$tel_desc, tel_typ = $tel_typ, tel_category=$tel_category,tel_series=$tel_series,tel_episode=$tel_episode,tel_part=$tel_part WHERE chn_id=$chn_id and tel_date_start=$tel_date_start";
    do_sql($SQL);
    return; // Update programm in this time exists programme with different name
  }

  $SQL  = "INSERT INTO television( chn_id, tel_date_start, tel_date_end, tel_name, tel_desc, tel_typ, tel_category, tel_series, tel_episode, tel_part)";
  $SQL .=                " VALUES($chn_id,$tel_date_start,$tel_date_end,$tel_name,$tel_desc,$tel_typ,$tel_category,$tel_series,$tel_episode,$tel_part)";
  do_sql($SQL);
  echo _MsgXmlTvInserted.": $chn_xmltv_id\n\t$tel_date_start: $tel_name\n\n";
// echo $SQL."\n\n";
}

function cleanRow() {
  global $ok,$chn_id,$tel_date_start,$tel_date_end,$tel_name,$tel_desc,$tel_typ,$tel_category,$tel_series,$tel_episode,$tel_part,$tag_name,$tel_ep_string,$tel_date_start_raw;
  $ok = true;
  $chn_id = "";
  $tel_date_start = "";
  $tel_date_end = "";
  $tel_name = "";
  $tel_desc = "";
  $tel_typ = "";
  $tel_category = "";
  $tel_series = "";
  $tel_episode = "";
  $tel_part = "";

  $tag_name = "";
  $tel_ep_string = "";
  $tel_date_start_raw = "";
}

$xml_parser = xml_parser_create();
xml_set_character_data_handler($xml_parser, "characterData");
xml_set_element_handler($xml_parser, "startElement", "endElement");

//$file = "tv_grab_cz/test.xml";
$file="php://stdin";

if (!($fp = fopen($file, "r"))) {
   die("could not open XML input");
}

while ($data = fread($fp, 4096)) {
   if (!xml_parse($xml_parser, $data, feof($fp))) {
     die(sprintf("XML error: %s at line %d",
                 xml_error_string(xml_get_error_code($xml_parser)),
                 xml_get_current_line_number($xml_parser)));
   }
}
xml_parser_free($xml_parser);
if ($globalOk) {
  echo _MsgXmlTvSuccess."\n";
} else {
  echo _MsgXmlTvFailed."\n";
}
?>
