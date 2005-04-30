#!/usr/bin/php -q
<?php
require("config.php");
require("dblib.php");
require("status.inc.php");

// zruseni ceske diakritiky
function strip_diacritics($str) {
//       return strtr($str, "Ã¡Ã¨Ã¯Ã©Ã¬Ã­Ã²Ã³Ã¸Â¹Â»ÃºÃ¹Ã½Â¾Ã~AÃ~HÃ~OÃ~IÃ~LÃ~MÃ~RÃ~SÃ~XÂ©Â«Ã~ZÃ~]Â®", "acdeeinorstuuyzACDEEINORSTUYZ");
        return strtr($str, "áèïéìíòóø¹»úùý¾ÁÈÏÉÌÍÒÓØ©«ÚÝ®", "acdeeinorstuuyzACDEEINORSTUYZ");
};

chdir($grab_storage);

$grb_id = getenv("GRB_ID");

// zjisti informace o danem grabu
$SQL = "select ch.chn_name, g.grb_date_start, g.grb_date_end, t.tel_name
          from channel ch, television t, grab g, request r
          where ch.chn_id=t.chn_id and
                t.tel_id=g.tel_id and
                g.grb_id=r.grb_id and
                g.grb_id=$grb_id";
$rs = db_sql($SQL);
$row = $rs->FetchRow();

$begin_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-$grab_date_start_shift*60, "Y-m-d H:i:s");
$channel = strtolower(strip_diacritics($row[0]));
$timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-$grab_date_start_shift*60, "Ymd-H");
$grab_name = "DVB-".$channel."-".$timestamp."-".ereg_replace("[/ ()&]", "_", strip_diacritics($row[3]));
//$grab_name = "DVB-".$timestamp."-".$channel."-".ereg_replace("[/ ()&]", "_", strip_diacritics($row[3]));

$test = "/bin/ls -lah $grab_storage/$grab_name.ts; if [ $? -eq 0 ] ; then echo true; else echo false; fi;";
$outputTest = system($test);
if (strstr($outputTest, 'true')) {
  $SQL = "insert into encode set grb_id=$grb_id, grb_name='$grab_name', grb_date_start='$begin_time'";
  db_sql($SQL);                                          
}
else {
  echo "Grab: $grab_name se tvari jako ze neexistuje\n";
  echo "ls -lah $grab_storage/$grab_name.ts\n";
}
?>
