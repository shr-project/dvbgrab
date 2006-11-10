#!/usr/bin/php
<?php

require_once("config.php");
require_once("dolib.inc.php");
require_once("lang/lang._Config_grab_backend_lang.inc.php");

// zjisti informace o hotovych grabech za poslednich 24 hodin
$sqlYesterday = $DB->OffsetDate(-1);
$SQL = "select ch.chn_name, t.tel_name, t.tel_date_start
        from channel ch, television t, request r, grab g
        where
            ch.chn_id=t.chn_id and
            t.tel_id=g.tel_id and
            r.grb_id=g.grb_id and
            r.req_status='done' and
            t.tel_date_start > $sqlYesterday
        order by t.tel_date_start";

$rs = do_sql($SQL);

$body = _MsgBackendGrabList.date("Ymd").":";
while ($row = $rs->FetchRow()) {
        $tv_date = $DB->UserTimeStamp($row[2]);
	$tv_channel = strtolower($row[0]);
	$grab_name = $row[2]."\t".$tv_channel."\t".$row[1];

	$body .= $grab_name."\n";
}
send_mail(_Config_report_email, _MsgBackendGrabList.date("Ymd"), $body, $header);
?>
