#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dblib.php");
require_once("output.inc.php");
require_once("log.inc.php");

/**
 * Returns recommended grab end date.
 * Lets the evening tv shows to have extra shift.
 *
 * @param timestamp when the tv show ends
 * @return timestamp when the grab should end
 */
function getEndTime($timestamp) {
    global $grab_date_stop_shift;
    global $grab_date_stop_bonus_shift;

    $bonus_shift = 0;
    $hour = date("G", $timestamp);
    if ($hour >= 20 || $hour < 5) {
        $bonus_shift = $grab_date_stop_bonus_shift;
    }

    return $timestamp + ($grab_date_stop_shift + $bonus_shift)*60;
}

$grb_id = getenv("GRB_ID");

// update grab status
$SQL = "update grab set grb_status='processing' where grb_id='$grb_id'";
db_sql($SQL);

// get grab info
$SQL = "select ch.chn_name, g.grb_date_start, g.grb_date_end, t.tel_name
          from channel ch, television t, grab g
          where ch.chn_id=t.chn_id and
                t.tel_id=g.tel_id and
                g.grb_id='$grb_id'";
$rs = db_sql($SQL);
if (!($row = $rs->FetchRow())) {
    logError("no such grab_id: $grb_id");
    exit;
}
ensure_free_space();

$begin_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-$grab_date_start_shift*60, "Y-m-d H:i:s");
$end_time = $DB->UserTimeStamp(getEndTime($DB->UnixTimeStamp($row[2])), "Y-m-d H:i:s");
$channel = strtolower(strip_diacritics($row[0]));
$timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1]), "Ymd-Hi");
$rs->Close();

$grab_name = get_grab_basename($grb_id);

// dvbgrab -b BEGIN_TIME -e END_TIME -i INPUT_CHANNEL -o OUTPUT_FILE
$grab_filename = "$grab_storage/$grab_name.mpg";
$command = "./dvbgrab -b '$begin_time' -e '$end_time' -i '$channel' -o '$grab_filename'";
logInfo("grabing: $command");
$output = system($command);

if (is_valid_file($grab_filename)) {
    logInfo("grab $grab_name is ok");
    $SQL = "update grab set grb_status='done', grb_basename='$grab_name' where grb_id=$grb_id";
    db_sql($SQL);
} else {
    logError("grab $grab_name got error: $output");
    $SQL = "update grab set grb_status='error' where grb_id=$grb_id";
    db_sql($SQL);
    report_grab_failure($grb_id, $grab_name);
}
?>
