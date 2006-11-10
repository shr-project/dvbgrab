#!/usr/bin/php -q
<?php
require("./config.php");
require("./dblib.php");
require("./status.inc.php");

// zruseni ceske diakritiky
function strip_diacritics($str) {
        return strtr($str, "áèïéìíòóø¹»úùý¾ÁÈÏÉÌÍÒÓØ©«ÚÝ®", "acdeeinorstuuyzACDEEINORSTUYZ");
};
// zjisti informace o danem grabu
$SQL1 = "select grab.grb_id from request natural join grab where grb_enc=0";
$rs1 = db_sql($SQL1);
while ($row = $rs1->FetchRow()) {
$grb_id=$row[0];

$SQL = "select ch.chn_name, g.grb_date_start, g.grb_date_end, t.tel_name
          from channel ch, television t, grab g, request r
          where ch.chn_id=t.chn_id and
                t.tel_id=g.tel_id and
                g.grb_id=r.grb_id and
                g.grb_id=$grb_id";
$rs = db_sql($SQL);
$row = $rs->FetchRow();

$begin_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-$grab_date_start_shift*60, "Y-m-d H:i:s");
$end_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[2])+10*$grab_date_start_shift*60, "Y-m-d H:i:s");
#$end_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-($grab_date_start_shift-1)*60, "Y-m-d H:i:s");
$channel = strtolower(strip_diacritics($row[0]));
$timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-$grab_date_start_shift*60, "Ymd-H");
$grab_name = "DVB-".$timestamp."-".$channel."-".ereg_replace("[/ ()?&]", "_", strip_diacritics($row[3]));
$test = "/bin/ls -lah $grab_storage/$grab_name.ts; if [ $? -eq 0 ] ; then echo true; else echo false; fi;";
$outputTest = system($test);
if (strstr($outputTest, 'true')) {
    echo $grab_storage."/".$grab_name.".ts\n";
}
else {
echo $grab_storage."/".$grab_name.".ts uz neexistuje\n";
}
}
?>
