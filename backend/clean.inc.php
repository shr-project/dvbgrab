<?php

function cleanAccount($usrName,$usrId) {
  $cmd = "rm -f "._Config_grab_root."/$usrName/*";
  do_cmd($cmd);
  $cmd = "rmdir -f "._Config_grab_root."/$usrName/*";
  do_cmd($cmd);
  $SQL = "delete from userinfo where usr_id=$usrId";
  do_sql($SQL);
  $SQL = "delete from userreq where usr_id=$usrId";
  do_sql($SQL);
}

function updateAccount($usrName,$usr_ip,$usr_email) {
  $usrDir = _Config_grab_root."/$usrName";
  if (!is_dir($usrDir)) {
    $cmd = "mkdir -p "._Config_grab_root."/$usrDir";
    system($cmd);
  }

  if (!file_exists("$usrDir/.htaccess")) {
    if ($fp = fopen("$usrDir/.htaccess", 'w')) {
      fwrite($fp, "Order deny,allow\n");
      fwrite($fp, "Deny from all\n");
      fwrite($fp, "Allow from $usr_ip\n");
      fwrite($fp, "\n");
      fwrite($fp, "Options +Indexes\n");
      fclose($fp);
    }
  }
  sendInfoUpdatedAccount($usr_name,$usr_ip,$usr_email);
}

function unknownAccount($usrName) {
  global $logdbg;
  // only write to log
  $logdbg->log("Unknown dir: $usrName");
}

function cleanOldDb() {
  global $DB, $logdbg;
  $logdbg->log("Cleaning television,grab,request older than ".(_Config_grab_history*3)." days");
  $limit = $DB->OffsetDate(-_Config_grab_history*3);
  $logdbg->log("Cleaning television,grab,request,userreq older than $limit");
  $SQL = "delete from userreq where req_id IN (select req_id from request where grb_id IN (select grb_id from grab where tel_id IN (select tel_id from television where tel_date_start < $limit)))";
  $n_userreq = do_sql($SQL);
  $SQL = "delete from request where grb_id IN (select grb_id from grab where tel_id IN (select tel_id from television where tel_date_start < $limit))";
  $n_request = do_sql($SQL);
  $SQL = "delete from grab where tel_id IN (select tel_id from television where tel_date_start < $limit)";
  $n_grab = do_sql($SQL);
  $SQL = "delete from television where tel_date_start < $limit";
  $n_television = do_sql($SQL);
  $logdbg->log("Removed ".$n_userreq->RecordCount()." user request, ".$n_request->RecordCount()." request, ".$n_grab->RecordCount()." grab, ".$n_television->RecordCount()." television rows");
}

function cleanSpace() {
  global $DB, $logdbg;
  $size = get_file_size(_Config_grab_storage);
  $sizeMax=_Config_grab_storage_size*1024*1024;
  $sizeMin=_Config_grab_storage_min_size*1024*1024;
  $cmdFree = "df "._Config_grab_storage." | tail -n 1 | sed 's/[^ ]* *[0123456789]* *[0123456789]* *\([0123456789]*\) *.*/\\1/g'";
  $free=do_cmd($cmdFree);
  $logdbg->log("Grab_storage size: ".$size);
  $logdbg->log("Grab_storage free: ".$free);
  $firstday = time()-(_Config_grab_history*24*3600);

  while ($size > $sizeMax || $free < $sizeMin) {
    $lastGrab = getOldestGrab();
    deleteGrab($lastGrab);
    $grb_date_start = $DB->UnixTimeStamp($lastGrab[2]);
    if ($grb_date_start > $firstday) {
      report_filesize_warning($size,$free);
      break; // don't remove more days even if sizeMax and sizeMin aren't right
    }
    $size = get_file_size(_Config_grab_storage);
    $free = do_cmd($cmdFree);
  }
  $logdbg->log("Grab_storage size: ".$size);
  $logdbg->log("Grab_storage free: ".$free);
}

function cleanTs() {
  global $logdbg;
  $cmd = "/bin/ls "._Config_grab_storage."/*.ts";
  $tsList = do_cmd($cmd);
  $tok = strtok($tsList, " \n\t");
  while ($tok !== false) {
    $ts = str_replace(".ts", "", $tok);
    $ts = str_replace(_Config_grab_storage."/", "", $ts);
    $SQL = "select grb_id, grb_name from grab where grb_name LIKE '$ts%'";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    if ($row) {
      $logdbg->log("Checking ts $ts");
      checkTs($row[0], $row[1]);
    }
    $tok = strtok(" \n\t");
  }
}

function checkTs($grb_id, $grb_name) {
  global $logdbg;
  $SQL = "select req_output,enc_codec,enc_suffix from request r,encoder e where r.enc_id=e.enc_id AND grb_id=$grb_id";
  $rs = do_sql($SQL);
  $finishedAll = true;
  while ($row = $rs->FetchRow()) {
    if (empty($row[0])) {
      $logdbg->log("Encoding with ".$row[1]." is probably not ready (file ".$grb_name.".".$row[2].")");
      $finishedAll = false;
    }
  }

  if ($finishedAll) {
    $logdbg->log("Removing not needed ".$grb_name.".ts");
    $cmd = "/bin/rm "._Config_grab_storage."/".$grb_name.".ts";
    do_cmd($cmd);
  }
}

function getOldestGrab() {
    $SQL = "select grb_id,grb_name,grb_date_start
            from grab g natural join request r
            where (r.req_status='done' or r.req_status='error')
            order by grb_date_start
            limit 1";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    if (!$row) {
      return false;
    }
    return $row;
}

function deleteGrab($grab) {
  global $logdbg;
  $grb_name = $grab[1];
  $grb_id = $grab[0];
  if (empty($grb_name)) {
    $logdbg->log("Trying to remove grab without name: ".$grb_name);
    return;
  } 
  $logdbg->log("Removing grab: ".$grb_name);
  $cmdRmGrab="rm -f "._Config_grab_storage."/$grb_name.mpg";
  do_cmd($cmdRmGrab);
  $SQL = "select distinct(req_output) from request where grb_id=$grb_id";
  $rs = do_sql($SQL);
  $cmdBrokenLinks = "find "._Config_grab_root." -type l -not -xtype f -name";
  while ($row = $rs->FetchRow()) {
    $cmd = $cmdBrokenLinks." -name $row[0]\\* -exec rm {} \;";
  }
  $rs->Close();
  $SQL = "update request set req_status='deleted' where grb_id=$grb_id";
  do_sql($SQL);
}
?>
