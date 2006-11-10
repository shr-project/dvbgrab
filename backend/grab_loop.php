#!/usr/bin/php -q
<?php
require("config.php");
require("dblib.php");

if ($fp = fopen($dvbgrab_log, 'a')) {
  fwrite($fp, sprintf("%s starting grab_loop\n", date("Y-m-d G:i:s")));
  fclose($fp);
}

while (true) {
  // vyber grab, ktery by se mel prave zacit grabovat
  $SQL ="select grb_id from grab
           where
             ".$DB->DBTimeStamp(time()+$grab_date_start_shift*60)." >= grb_date_start and 
             grb_status='scheduled'";
  $rs = db_sql($SQL);
  
  while($row=$rs->FetchRow()) {
    // pokracuj, pokud byl nalezen grab
//    echo "start grab id $row[0]\n";
    system("GRB_ID=".$row[0]." ./grabId.php 2>> $dvbgrab_log >> $dvbgrab_log &");
//    echo "next one\n";
  }
  sleep(30);
}
?>
