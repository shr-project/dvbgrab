<?php
require_once("config.php");
require_once("dblib.php");

/**
 * Lets timeouted grab processing to go to error.
 * Second invocation of grabId will ensure the error reporting.
 */
function status_update() {
	global $DB;
	global $grab_date_stop_shift;
	global $grab_date_stop_bonus_shift;

	$grab_stop_limit = $DB->DBTimeStamp(time()-(10+$grab_date_stop_bonus_shift+$grab_date_stop_shift)*60);

	$SQL = "update grab set grb_status='scheduled'
				where grb_date_end < $grab_stop_limit and
				grb_status='processing'";
	db_sql($SQL);
}


// vim: noexpandtab tabstop=4
?>
