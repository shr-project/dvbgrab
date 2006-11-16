#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("status.inc.php");
require_once("loggers.inc.php");

$logdbg = &Log::singleton('file', _Config_dvbgrab_log, 'grabId - debug', $logFileConf);
$logerr = &Log::singleton('file', _Config_dvbgrab_log, 'grabId - error', $logFileConf);


while (true) {
  status_update();
  // vyber grab, ktery by se mel prave zacit grabovat
  $SQL ="select g.grb_id from grab g , request r
           where
             ".$DB->DBTimeStamp(time())." >= grb_date_start and g.grb_id=r.grb_id
             and req_status='scheduled' order by grb_date_start limit 1";
  $rs = do_sql($SQL);
  
  while($row=$rs->FetchRow()) {
    do_cmd("GRB_ID=".$row[0]." ./grab_process.php >/dev/null 2>&1 &");
  }
  $rs->Close();
  sleep(30);
}
?>
