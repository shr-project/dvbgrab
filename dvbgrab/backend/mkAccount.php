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
$end_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[2])+$grab_date_start_shift*60, "Y-m-d H:i:s");
$channel = strtolower(strip_diacritics($row[0]));
$grab_name = "DVB-".$channel."-".ereg_replace("[/ ()]", "_", strip_diacritics($row[3]));


    // posli vsem requestujicim uzivatelum zpravu, ze grab je nekde ke stazeni
    $msg = "grab $grab_name\n";
    $msg .= "je zkomprimovan a pripraven ke stazeni na:\n";
//    $SQL = "select usr_name, usr_email, usr_ip
//            from user left join request,
//                 grab left join request
//            where grab.grb_id=$grb_id and
//                  grab.grb_enc=1";
    $SQL = "select distinct usr_name, usr_email, usr_ip from user u, grab g, request r where
            r.grb_id=$grb_id and
            u.usr_id=r.usr_id and
            r.grb_enc=1";
    $rs = db_sql($SQL);
    while ($row = $rs->FetchRow()) {
      $userDir = strtolower(strip_diacritics($row[0]));
      $MAX_RAND= mt_getrandmax();
      $randomSeed = mt_rand($MAX_RAND/2, $MAX_RAND);
      if (!is_dir($grab_root."/".$userDir)) {
        $command = "mkdir $grab_root/$userDir";
        system($command);
      }
      if (!file_exists($grab_root."/".$userDir."/.htaccess")) {
        if ($fp = fopen($grab_root."/".$userDir."/.htaccess", 'w')) {
          fwrite($fp, sprintf("Order deny,allow\n"));
          fwrite($fp, sprintf("Deny from all\n"));
          fwrite($fp, sprintf("Allow from ".$row["usr_ip"]."\n"));
          fclose($fp);
        }
      }
      $command = "ln -s ".$grab_storage."/".$grab_name.".avi"." ".$grab_root."/".$userDir."/".$randomSeed."_".$grab_name.".avi";
      system($command);
      $msg .= "http://".$hostname."/".$userDir."/".$randomSeed."_".$grab_name.".avi";
      mail($row[1], "hotovy zkomprimovany grab", $msg, "From: $error_email\r\n");
    }
?>
