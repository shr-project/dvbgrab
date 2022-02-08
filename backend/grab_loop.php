#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dblib.php");
require_once("status.inc.php");

function grabLog($text) {
    global $dvbgrab_log;

    if ($fp = fopen($dvbgrab_log, 'a')) {
      $msg = "INFO: ".date("Y-m-d G:i")." $text\n";
      fwrite($fp, $msg);
      fclose($fp);
    }
}

grabLog("starting grab_loop");

while (true) {
  status_update();
  // vyber grab, ktery by se mel prave zacit grabovat
  $SQL ="select grb_id from grab
           where
             ".$DB->DBTimeStamp(time()+$grab_date_start_shift*60)." >= grb_date_start and
             grb_status='scheduled'";
  $rs = db_sql($SQL);

  while($row=$rs->FetchRow()) {
    system("GRB_ID=".$row[0]." ./grabId.php >>'$dvbgrab_log' 2>&1 &");
  }
  $rs->Close();
  sleep(30);
}
?>
