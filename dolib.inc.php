<?php

require_once("config.php");
require_once("loggers.inc.php");
require_once("adodb/adodb.inc.php");
require_once("adodb/adodb-exceptions.inc.php");

// log every SQL call or SYS call to log file
$logToFile = true;


$DB = NewADOConnection(_Config_db_type);
connect_db();

if (_Config_auth_db_used == '1') {
  $AuthDB = NewADOConnection(_Config_auth_db_type);
  connect_auth_db();
}


function rappend($v, $w) {
  $v .= "$w\n";
  return $v;
}

function do_cmd($cmd) {
  global $logsys;
  global $logToFile;
  exec($cmd, $output, $rVal);
  $outputStr = array_reduce($output,"rappend");
  if ($logToFile) {
    $logsys->log("SYS:\"$cmd\" returned $rVal and \n<output>\n$outputStr\n</output>");
  }
  return $outputStr;
}

function connect_db() {
  global $DB;
  global $logsql;
  
  if ($DB->IsConnected( )) {
    // clean and reconnect
    $DB->Close();
    $DB = NewADOConnection(_Config_db_type);
  }

  while (!$DB->IsConnected( )) {
    try {
      if (!$DB->Connect(_Config_db_host, _Config_db_user, _Config_db_pass, _Config_db_name)) {
        $logsql->log("Sorry, cannot connect to database");
        sleep(300);
      }
    } catch (exception $e) {
      $logsql->log("Exception during connect to database");
      sleep(300);
    }
  }
}

function connect_auth_db() {
  global $AuthDB;
  global $logsql;

  if ($AuthDB->IsConnected( )) {
    // clean and reconnect
    $AuthDB->Close();
    $AuthDB = NewADOConnection(_Config_auth_db_type);
  }

  while (!$AuthDB->IsConnected( )) {
    try {
      if (!$AuthDB->Connect(_Config_auth_db_host, _Config_auth_db_user, _Config_auth_db_pass, _Config_auth_db_name)) {
        $logsql->log("Sorry, cannot connect to external auth database");
        sleep(300);
      }
    } catch (exception $e) {
      $logsql->log("Exception during connect to external auth database");
      sleep(300);
    }
  }
}

//-----------------------------------------------------------------
// executes a sql query
function do_sql($sql) {
  global $DB;
  global $logsql;
  global $logToFile;
  if ($logToFile) {
    $logsql->log("SQL:\"".$sql."\"");
  }

  connect_db();

  $tryCount = 0;
  while (!$rs && $tryCount < 10) {
    try {  
      $rs = $DB->Execute($sql);
      $tryCount++;

      if (!$rs) {
        handle_error("SQL: {$sql}[br]".$DB->ErrorMsg().": ".$DB->ErrorNo());
        if (!$DB->IsConnected( )) {
          connect_db();
        }
      }
    } catch (exception $e) {
      $logsql->log("Exception during SQL:\"".$sql."\"\n".$e->getMessage());
      sleep(300);
      connect_db();
    }
  }
  return $rs;
}

//-----------------------------------------------------------------
// executes a sql query
function do_extern_sql($sql) {
  global $AuthDB;
  global $logsql;
  global $logToFile;
  
  if ($logToFile) {
    $logsql->log("ExternSQL:\"".$sql."\"");
  }

  connect_auth_db();

  $tryCount = 0;
  while (!$rs && $tryCount < 10) {
    try {
      $rs = $AuthDB->Execute($sql);
      $tryCount++;

      if (!$rs) {
        handle_error("ExternSQL: {$sql}[br]".$DB->ErrorMsg().": ".$DB->ErrorNo());
        if (!$AuthDB->IsConnected( )) {
          connect_auth_db();
        }
      }
    } catch (exception $e) {
      $logsql->log("Exception during ExternSQL:\"".$sql."\"\n".$e->getMessage());
      sleep(300);
      connect_auth_db();
    }
  }
  return $rs;
}

//-----------------------------------------------------------------
// recursively parses an array and returns string
function parse_arr($arr, $lev) {
  if (is_array($arr)) {
    if (!isset($body)) {
      $body = "";
    }
    foreach($arr as $key => $val) {
      for ($i = 0; $i < $lev; $i++) {
        $body .= " -> ";
      }
      $body .= "$key = $val\n";
      if (is_array($val)) {
        $body .= parse_arr($val, $lev+1);
      }
    }
  }
  return $body;
}

//-----------------------------------------------------------------
// handle error of query
function handle_error($err) {
  switch (_Config_error_status) {
    // only print it to stdout
    case "0":
      handle_error_by_page($err);
    break;
    // compose and send error report by email to _Config_error_email
    case "1":
      handle_error_by_email($err);
    break;
    // print it to stdout and sent email
    case "2":
      handle_error_by_email($err);
      handle_error_by_page($err);
    break;
    // ignore
    default:
    break;
  }
  return true;
}

//-----------------------------------------------------------------
function handle_error_by_page($err) {
  // vypsani hlaseni do stranky
  echo "<p>\n";
  echo "DB error:<br />\n";
  echo "<pre>";
  echo ereg_replace("\[br\]", "<br />\n", $err);
  echo "</pre></p>\n";
  flush();
  return true;
}

//-----------------------------------------------------------------
function handle_error_by_email($err) {
  $body = "hi!\ndatabase error message\ndate: ".date("d.m.Y, H:i:s", time());
  $body .= "\nhttp request vars:\n";
  $body .= parse_arr($_REQUEST, 1);
  if (isset($_SESSION)) {
    $body .= "\nhttp session vars:\n";
    $body .= parse_arr($_SESSION, 1);
  }
  $body .= "\n".ereg_replace("\[br\]", "\n", $err)."\n\nmessage generated by MyDB\n\n";
  send_mail(_Config_error_email, "Database ERROR", $body);
  return true;
}

/**
 * Sends a email from grab server.
 * @param to to address
 * @param subject subject
 * @param body message body encoded by iso-8859-2
 */
function send_mail($to, $subject, $body) {
  $header = "From: "._Config_admin_email." \r\n";
  $header .= "Content-Type: text/plain; charset=UTF-8; format=flowed\r\n";
  $header .= "Content-Transfer-Encoding: 8bit\r\n";
  $header .= "Mime-Version: 1.0\r\n";
  $header .= "Reply-To: "._Config_admin_email."\r\n";

  mail($to, $subject, $body, $header,"-f"._Config_admin_email);
}


function do_xml_grab($grb_id) {
    $SQL = "select t.tel_name,
              c.chn_name,
              t.tel_date_start,
              g.grb_date_start,
              g.grb_date_end,
              g.grb_output,
            from grab g,television t,channel c
            where g.tel_id=t.tel_id
              AND t.chn_id=c.chn_id
              AND g.grb_id=$grb_id";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    if (!$row) {
      $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<grab>\n<error></error></grab>";
    } else {
      $grabfile_name = $row[6];
      $xml  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
      $xml .= "<grab>\n";
      $xml .= "  <television_name>".$row[1]."</television_name>\n";
      $xml .= "  <channel_name>".$row[2]."</channel_name>\n";
      $xml .= "  <file_name>".$grabfile_name."</file_name>\n";
      $xml .= "  <file_md5>".md5_file(_Config_grab_storage/$grabfile_name)."</file_md5>\n";
      $xml .= "  <tel_date_start_timestamp>".$DB->UnixTimeStamp($row[3])."</tel_date_start_timestamp>\n";
      $xml .= "  <grab_date_start_timestamp>".$DB->UnixTimeStamp($row[4])."</grab_date_start_timestamp>\n";
      $xml .= "  <grab_date_end_timestamp>".$DB->UnixTimeStamp($row[5])."</grab_date_end_timestamp>\n";
      $xml .= "  <tel_date_start>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[3]))."</tel_date_start>\n";
      $xml .= "  <grab_date_start>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[4]))."</grab_date_start>\n";
      $xml .= "  <grab_date_end>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[5]))."</grab_date_end>\n";
      $xml .= "</grab>\n";
    }
    return $xml;
}


?>
