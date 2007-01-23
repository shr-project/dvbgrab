<?php

require_once("config.php");
#require_once("dolib.inc.php");
require_once("charset.inc.php");
require_once("loggers.inc.php");
require_once("clean.inc.php");
require_once("lang/lang."._Config_grab_backend_lang.".inc.php");
require_once("print_xsl_template.php");

/**
 * Returns true when file exists and is not empty.
 */
function is_valid_file($filename) {
  //NOTE: is_file() and file_exists() cannot handle files bigger than 2GB
  $test = "test -s '$filename'";
  exec($test, $output, $retval);
  $outputStr = array_reduce($output,"rappend");
  return ($retval == 0);
}

/**
 * Return true when file has usable size.
 * Usable size is bigger than 1M.
 */
function is_empty_file($filename) {
  return (get_file_size($filename) < 10000);
}

/**
 * Returns file size
 */
function get_file_size($filename) {
  $cmd = "du ".$filename." | cut -f 1";
  exec($cmd, $output, $retval);
  $outputStr = array_reduce($output,"rappend");
  return ($retval != 0?0:$outputStr);
}

/**
 * Returns file md5
 */
function get_file_md5($filename) {
  $cmd = "md5sum ".$filename." | cut -d ' ' -f 1";
  exec($cmd, $output, $retval);
  $outputStr = array_reduce($output,"rappend");
  $outputStr = str_replace("\n","",$outputStr);
  return ($retval != 0?0:$outputStr);
}


/**
 * Marks deleted grabs in database.
 */
function mark_deleted_grabs() {
  $SQL = "select r.req_output,r.req_id 
          from grab g 
               left join request r using (grb_id)
          where req_status = 'done'
          order by grb_date_start";
  $rs = do_sql($SQL);
  while ($row = $rs->FetchRow()) {
    $filename = _Config_grab_storage."/".$row[0];
    if (is_valid_file($filename)) {
      return;
    }
    else {
      $SQL = "update request set req_status='deleted'
              where req_id = ".$row[1];
      do_sql($SQL);
    }
  }
}

/**
 * Deletes the oldest grabs to ensures enough free space on disk storage.
 */
function ensure_free_space() {
  cleanSpace();
  mark_deleted_grabs();
}

/**
 * Makes a copy for the given user.
 * Returns path to user copy.
 */
function publish_user_grab($target_name, $target_name_xml, $usr_name, $usr_ip) {
  //NOTE: It is required that usr_name is free of evil characters.
  // It is the resposibility of registration form.
  $target_path = _Config_grab_storage."/$target_name";
  $target_path_xml = _Config_grab_storage."/$target_name_xml";
  $usr_dir = _Config_grab_root."/$usr_name";
  if (!is_dir("$usr_dir")) {
    $cmd = "mkdir -p '$usr_dir'";
    do_cmd($cmd);
  }
  $usr_path = "$usr_dir/$target_name";
  $usr_path_xml = "$usr_dir/$target_name_xml";
  $cmd = "ln -s $target_path $usr_path";
  do_cmd($cmd);
  $cmd = "ln -s $target_path_xml $usr_path_xml";
  do_cmd($cmd);

  //NOTE: the .htaccess file is always overwritten,
  // this allows usr to change his IP address
  $accessFile = "$usr_dir/.htaccess";
  if ($fp = fopen($accessFile, 'w')) {
    fwrite($fp, "Order deny,allow\n");
    fwrite($fp, "Deny from all\n");
    fwrite($fp, "Allow from $usr_ip\n");
    fwrite($fp, "\n");
    fwrite($fp, "Options +Indexes\n");
    fclose($fp);
  }

  return $usr_path;
}


/**
* Provides the grab to all requestors.
* Makes hard link to user directory and sends them email.
* Also updates column request.req_output in database.
*/
function report_grab_success($req_id, $target_name, $target_name_xml) {
  global $DB,$logdbg;
  $logdbg->log("Reporting success of $target_name and $target_name_xml");
  $update = "update request set req_status='done' where req_id=$req_id";
  do_sql($update);
  $logdbg->log("Updated request to status done");

  $SQL = "select usr_name, usr_email, usr_ip, urq_id, usr_lang
          from request r
               left join userreq ur using (req_id)
               left join userinfo u using (usr_id)
          where r.req_id = $req_id";

  $rs = do_sql($SQL);
  while ($row = $rs->FetchRow()) {
    $usr_name = $row[0];
    $usr_email = $row[1];
    $usr_ip = $row[2];
    $urq_id = $row[3];
    $usr_lang = $row[4];

    if (empty($urq_id) || empty($usr_email)) {
      $logdbg->log("Dokoncen uz nechteny request\nreq_id=$req_id\ntarget_name=$target_name\ntarget_name_xml=$target_name_xml\nusr_name=$usr_name\nusr_email=$usr_email\nusr_ip=$usr_ip\nurq_id=$urq_id\nusr_lang=$usr_lang");
      continue;
    }
    
    $usr_target = publish_user_grab($target_name, $target_name_xml, $usr_name, $usr_ip);
    $usr_target_url = _Config_hostname."/$usr_name/$target_name";
    $usr_target_url_xml = _Config_hostname."/$usr_name/$target_name_xml";
    
    $update = "update userreq set urq_output='$usr_target_url' where urq_id=".$urq_id;
    do_sql($update);

    if (empty($usr_lang)) {
      $usr_lang = _Config_grab_backend_lang;
    }
    require_once("lang/lang.$usr_lang.inc.php");
    print_xsl_template(_Config_grab_root."/$usr_name/dvbgrab.xsl");
    
    $msg = "grab: $target_name\n";
    $msg .= _MsgBackendSuccess."\n";
    $msg .= $usr_target_url."\n";
    $msg .= $usr_target_url_xml."\n";
  

    send_mail($usr_email, _MsgBackendSuccessSub, $msg);
  }
}

function sendInfoCleanAccount($usr_name,$usr_email, $usr_lang) {
  global $DB;
  require_once("lang/lang.$usr_lang.inc.php");

  $msg = "user: $usr_name\n";
  $msg .= _MsgBackendAccountCleaned." "._Config_user_inactivity_limit."\n";
  send_mail($usr_email, _MsgBackendAccountCleanedSub, $msg);
}

function sendInfoUpdatedAccount($usr_name,$usr_ip,$usr_email, $usr_lang) {
  global $DB;
  require_once("lang/lang.$usr_lang.inc.php");

  $msg = "user: $usr_name\n";
  $msg .= _MsgBackendAccountUpdated." ip=$usr_ip\n";
  send_mail($usr_email, _MsgBackendAccountUpdatedSub, $msg);
}



/**
* Sends polite email to all requestors.
* Send the error report to the admin too.
*/
function report_grab_failure($grb_id, $grb_name) {
  global $DB;
  require_once("lang/lang."._Config_grab_backend_lang.".inc.php");

  $msg = "grab: $grb_name\n";
  $msg .= _MsgBackendGrabError."\n";
  $SQL = "select distinct usr_email, usr_lang
          from request r
               left join userreq ur using (req_id)
               left join userinfo u using (usr_id)
          where r.grb_id = $grb_id";

  send_mail(_Config_error_email, _MsgBackendGrabErrorSub, $msg);

  $rs = do_sql($SQL);
  while ($row = $rs->FetchRow()) {
    $usr_lang = $row[1];
    if (empty($usr_lang)) {
      $usr_lang = _Config_grab_backend_lang;
    }
    require_once("lang/lang.$usr_lang.inc.php");
    $msg = "grab: $grb_name\n";
    $msg .= _MsgBackendGrabError."\n";
 
    send_mail($row[0], _MsgBackendGrabErrorSub, $msg);
  }
}

/**
* Sends polite email to all requestors.
* Send the error report to the admin too.
*/
function report_encode_failure($req_id, $grb_name) {
  global $DB;
  require_once("lang/lang."._Config_grab_backend_lang.".inc.php");
  
  $msg = "grab: $grb_name\n";
  $msg .= _MsgBackendEncodeError."\n";
  $SQL = "select distinct usr_email, usr_lang
          from request r
               left join userreq ur using (req_id)
               left join userinfo u using (usr_id)
          where r.req_id = $req_id";

  send_mail(_Config_error_email, _MsgBackendEncodeErrorSub, $msg);

  $rs = do_sql($SQL);
  while ($row = $rs->FetchRow()) {
    $usr_lang = $row[1];
    if (empty($usr_lang)) {
      $usr_lang = _Config_grab_backend_lang;
    }
    require_once("lang/lang.$usr_lang.inc.php");
    $msg = "grab: $grb_name\n";
    $msg .= _MsgBackendEncodeError."\n";
    send_mail($row[0], _MsgBackendEncodeErrorSub, $msg);
  }
}

/**
* Create XML info file
*/
function create_xml_info($req_id, $target_path_xml) {
  global $DB;
  $SQL = "select enc_codec, 
            req_output, 
            req_output_size, 
            req_output_md5, 
            req_status,
            t.tel_name, 
            t.tel_series,
            t.tel_episode,
            t.tel_part,
            c.chn_name,
            g.grb_name,
            t.tel_date_start,
            t.tel_date_end,
            g.grb_date_start,
            g.grb_date_end
          from request r
               left join encoder e using (enc_id)
               left join grab g using (grb_id)
               left join television t using (tel_id)
               left join channel c using (chn_id)
          where r.req_id=$req_id";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  if ($fp = fopen($target_path_xml, 'w')) {
    $filename = $row[1];
    if (!empty($filename)) {
      $pos = strrpos($filename, "/");
      if ($pos !== false) {
        $filename = substr($filename,$pos);
      }
    }
    fwrite($fp, sprintf("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n")); 
    fwrite($fp, sprintf("<?xml-stylesheet type=\"text/xsl\" href=\"dvbgrab.xsl\"?>\n"));
    fwrite($fp, sprintf("<grab>\n")); 
    fwrite($fp, sprintf("  <channel_name>".$row[9]."</channel_name>\n")); 
    fwrite($fp, sprintf("  <tel_name>".$row[5]."</tel_name>\n")); 
    fwrite($fp, sprintf("  <tel_series>".$row[6]."</tel_series>\n")); 
    fwrite($fp, sprintf("  <tel_episode>".$row[7]."</tel_episode>\n")); 
    fwrite($fp, sprintf("  <tel_part>".$row[8]."</tel_part>\n")); 
    fwrite($fp, sprintf("  <tel_date_start_timestamp>".$DB->UnixTimeStamp($row[11])."</tel_date_start_timestamp>\n")); 
    fwrite($fp, sprintf("  <tel_date_end_timestamp>".$DB->UnixTimeStamp($row[12])."</tel_date_end_timestamp>\n")); 
    fwrite($fp, sprintf("  <tel_date_start>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[11]))."</tel_date_start>\n")); 
    fwrite($fp, sprintf("  <tel_date_end>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[12]))."</tel_date_end>\n")); 
    fwrite($fp, sprintf("  <grb_name>".$row[10]."</grb_name>\n")); 
    fwrite($fp, sprintf("  <grb_date_start_timestamp>".$DB->UnixTimeStamp($row[13])."</grb_date_start_timestamp>\n")); 
    fwrite($fp, sprintf("  <grb_date_end_timestamp>".$DB->UnixTimeStamp($row[14])."</grb_date_end_timestamp>\n")); 
    fwrite($fp, sprintf("  <grb_date_start>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[13]))."</grb_date_start>\n")); 
    fwrite($fp, sprintf("  <grb_date_end>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[14]))."</grb_date_end>\n")); 
    fwrite($fp, sprintf("  <enc_codec>".$row[0]."</enc_codec>\n")); 
    fwrite($fp, sprintf("  <req_output>".$filename."</req_output>\n")); 
    fwrite($fp, sprintf("  <req_output_size>".$row[2]."</req_output_size>\n")); 
    fwrite($fp, sprintf("  <req_output_md5>".$row[3]."</req_output_md5>\n")); 
    fwrite($fp, sprintf("  <req_status>".$row[4]."</req_status>\n")); 
    fwrite($fp, sprintf("</grab>\n")); 
    fclose($fp);
  }
}

function report_filesize_warning($size,$free) {
  require_once("lang/lang."._Config_grab_backend_lang.".inc.php");

  global $DB;

  $msg = _MsgBackendFilesizeWarningSize."\n";
  $msg = "grab storage size: ".($size/(1024))." MB\n";
  $msg = "grab storage free: ".($free/(1024))." MB\n";

  send_mail(_Config_error_email, _MsgBackendFilesizeWarningSizeSub, $msg);
}
?>
