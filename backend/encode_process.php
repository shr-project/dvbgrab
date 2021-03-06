#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("output.inc.php");
require_once("loggers.inc.php");

/**
* Check for no already active encoder.
* Dies when there is another process for this encoder.
*/
function checkNoActive($enc_id, $enc_pid) {
  global $logdbg;
  if ($enc_pid) {
    if (posix_kill($enc_pid, 0)) {
      $logdbg->log("encoder is already active, enc_id=$enc_id, pid=$enc_pid");
      exit;
    }
  }
}

function updatePidInfo($enc_id) {
  global $logdbg;
  $pid = getmypid();
  $SQL = "update encoder set enc_pid = $pid
          where enc_id = $enc_id";
  do_sql($SQL);
  $logdbg->log("started encoder, enc_id=$enc_id, pid=$pid");
}

function cleanPidInfo($enc_id) {
  $SQL = "update encoder set enc_pid = NULL
          where enc_id = $enc_id";
  do_sql($SQL);
}

/*
* Returns id of the oldest grab waiting for this encoder.
* Returns false when there is none.
*/
function getOldestRequest($enc_id) {
  $SQL ="select r.req_id
    from request r left join grab g using (grb_id)
    where
      r.enc_id = $enc_id and
      r.req_status = 'saved'
    order by g.grb_date_start
    limit 1";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  $rs->Close();
  if (!$row) {
    return false;
  }
  return $row[0];
}

/*
* Returns filename of saved grb_id and checks if exists
*/
function getGrabName($req_id) {
  $SQL ="select g.grb_name
    from grab g left join request r using (grb_id)
    where
      r.req_id = $req_id";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  $rs->Close();
  if (!$row[0]) {
    return false;
  }
  $grb_file = _Config_grab_storage."/".$row[0].".ts";
  if (is_empty_file($grb_file)) {
    return false;
  }
  return $row[0];
}

/**
* Encodes the oldest grab for this codec.
*/
function encodeGrab($enc_id, $enc_suffix, $enc_script) {
  global $logdbg;
  global $logerr;

  $req_id = getOldestRequest($enc_id);
  if (!$req_id) {
    $logdbg->log("nothing to encode, enc_id=$enc_id");
    return;
  }
  ensure_free_space();
  $grb_name = getGrabName($req_id);
  if (!$grb_name) {
    $logerr->log("encoding failed to find saved grab $grb_name, enc_id=$enc_id");
    $target_name = "$grb_name.$enc_suffix";
    report_encode_failure($req_id, $target_name);
    return;
  }

  $SQL = "update request set req_status='encoding' where req_id=$req_id";
  do_sql($SQL);

  $target_name = "$grb_name.$enc_suffix";
  $target_path = _Config_grab_storage."/$target_name";
  $target_name_xml = $target_name.".xml";
  $target_path_xml = $target_path.".xml";
  $cmd = "encoders/$enc_script "._Config_grab_storage."/$grb_name.ts $target_path >/dev/null 2>&1";
  $logdbg->log("starting encoder (enc_id=$enc_id): $cmd");
  $logdbg->log("starting $target_path");
  do_cmd($cmd);
  $logdbg->log("finished encoder $target_path");
  $logdbg->log("size: ".get_file_size($target_path));

  if (!is_empty_file($target_path)) {
    $logdbg->log("encoding created $target_path, enc_id=$enc_id");
    $req_output_size = get_file_size($target_path);
    $req_output_md5 = get_file_md5($target_path);
    $SQL = "update request set req_status='encoded', req_output='$target_name', req_output_size=$req_output_size, req_output_md5='$req_output_md5' where req_id=$req_id";
    do_sql($SQL);
    create_xml_info($req_id,$target_path_xml);
    report_grab_success($req_id, $target_name, $target_name_xml);
  }
  else {
    $SQL = "update request set req_status='error' where req_id=$req_id";
    do_sql($SQL);
    $logerr->log("encoding failed to create $target_path, enc_id=$enc_id");
    report_encode_failure($req_id, $target_name);
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

  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  $rs->Close();
  return $row;
}

/**
* Runs the encoder script to encode the oldest grab.
* Selected encoder is given in ENC_ID environment variable.
*/
function main() {
  global $logerr;
  $enc_id = getenv("ENC_ID");
  $row = getEncoderRow($enc_id);
  if (!$row) {
    $logerr->log("no such enc_id: $enc_id");
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
