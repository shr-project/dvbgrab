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

$body = "";

/* Hint: We cache also negative results. */
define ("ErrorNoChn", -1);
define ("ErrorManyChn", -2);

$channel_cache = array();

function check_channel($channel) {
	global $channel_cache;

	if (!array_key_exists($channel, $channel_cache)) {
		$sql = "SELECT chn_id FROM channel WHERE chn_xmltv_name='$channel'";
		//echo "$sql\n";
		$rs = do_sql($sql);
		if ($rs->RecordCount() == 1) {
			$row = $rs->FetchRow();
			$channel_cache[$channel] = $row[0];
		}
		else if ($rs->RecordCount() == 0) {
			$channel_cache[$channel] = ErrorNoChn;
		}
		else {
			$channel_cache[$channel] = ErrorManyChn;
		}
	}	

	return $channel_cache[$channel];
}

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
  global $body;
  //echo $name;
  if ($name == "PROGRAMME") {
    foreach ($attrs as $k => $v) {
      switch($k) {
        case "CHANNEL": 
          if (!empty($v)) {
		    $chn_xmltv_id = $v;

			$chn_id = check_channel($chn_xmltv_id);

            if ($chn_id < 0) {
			  /* Error, invalid ID */
              if ($chn_id == ErrorNoChn) {
				  $ok = false;
				  $body .= _MsgXmlTvFormatErrorNoChn."\n";
              }
			  else {
			      /* ErrorManyChn */
				  $ok = false;
				  $body .= _MsgXmlTvFormatErrorManyChn."\n";
              }
            }
		  } else {
		    echo "BUGA: No CHANNEL tag ;-/\n";
            $ok = false;
            $body .= _MsgXmlTvFormatErrorNoneChn."\n";
          }
          break;
        case "START":
          $attr = $v;
          if ($attr) {
            $tel_date_start = parseDate($attr);
            $tel_date_start_raw = $attr;
          } else {
            $ok = false;
            $body .= _MsgXmlTvFormatErrorNoDateStart."\n";
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
  global $body;
  switch($tag_name) {
    case "EPISODE-NUM" : $tel_ep_string .= $data; break;
    case "CATEGORY"    : $tel_category .= $data; break;
    case "TITLE"       : $tel_name .= $data; break;
    case "DESC"        : $tel_desc .= $data; break;
    case "DISPLAY-NAME": /* ignore */ break;
    default            : if (!empty($data) && !preg_match('/\s*/',$data)) {$ok = false; $body .= _MsgXmlTvFormatErrorData." tag_name='$tag_name' data='$data'"."\n"; }  break;
  }
//  echo " tag_name='$tag_name' data='$data'"."\n";
}

function checkRow() {
  global $ok,$chn_id,$tel_date_start,$tel_date_start_raw,$tel_date_end,$tel_name,$tel_desc,$tel_typ,$tel_category,$tel_ep_string;
  global $body;
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
    $body .= _MsgXmlTvFormatErrorNotAll."\n";
    $body .= "chn_id: ".$chn_id."\n";
    $body .= "tel_date_start: ".$tel_date_start."\n";
    $body .= "tel_date_end: ".$tel_date_end."\n";
    $body .= "tel_name: ".$tel_name."\n";
  }
  $tel_name=stripSpaces($tel_name);
  $tel_desc=stripSpaces($tel_desc);
  $tel_typ=stripSpaces($tel_typ);
  $tel_category=stripSpaces($tel_category);
}

function insertRow() {
  global $ok,$chn_id,$chn_xmltv_id,$tel_date_start,$tel_date_end,$tel_name,$tel_desc,$tel_typ,$tel_category,$tel_series,$tel_episode,$tel_part;
  global $body;

  if (!$ok) {
    $globalOk = false;
    return;
  }

  /* We have UNIQUE KEY `idx_tel_chn` (`chn_id`,`tel_date_start`) */
  $SQL  = "REPLACE INTO television( chn_id, tel_date_start, tel_date_end, tel_name, tel_desc, tel_typ, tel_category, tel_series, tel_episode, tel_part)";
  $SQL .=                " VALUES($chn_id,$tel_date_start,$tel_date_end,$tel_name,$tel_desc,$tel_typ,$tel_category,$tel_series,$tel_episode,$tel_part)";
  do_sql($SQL);
  $body .= _MsgXmlTvInserted.": $chn_xmltv_id\n\t$tel_date_start: $tel_name\n\n";
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
  $body .= _MsgXmlTvSuccess."\n";
  send_mail(_Config_report_email, _MsgXmlTvSuccess." ".date("Ymd"), $body);
} else {
  $body .= _MsgXmlTvFailed."\n";
  send_mail(_Config_report_email, _MsgXmlTvFailed." ".date("Ymd"), $body);
}

?>
