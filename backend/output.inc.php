<?php

require_once("config.php");
require_once("mail.php");
require_once("dblib.php");
require_once("log.inc.php");

// zruseni ceske diakritiky
//NOTE: encoding=iso-8859-2
function strip_diacritics($str) {
    return strtr($str, "áèïéìíòóø¹»úùý¾ÁÈÏÉÌÍÒÓØ©«ÚÝ®", "acdeeinorstuuyzACDEEINORSTUYZ");
};

/**
* Returns grab basename.
* Basename = filename without path and suffix.
*/
function get_grab_basename($grb_id) {
    global $DB;

    $SQL = "select ch.chn_name, g.grb_date_start, t.tel_name
        from channel ch, television t, grab g
        where ch.chn_id = t.chn_id and
            t.tel_id = g.tel_id and
            g.grb_id = $grb_id";
    $rs = db_sql($SQL);
    $row = $rs->FetchRow();
    if (!$row) {;
        return false;
    }

    $channel = strtolower(strip_diacritics($row[0]));
    $timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1]), "Ymd-Hi");
    $tel_name = $row[2];
    $rs->Close();

    return "DVB-$timestamp-$channel-".
        ereg_replace("[^a-zA-Z0-9_,!().+-]", "_", strip_diacritics($tel_name));
}

/**
 * Returns true when file exists and is not empty.
 */
function is_valid_file($filename) {
    //NOTE: standard function is_file() cannot handle files bigger than 2GB
    $test = "test -s '$filename'";
    system($test, $retval);
    return ($retval == 0);
}

/**
 * Marks deleted grabs in database.
 */
function mark_deleted_grabs() {
    global $grab_storage;

    $SQL = "select grb_id from grab
        where grb_status = 'done'
        order by grb_date_start";
    $rs = db_sql($SQL);
    while ($row = $rs->FetchRow()) {
        $grb_id = $row[0];
        $basename = get_grab_basename($grb_id);
        $filenames = glob("$grab_storage/$basename.*");
        if (count($filenames) > 0) {
            return;
        }
        else {
            $SQL = "update grab set grb_status='deleted'
                where grb_id = $grb_id";
            db_sql($SQL);
        }
    }
}

/**
 * Deletes the oldest grabs to ensures enough free space on disk storage.
 */
function ensure_free_space() {
    global $grab_storage_size;
    global $grab_storage;
    global $grab_root;
    global $max_mpg_days;

    $mtime = $max_mpg_days - 1;
    $cmd = "find '$grab_storage' '$grab_root'* '$grab_storage/../nfs' -mtime +$mtime -name 'DVB-*.mpg' -exec sh -c \"echo removeMPG: '{}' && rm -f '{}'\" \;";
    system($cmd);
    $cmd = "./remove_oldnamed.py $grab_storage_size '$grab_storage/DVB-*.*' '$grab_root*/DVB-*.*' '$grab_storage/../nfs/*/DVB-*.*'";
    system($cmd);

    mark_deleted_grabs();
}

/**
 * Makes a copy for the given user.
 * Returns path to user copy.
 */
function publish_user_grab($grab_fullname, $username, $user_ip) {
    global $grab_root;
    global $grab_storage;

    //NOTE: It is required that username is free of evil characters.
    // It is the resposibility of registration form.
    $grab_filename = "$grab_storage/$grab_fullname";
    $userDir = "$grab_root$username";
    if (!is_dir("$userDir")) {
        $command = "mkdir -p '$userDir'";
        system($command);
    }
    $user_filename = "$userDir/$grab_fullname";
    $command = "ln '$grab_filename' '$user_filename'";
    system($command);

    //NOTE: the .htaccess file is always overwritten,
    // this allows user to change his IP address
    $accessFile = "$userDir/.htaccess";
    if ($fp = fopen($accessFile, 'w')) {
        fwrite($fp, "Order deny,allow\n");
        fwrite($fp, "Deny from all\n");
        fwrite($fp, "Allow from $user_ip\n");
        fwrite($fp, "\n");
        fwrite($fp, "Options +Indexes\n");
        fwrite($fp, "IndexOptions FancyIndexing NameWidth=*\n");
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
    global $grab_storage;
    global $grab_user_url;
    global $DB;

    $SQL = "select usr_name, usr_email, usr_ip, req_id
        from user u, request r
        where
        r.grb_id = $grab_id and
        u.enc_id = $enc_id and
        u.usr_id = r.usr_id and
        r.req_output = ''
        ";

    $rs = db_sql($SQL);
    while ($row = $rs->FetchRow()) {
      $username = $row["usr_name"];
      $user_filename = publish_user_grab($grab_fullname, $username, $row["usr_ip"]);

      $update = "update request set req_output='$user_filename' where req_id='".$row["req_id"]."'";
      db_sql($update);

      //TODO: localization
      $user_url = str_replace('<USER>', $username, $grab_user_url);
      $msg = "grab: $user_url/$grab_fullname\n";
      $msg .= "je pripraven ke stazeni\n";

      send_mail($row["usr_email"], "hotovy grab", $msg);
      logDebug("reported $grab_fullname to $username");
    }
}

/**
* Sends polite email to all requestors.
* Send the error report to the admin too.
*/
function report_grab_failure($grab_id, $grab_name) {
    global $error_email;
    global $DB;

    $msg = "grab: $grab_name\n";
    $msg .= "se bohuzel neuskutecnil, kvuli problemum pri nahravani\n";
    $SQL = "select distinct usr_name, usr_email from user u, request r where
          r.grb_id=$grab_id and
          u.usr_id=r.usr_id";

    send_mail($error_email, "ERROR: neuskutecneny grab", $msg);

    $rs = db_sql($SQL);
    while ($row = $rs->FetchRow()) {
      send_mail($row[1], "neuskutecneny grab", $msg);
    }
}

?>
