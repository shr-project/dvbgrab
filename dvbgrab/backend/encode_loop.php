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

if ($fp = fopen($dvbgrab_log, 'a')) {
  fwrite($fp, sprintf("%s starting encode loop\n", date("Y-m-d G:i:s")));
  fclose($fp);
}

chdir($grab_storage);

while (true) {
  // vyber grab, ktery je nagrabovany nejdele
  $SQL ="select grb_id, grb_name from encode
           order by grb_date_start 
           limit 1"; 
  $rs = db_sql($SQL);
  
  if ($row=$rs->FetchRow()) {
    // pokracuj, pokud byl nalezen grab
    // encode INPUT_FILE OUTPUT_FILE
    $grab_name = $row[1];
    $grb_id = $row[0];
    $command = "/var/lib/dvbgrab/encode ".$grab_storage."/".$grab_name.".ts ".$grab_storage."/".$grab_name.".avi 2>/dev/null >/dev/null";
    system($command);
    $test = "/bin/ls -lah $grab_storage/$grab_name.avi; if [ $? -eq 0 ] ; then echo true; else echo false; fi;";
    $outputTest = system($test);
    if (strstr($outputTest, 'true')) {
//    if (file_exists($grab_storage."/".$grab_name.".avi")) {
//      printf("%s %s encoded from TS to AVI\n", date("Y-m-d G:i:s"), $grab_name);

      if ($fp = fopen($dvbgrab_log, 'a')) {
        fwrite($fp, sprintf("\n\nINFO: %s %s encoded from TS to AVI\n", date("Y-m-d G:i:s"), $grab_name));
        fclose($fp);
      }
      // encodovano, takze smazat z fronty cekajicich
      $SQL = "delete from encode where grb_id=$grb_id";
      $rs = db_sql($SQL);
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
    }
    else {
      printf("%s %s cannot be encoded from TS to AVI\n", date("Y-m-d G:i:s"), $grab_name);
      if ($fp = fopen($dvbgrab_log, 'a')) {
        fwrite($fp, sprintf("\n\nERROR: %s %s cannot be encoded\n", date("Y-m-d G:i:s"), $grab_name));
        fclose($fp);
      }
      // posli adminovi zpravu, ze se encoding nepovedl
      $msg = "grab $grab_name\n";
      $msg .= "se nepodarilo prekodovat\n";
      mail($error_email, "nepodarene encodovani", $msg, "From: $error_email\r\n");
      // chyba bude potreba nejdrive odstranit takze spime dele
      sleep(1200);
    }
  }
  sleep(300);
}
?>
