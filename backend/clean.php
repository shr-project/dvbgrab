#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");
require_once("output.inc.php");
require_once("clean.inc.php");

$logdbg = &Log::singleton('console', _Config_dvbgrab_log, 'clean - debug');

$logdbg->log("Erasing inactive users .. start");
// not active users
$activity_limit=$DB->OffsetDate(0 - _Config_user_inactivity_limit);
$SQL = "select usr_name, usr_id, usr_email from userinfo u where usr_last_activity < $activity_limit";

$rs = do_sql($SQL);
while ($row = $rs->FetchRow()) {
  $logdbg->log("Erasing account: $row[0]");
  cleanAccount($row[0],$row[1]);
  sendInfoCleanAccount($row[0],$row[2]);
}
$logdbg->log("Erasing inactive .. done");

$logdbg->log("Updating htaccess .. start");
$SQL = "select par_val from param where par_key='last_account_update'";
$rs = do_sql($SQL);
if ($row = $rs->FetchRow() && $row[0]) {
  $lastUpdate = $row[0];
  $thisUpdate = $DB->DBTimeStamp(time());
  $SQL = "update param set par_val=$thisUpdate where par_key='last_account_update'";
  do_sql($SQL);
  $SQL = "select u.usr_name, u.usr_ip, u.usr_email from userinfo u where usr_update >= $lastUpdate and <= $thisUpdate";
  $rs = do_sql($SQL);
  while ($row = $rs->FetchRow()) {
    $logdbg->log("Updating account: $row[0]");
    updateAccount($row[0],$row[1], $row[2]);
  }
}
$logdbg->log("Updating htaccess .. done");

$logdbg->log("Removing unknown dirs .. start");
$cmd = "/bin/ls "._Config_grab_root."/";
$dirList = do_cmd($cmd);
$tok = strtok($dirList, " \n\t");
while ($tok !== false) {
  $usrList = $tok;
  $SQL = "select u.usr_name from userinfo u where u.usr_name = '$tok'";
  $rs = do_sql($SQL);
  if ($rs->RecordCount( ) != 1) {
    unknownAccount($tok);
  }
  $tok = strtok(" \n\t");
}

$logdbg->log("Removing unknown dirs .. done");

$logdbg->log("Cleaning unwanted ts files .. start");
cleanTs();
$logdbg->log("Cleaning unwanted ts files .. done");

$logdbg->log("Checking used space .. start");
cleanSpace();
$logdbg->log("Checking used space .. done");

$logdbg->log("Checking old data in database .. start");
cleanOldDb();
$logdbg->log("Checking old data in database .. done");
?>
