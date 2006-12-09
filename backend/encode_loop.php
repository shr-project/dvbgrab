#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");

$logdbg->log("Starting encode_loop");
// Runs all encoders
$SQL ="select enc_id from encoder order by enc_id";
while (true) {
  $rs = do_sql($SQL);

  while($row=$rs->FetchRow()) {
    
    system("ENC_ID=".$row[0]." nice -n 10 ./encode_process.php >/dev/null 2>&1 &");
  }
  $rs->Close();
  sleep(60);
}
?>
