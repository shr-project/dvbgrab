#!/usr/bin/php
<?php

require_once("config.php");
require_once("mail.php");
require_once("dblib.php");

// zjisti informace o hotovych grabech za poslednich 24 hodin
$SQL = "select ch.chn_name, t.tel_name, date_format(g.grb_date_start, '%Y-%m-%d\t%H:%i')
			from channel ch, television t, grab g
			where
				ch.chn_id=t.chn_id and
				t.tel_id=g.tel_id and
				g.grb_status='done' and
				g.grb_date_start>subdate(now(), interval 24 hour)
			order by g.grb_date_start";
$rs = db_sql($SQL);

$body = "";
while ($row = $rs->FetchRow()) {
	$TV_CHANNEL = strtolower($row[0]);
	$GRAB_NAME = $row[2]."\t".$TV_CHANNEL."\t".$row[1];

	$body .= $GRAB_NAME."\n";
}

ereg("([0-9]{4})([0-9]{2})([0-9]{2})", date("Ymd"), $regs);
send_mail($report_email, "Seznam grabù ".date("d.m.Y", mktime(0, 0, 0, $regs[2], $regs[3]-1, $regs[1])), $body);
?>
