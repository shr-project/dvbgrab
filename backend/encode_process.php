#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dblib.php");
require_once("output.inc.php");
require_once("log.inc.php");


/**
* Check for no already active encoder.
* Dies when there is another process for this encoder.
*/
function checkNoActive($enc_id, $enc_pid) {
    if ($enc_pid) {
        if (posix_kill($enc_pid, 0)) {
            logInfo("encoder is already active, enc_id=$enc_id, pid=$enc_pid");
            exit;
        }
    }
}

function updatePidInfo($enc_id) {
    $pid = getmypid();
    $SQL = "update encoder set enc_pid = $pid
            where enc_id = $enc_id";
    db_sql($SQL);
    logDebug("started encoder, enc_id=$enc_id, pid=$pid");
}

function cleanPidInfo($enc_id) {
    $SQL = "update encoder set enc_pid = NULL
            where enc_id = $enc_id";
    db_sql($SQL);
}

/*
* Returns id of the oldest grab waiting for this encoder.
* Returns false when there is none.
*/
function getOldestGrab($enc_id) {
    $SQL ="select g.grb_id
        from grab g, request r, user u
        where
            r.grb_id = g.grb_id and
            r.usr_id = u.usr_id and
            u.enc_id = $enc_id and
            g.grb_status = 'done' and
            r.req_output = ''
        order by g.grb_date_start
        limit 1";
    $rs = db_sql($SQL);
    $row = $rs->FetchRow();
    $rs->Close();
    if (!$row) {
        return false;
    }
    return $row[0];
}

/**
* Encodes the oldest grab for this codec.
*/
function encodeGrab($enc_id, $enc_suffix, $enc_script) {
    global $grab_storage;

    $grab_id = getOldestGrab($enc_id);
    if (!$grab_id) {
        logDebug("nothing to encode, enc_id=$enc_id");
        return;
    }
    ensure_free_space();

    $grab_name = get_grab_basename($grab_id);
    $target_name = "$grab_name$enc_suffix";
    $target_path = "$grab_storage/$target_name";
    if (!is_valid_file($target_path)) {
        $command = "./$enc_script $grab_storage/$grab_name.mpg $target_path >/dev/null";
        logInfo("starting encoder (enc_id=$enc_id): $command");
        system("$command");
    }

    if (is_valid_file($target_path)) {
        logInfo("encoding created $target_path, enc_id=$enc_id");
        report_success_grab($grab_id, $target_name, $enc_id);
    }
    else {
        logError("encoding failed to create $target_path, enc_id=$enc_id");
    }
}

/**
* Returns row from database: enc_suffix, enc_script, enc_pid.
* Returns false when a error occurs.
*
*/
function getEncoderRow($enc_id) {
    if (empty($enc_id)) {
        return false;
    }

    $SQL = "select enc_suffix, enc_script, enc_pid
            from encoder
            where enc_id = $enc_id";

    $rs = db_sql($SQL);
    $row = $rs->FetchRow();
    $rs->Close();
    return $row;
}

/**
* Runs the encoder script to encode the oldest grab.
* Selected encoder is given in ENC_ID environment variable.
*/
function main() {
    $enc_id = getenv("ENC_ID");
    $row = getEncoderRow($enc_id);
    if (!$row) {
        logError("no such enc_id: $enc_id");
        exit(1);
    }

    $enc_suffix = $row[0];
    $enc_script = $row[1];
    $enc_pid = $row[2];

    checkNoActive($enc_id, $enc_pid);
    updatePidInfo($enc_id);
    encodeGrab($enc_id, $enc_suffix, $enc_script);
    cleanPidInfo($enc_id);
}


main();
?>
