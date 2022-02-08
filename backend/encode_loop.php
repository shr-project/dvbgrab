#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dblib.php");
require_once("log.inc.php");

if ($fp = fopen("$dvbgrab_log.encoding", 'a')) {
  fwrite($fp, sprintf("%s starting encode_loop\n", date("Y-m-d G:i")));
  fclose($fp);
}

// Runs all encoders
$SQL ="select enc_id from encoder order by enc_id";
while (true) {
  $rs = db_sql($SQL);

  while($row=$rs->FetchRow()) {
    system("ENC_ID=".$row[0]." nice -n 10 ./encode_process.php >>'$dvbgrab_log.encoding' 2>&1 &");
  }
  $rs->Close();
  sleep(600);
}
?>
