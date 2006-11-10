#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");

$logdbg = &Log::singleton('file', _Config_dvbgrab_log, 'encodeLoop - debug', $logFileConf);
$logdbg->log("Starting encode_loop");

// Runs all encoders
$SQL ="select enc_id from encoder order by enc_id";
while (true) {
  $rs = do_sql($SQL);

  while($row=$rs->FetchRow()) {
    
    system("ENC_ID=".$row[0]." nice -n 10 ./encode_process.php 2>&1 >"._Config_dvbgrab_encode_log." &");
  }
  $rs->Close();
  sleep(60);
}
?>
