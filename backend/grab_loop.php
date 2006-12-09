#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("status.inc.php");
require_once("loggers.inc.php");

while (true) {
  status_update();
  // vyber grab, ktery by se mel prave zacit grabovat
  $grab_time_limit_lo = time()-30*60;
  $grab_time_limit_hi = time()+2*60;
  $SQL ="select g.grb_id from grab g
           where
             grb_date_start >= ".$DB->DBTimeStamp($grab_time_limit_lo)." and grb_date_start <= ".$DB->DBTimeStamp($grab_time_limit_hi)."
             and exists (select * from request r where req_status='scheduled' and g.grb_id=r.grb_id) order by grb_date_start limit 1";
  $rs = do_sql($SQL);
  $SQL = "update request set req_status='saving' where grb_id=$grb_id";
  do_sql($SQL);

  
  while($row=$rs->FetchRow()) {
    do_cmd("GRB_ID=".$row[0]." ./grab_process.php >/dev/null 2>&1 &");
  }
  $rs->Close();
  sleep(30);
}
?>
