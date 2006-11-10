#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");

$logdbg = &Log::singleton('file', _Config_dvbgrab_log, 'clean - debug', $logFileConf);

$logdbg->log("Erasing inactive users .. start");
// not active users
$activity_limit=$DB->OffsetDate(0 - _Config_user_inactivity_limit);
$SQL = "select u.usr_name, u.usr_id from user u where usr_last_activity < $activity_limit";

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
  $SQL = "select u.usr_name, u.usr_ip from user u where usr_update >= $lastUpdate and <= $thisUpdate";
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
  $SQL = "select u.usr_name from user u where u.usr_name = '$tok'";
  $rs = do_sql($SQL);
  if ($rs->RecordCount( ) != 1) {
    unknownAccount($tok);
  }
  $tok = strtok(" \n\t");
}

$logdbg->log("Removing unknown dirs .. done");



function cleanAccount($usrName,$usrId) {
  $cmd = "rm -f "._Config_grab_root."/$usrName/*";
  do_cmd($cmd);
  $cmd = "rmdir -f "._Config_grab_root."/$usrName/*";
  do_cmd($cmd);
  $SQL = "delete from user where usr_id=$usrId";
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
?>
