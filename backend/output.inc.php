<?php

require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");
require_once("lang/lang."._Config_grab_backend_lang.".inc.php");

/**
* Returns grab basename.
* Basename = filename without path and suffix.
*/
function get_grab_basename($grb_id) {
    global $DB;

    $SQL = "select ch.chn_name, t.tel_date_start, t.tel_name, t.tel_id
        from channel ch, television t, grab g
        where ch.chn_id = t.chn_id and
            t.tel_id = g.tel_id and
            g.grb_id = $grb_id";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    if (!$row) {;
        return false;
    }

    $channel = strtolower(strip_diacritics($row[0]));
    $timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1]), "Ymd-Hi");
    if (_Config_grab_backend_strip_diacritics == "1") {
      $tel_name = strip_diacritics($row[2]);
    } else {
      $tel_name = $row[3];
    }
    $rs->Close();

    return "DVB-$timestamp-$channel-".
        ereg_replace("[/ ()?&:']", "_", $tel_name);
}

/**
 * Returns true when file exists and is not empty.
 */
function is_valid_file($filename) {
    //NOTE: is_file() and file_exists() cannot handle files bigger than 2GB
    $test = "test -s '$filename'";
    exec($test, $retval);
    return ($retval == 0);
}

/**
 * Return true when file has usable size.
 * Usable size is bigger than 1M.
 */
function is_empty_file($filename) {
    $cmd = "du '$filename'";
    $output = exec($cmd, $retval);
    return ($retval != 0 or $output < 1000000);
}

/**
 * Marks deleted grabs in database.
 */
function mark_deleted_grabs() {
    $SQL = "select g.grb_id from grab g, request r
        where req_status = 'done' and g.grb_id=r.grb_id
        order by grb_date_start";
    $rs = do_sql($SQL);
    while ($row = $rs->FetchRow()) {
        $grb_id = $row[0];
        $filename = get_grab_basename($grb_id);
        $filename = _Config_grab_storage."/$filename.mpg";
        if (is_valid_file($filename)) {
            return;
        }
        else {
            $SQL = "update request set req_status='deleted'
                where grb_id = $grb_id";
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
function publish_user_grab($grab_fullname, $grabinfo_fullname, $username, $usr_ip) {
    //NOTE: It is required that username is free of evil characters.
    // It is the resposibility of registration form.
    $grab_filename = _Config_grab_storage."/$grab_fullname";
    $usrDir = _Config_grab_root."/$username";
    if (!is_dir("$usrDir")) {
        $cmd = "mkdir -p '$usrDir'";
        do_cmd($cmd);
    }
    $user_filename = "$usrDir/$grab_fullname";
    $cmd = "ln -s '$grab_filename' '$user_filename'";
    do_cmd($cmd);
    $userinfo_filename = "$usrDir/$grabinfo_fullname";
    $cmd = "ln -s '$grabinfo_filename' '$userinfo_filename'";
    do_cmd($cmd);

    //NOTE: the .htaccess file is always overwritten,
    // this allows user to change his IP address
    $accessFile = "$userDir/.htaccess";
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
function report_success_grab($grab_id, $grab_fullname, $enc_id) {
    global $DB;

    $msg = "grab: $grab_fullname\n";
    $msg .= _MsgBackendSuccess."\n";

    $SQL = "select usr_name, usr_email, usr_ip, req_id
        from user u, request r
        where
        r.grb_id = $grab_id and
        u.enc_id = $enc_id and
        u.usr_id = r.usr_id";

    $rs = do_sql($SQL);
    while ($row = $rs->FetchRow()) {
      $user_filename = publish_user_grab($grab_fullname, $row["usr_name"], $row["usr_ip"]);

      $update = "update request set req_output='$user_filename' where req_id='".$row["req_id"]."'";
      do_sql($update);
      send_mail($row["usr_email"], _MsgBackendSuccessSub, $msg);
    }
}

/**
* Sends polite email to all requestors.
* Send the error report to the admin too.
*/
function report_grab_failure($grab_id, $grab_name) {
    global $DB;

    $msg = "grab: $grab_name\n";
    $msg .= _MsgBackendGrabError."\n";
    $SQL = "select distinct usr_email from user u, request r where
          r.grb_id=$grab_id and
          u.usr_id=r.usr_id";

    send_mail(_Config_error_email, _MsgBackendGrabErrorSub, $msg);

    $rs = do_sql($SQL);
    while ($row = $rs->FetchRow()) {
      send_mail($row[0], _MsgBackendGrabErrorSub, $msg);
    }
}

/**
* Create XML info file
*/
function create_xml_info($grab_id, $grabinfo_name, $grabfile_name) {
    $SQL = "select t.tel_name, 
              c.chn_name, 
              t.tel_date_start, 
              g.grb_date_start, 
              g.grb_date_end, 
            from grab g,television t,channel c 
            where g.tel_id=t.tel_id 
              AND t.chn_id=c.chn_id 
              AND g.grb_id=$grab_id";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    if ($fp = fopen($grabinfo_name, 'w')) {
      fwrite($fp, sprintf("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n")); 
      fwrite($fp, sprintf("<grab>\n")); 
      fwrite($fp, sprintf("  <television_name>".$row[1]."</television_name>\n")); 
      fwrite($fp, sprintf("  <channel_name>".$row[2]."</channel_name>\n")); 
      fwrite($fp, sprintf("  <file_name>".$grabfile_name."</file_name>\n")); 
      fwrite($fp, sprintf("  <file_md5>".md5_file(_Config_grab_storage/$grabfile_name)."</file_md5>\n")); 
      fwrite($fp, sprintf("  <tel_date_start_timestamp>".$DB->UnixTimeStamp($row[3])."</tel_date_start_timestamp>\n")); 
      fwrite($fp, sprintf("  <tel_date_stop_timestamp>".$DB->UnixTimeStamp($row[3])."</tel_date_start_timestamp>\n")); 
      fwrite($fp, sprintf("  <grab_date_start_timestamp>".$DB->UnixTimeStamp($row[4])."</grab_date_start_timestamp>\n")); 
      fwrite($fp, sprintf("  <grab_date_end_timestamp>".$DB->UnixTimeStamp($row[5])."</grab_date_end_timestamp>\n")); 
      fwrite($fp, sprintf("  <tel_date_start>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[3]))."</tel_date_start>\n")); 
      fwrite($fp, sprintf("  <grab_date_start>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[4]))."</grab_date_start>\n")); 
      fwrite($fp, sprintf("  <grab_date_end>".$DB->UserTimeStamp($DB->UnixTimeStamp($row[5]))."</grab_date_end>\n")); 
      fwrite($fp, sprintf("</grab>\n")); 
    }
    return $grabinfo_name;
}
    
/**
* Sends polite email to all requestors.
* Send the error report to the admin too.
*/
function report_encode_failure($grab_id, $grab_name, $enc_id) {
    global $DB;

    $msg = "grab: $grab_name\n";
    $msg .= _MsgBackendEncodeError."\n";
    $SQL = "select distinct usr_email from user u, request r where
          r.grb_id=$grab_id and
          u.enc_id=$enc_id and
          u.usr_id=r.usr_id";

    send_mail(_Config_error_email, _MsgBackendEncodeErrorSub, $msg);

    $rs = do_sql($SQL);
    while ($row = $rs->FetchRow()) {
      send_mail($row[0], _MsgBackendEncodeErrorSub, $msg);
    }
}


?>
