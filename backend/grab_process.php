#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("output.inc.php");
require_once("loggers.inc.php");

$grb_id = getenv("GRB_ID");

$logdbg->log("Grabing id: $grb_id");
// get grab info
$SQL = "select ch.chn_ip, ch.chn_port, g.grb_date_start, g.grb_date_end
          from channel ch, television t, grab g
          where ch.chn_id=t.chn_id and
                t.tel_id=g.tel_id and
                g.grb_id=$grb_id";
$rs = do_sql($SQL);
if (!($row = $rs->FetchRow())) {
  $logerr->log("No such grab_id: $grb_id");
  exit;
}
ensure_free_space();

$chn_ip = $row[0];
$chn_port = $row[1];
$begin_time = $DB->UnixTimeStamp($row[2]);
$end_time = $DB->UnixTimeStamp($row[3]);
$rs->Close();

$grab_name = get_grab_basename($grb_id);
$grab_filename = _Config_grab_storage."/$grab_name.ts";
        
while ($begin_time > time()) {
  sleep(10); // wait for beginning
}
$logdbg->log("starting grab: $grab_filename");
$cmd = "dumprtp $chn_ip $chn_port > $grab_filename 2>/dev/null & "
      ."while [[ `date \"+%s\"` -le $end_time ]] ; do "
      ."  sleep 10; "
      ."done; "
      ."kill %1";

$logdbg->log("running: $cmd");
$SQL = "update grab set grb_date_start=".$DB->DBTimeStamp(time())." where grb_id=$grb_id";
do_sql($SQL);
$output = do_cmd($cmd);
$SQL = "update grab set grb_date_end=".$DB->DBTimeStamp(time())." where grb_id=$grb_id";
do_sql($SQL);
$logdbg->log("finishing grab: $grab_filename");
$logdbg->log("size: ".get_file_size($grab_filename));

if (is_empty_file($grab_filename)) {
  $logerr->log("grab $grab_name got error: $output");
  $SQL = "update request set req_status='error' where grb_id=$grb_id";
  do_sql($SQL);
  report_grab_failure($grb_id, $grab_name);
} else {
  $logdbg->log("grab $grab_name is ok");
  $SQL = "update request set req_status='saved' where grb_id=$grb_id";
  do_sql($SQL);
}
?>
