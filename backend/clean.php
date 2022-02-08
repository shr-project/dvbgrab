#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");
require_once("output.inc.php");

$logdbg = &Log::singleton('file', _Config_dvbgrab_log, 'clean - debug', $logFileConf);

$logdbg->log("Erasing inactive users .. start");
// not active users
$activity_limit=$DB->OffsetDate(0 - _Config_user_inactivity_limit);
$SQL = "select usr_name, usr_id from usergrb u where usr_last_activity < $activity_limit";

$rs = do_sql($SQL);
while ($row = $rs->FetchRow()) {
  $logdbg->log("Erasing account: $row[0]");
  cleanAccount($row[0],$row[1]);
}
$logdbg->log("Erasing inactive .. done");

$logdbg->log("Updating htaccess .. start");
$SQL = "select last_account_update from params";
$rs = do_sql($SQL);
if ($row = $rs->FetchRow() && $row[0]) {
  $lastUpdate = $row[0];
  $thisUpdate = $DB->DBTimeStamp(time());
  $SQL = "update params set last_account_update=$thisUpdate";
  do_sql($SQL);
  $SQL = "select u.usr_name, u.usr_ip from usergrb u where usr_update >= $lastUpdate and <= $thisUpdate";
  $rs = do_sql($SQL);
  while ($row = $rs->FetchRow()) {
    $logdbg->log("Updating account: $row[0]");
    updateAccount($row[0],$row[1]);
  }
}
$logdbg->log("Updating htaccess .. done");

$logdbg->log("Removing unknown dirs .. start");
$cmd = "/usr/bin/ls "._Config_grab_root."/";
$dirList = do_cmd($cmd);
$tok = strtok($dirList, " \n\t");
while ($tok !== false) {
  $usrList = $tok;
  $SQL = "select u.usr_name from usergrb u where u.usr_name = '$tok'";
  $rs = do_sql($SQL);
  if ($rs->RecordCount( ) != 1) {
    unknownAccount($tok);
  }
  $tok = strtok(" \n\t");
}

$logdbg->log("Removing unknown dirs .. done");

$logdbg->log("Checking used space .. start");
cleanSpace();
$logdbg->log("Checking used space .. done");


function cleanAccount($usrName,$usrId) {
  $cmd = "rm -f "._Config_grab_root."/$usrName/*";
  do_cmd($cmd);
  $cmd = "rmdir -f "._Config_grab_root."/$usrName/*";
  do_cmd($cmd);
  $SQL = "delete from usergrb where usr_id=$usrId";
  do_sql($SQL);
  $SQL = "delete from request where usr_id=$usrId";
  do_sql($SQL);
}

function updateAccount($usrName,$usrIp) {
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
}

function unknownAccount($usrName) {
  global $logdbg;
  // only write to log
  $logdbg->log("Unknown dir: $usrName");
}

function cleanSpace() {
  global $DB, $logdbg;
  $size = get_file_size(_Config_grab_storage);
  $sizeMax=_Config_grab_storage_size*1024*1024*1024;
  $sizeMin=_Config_grab_storage_min_size*1024*1024*1024;
  $cmdFree = "df "._Config_grab_storage." | tail -n 1 | sed 's/[^ ]* *[0123456789]* *[0123456789]* *\([0123456789]*\) *.*/\\1/g'";
  $free=do_cmd($cmdFree);
  $logdbg->log("Grab_storage size: "+$size);
  $logdbg->log("Grab_storage free: "+$free);
  $firstday = time()-(_Config_grab_history*24*3600);

  while ($size > $sizeMax || $free < $sizeMin) {
    $lastGrab = getOldestGrab();
    deleteGrab($lastGrab);
    $grb_date_start = $DB->UnixTimeStamp($lastGrab[2]);
    if ($grb_date_start > $firstday) {
      writeFileSizeWarning($size,$free);
      break; // don't remove more days even if sizeMax and sizeMin aren't right
    }
    $size = get_file_size(_Config_grab_storage);
    $free = do_cmd($cmdFree);
  }
  $logdbg->log("Grab_storage size: "+$size);
  $logdbg->log("Grab_storage free: "+$free);
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
    $logdbg->log("Trying to remove grab without name: "+$grb_name);
    return;
  } 
  $logdbg->log("Removing grab: "+$grb_name);
  $cmdRmGrab="rm -f "._Config_grab_storage."/$grb_name.mpg";
  do_cmd($cmdRmGrab);
  $SQL = "select distinct(req_output) from request where grb_id=$grb_id";
  $rs = do_sql($SQL);
  $cmdBrokenLinks = "find "._Config_grab_root." -type l -not -xtype f -name";
  while ($row = $rs->FetchRow()) {
    $cmd = $cmdBrokenLinks+" -name $row[0]\\* -exec rm {} \;";
  }
  $rs->Close();
  $SQL = "update request set req_status='deleted' where grb_id=$grb_id";
  do_sql($SQL);
}

?>
