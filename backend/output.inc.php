<?php

require_once("config.php");
#require_once("dolib.inc.php");
require_once("charset.inc.php");
require_once("loggers.inc.php");
require_once("lang/lang."._Config_grab_backend_lang.".inc.php");

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
                 left join request r on (r.grb_id=g.grb_id)
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
#    $cmd = "./remove_oldnamed.py _Config_grab_storage_size '_Config_grab_storage/DVB-*.mpg' '_Config_grab_storage/DVB-*.avi' '_Config_grab_root/*/DVB-*.mpg' '_Config_grab_root/*/DVB-*.avi'";
#    system($cmd);

    mark_deleted_grabs();
}

/**
 * Makes a copy for the given user.
 * Returns path to user copy.
 */
function publish_user_grab($grb_fullname, $grbinfo_fullname, $username, $usr_ip) {
    //NOTE: It is required that username is free of evil characters.
    // It is the resposibility of registration form.
    $grb_filename = _Config_grab_storage."/$grb_fullname";
    $grbinfo_filename = _Config_grab_storage."/$grbinfo_fullname";
    $usrDir = _Config_grab_root."/$username";
    if (!is_dir("$usrDir")) {
        $cmd = "mkdir -p '$usrDir'";
        do_cmd($cmd);
    }
    $user_filename = "$usrDir/$grb_fullname";
    $cmd = "ln -s $grb_filename $user_filename";
    do_cmd($cmd);
    $userinfo_filename = "$usrDir/$grbinfo_fullname";
    $cmd = "ln -s $grbinfo_filename $userinfo_filename";
    do_cmd($cmd);

    //NOTE: the .htaccess file is always overwritten,
    // this allows user to change his IP address
    $accessFile = "$usrDir/.htaccess";
    if ($fp = fopen($accessFile, 'w')) {
        fwrite($fp, "Order deny,allow\n");
        fwrite($fp, "Deny from all\n");
        fwrite($fp, "Allow from $usr_ip\n");
        fwrite($fp, "\n");
        fwrite($fp, "Options +Indexes\n");
        fclose($fp);
    }

    return $user_filename;
}


/**
* Provides the grab to all requestors.
* Makes hard link to user directory and sends them email.
* Also updates column request.req_output in database.
*/
function report_grab_success($grb_id, $grb_fullname, $grbinfo_fullname, $enc_id) {
    global $DB;

    $msg = "grab: $grb_fullname\n";
    $msg .= _MsgBackendSuccess."\n";

    $update = "update request set req_output='$grb_fullname' where grb_id=$grb_id and enc_id=$enc_id";
    do_sql($update);
    $SQL = "update request set req_status='done' where grb_id=$grb_id and enc_id=$enc_id";
    do_sql($SQL);

    $SQL = "select usr_name, usr_email, usr_ip, urq_id, req_id
        from request r
             left join userreq ur on (ur.req_id=r.req_id)
             left join userinfo u on (u.usr_id=ur.req_id)
        where
        r.grb_id = $grb_id and
        r.enc_id = $enc_id";

    $rs = do_sql($SQL);
    while ($row = $rs->FetchRow()) {
      $user_filename = publish_user_grab($grb_fullname, $grbinfo_fullname, $row["usr_name"], $row["usr_ip"]);
      $user_file_url = "http://"._Config_hostname."$row[0]/$grb_fullname";
      $user_fileinfo_url = "http://"._Config_hostname."$row[0]/$grbinfo_fullname";

      $update = "update userreq set urq_output='$user_file_url' where urq_id=".$row["urq_id"];
      do_sql($update);
      send_mail($row["usr_email"], _MsgBackendSuccessSub, $msg."\n$user_fileinfo_url\n$user_file_url");
    }
}

function sendInfoCleanAccount($usr_name,$usr_email) {
   global $DB;

    $msg = "user: $usr_name\n";
    $msg .= _MsgBackendAccountCleaned." "._Config_user_inactivity_limit."\n";
    send_mail($usr_email, _MsgBackendAccountCleanedSub, $msg);
}

function sendInfoUpdatedAccount($usr_name,$usr_ip,$usr_email) {
   global $DB;

    $msg = "user: $usr_name\n";
    $msg .= _MsgBackendAccountUpdated." $usr_ip\n";
    send_mail($usr_email, _MsgBackendAccountUpdatedSub, $msg);
}



/**
* Sends polite email to all requestors.
* Send the error report to the admin too.
*/
function report_grab_failure($grb_id, $grb_name) {
    global $DB;

    $msg = "grab: $grb_name\n";
    $msg .= _MsgBackendGrabError."\n";
    $SQL = "select distinct usr_email
              from request r
                   left join userreq ur on (ur.req_id=r.req_id)
                   left join userinfo u on (u.usr_id=ur.req_id)
              where r.grb_id = $grb_id";

    send_mail(_Config_error_email, _MsgBackendGrabErrorSub, $msg);

    $rs = do_sql($SQL);
    while ($row = $rs->FetchRow()) {
      send_mail($row[0], _MsgBackendGrabErrorSub, $msg);
    }
}

/**
* Create XML info file
*/
function create_xml_info($grb_id, $enc_id, $grbinfo_name) {
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
                 left join encoder e on (e.enc_id=r.enc_id)
                 left join grab g on (g.grb_id=r.grb_id)
                 left join television t on (t.tel_id=g.tel_id)
                 left join channel c on (c.chn_id=t.chn_id)
            where AND g.grb_id=$grb_id
                  AND r.enc_id=$enc_id";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    if ($fp = fopen(_Config_grab_storage."/".$grbinfo_name, 'w')) {
      $filename = $row[1];
      if (!empty($filename)) {
        $pos = strrpos($filename, "/");
        if ($pos !== false) {
          $filename = substr($filename,$pos);
        }
      }
      fwrite($fp, sprintf("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n")); 
      fwrite($fp, sprintf("<?xml-stylesheet type=\"text/xsl\" href=\"../dvbgrab.xsl\"?>\n"));
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
    return $grbinfo_name;
}
/**
* Sends polite email to all requestors.
* Send the error report to the admin too.
*/
function report_encode_failure($grb_id, $grb_name, $enc_id) {
    global $DB;

    $msg = "grab: $grb_name\n";
    $msg .= _MsgBackendEncodeError."\n";
    $SQL = "select distinct usr_email
              from request r
                   left join userreq ur on (ur.req_id=r.req_id)
                   left join userinfo u on (u.usr_id=ur.req_id)
              where r.grb_id = $grb_id and r.enc_id=$enc_id";

    send_mail(_Config_error_email, _MsgBackendEncodeErrorSub, $msg);

    $rs = do_sql($SQL);
    while ($row = $rs->FetchRow()) {
      send_mail($row[0], _MsgBackendEncodeErrorSub, $msg);
    }
}

function report_filesize_warning($size,$free) {
    global $DB;

    $msg = _MsgBackendFilesizeWarningSize."\n";
    $msg = "grab storage size: ".($size/(1024))." MB\n";
    $msg = "grab storage free: ".($free/(1024))." MB\n";

    send_mail(_Config_error_email, _MsgBackendFilesizeWarningSizeSub, $msg);
}
?>
