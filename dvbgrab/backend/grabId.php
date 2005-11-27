#!/usr/bin/php -q
<?php
require("./config.php");
require("./dblib.php");
require("./status.inc.php");

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

// uprava statusu poradu
$SQL = "update grab set grb_status='processing' where grb_id=$grb_id";
db_sql($SQL);
status_update();

$begin_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-$grab_date_start_shift*60, "Y-m-d H:i:s");
$end_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[2])+$grab_date_stop_shift*60, "Y-m-d H:i:s");
#$end_time = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-($grab_date_start_shift-1)*60, "Y-m-d H:i:s");
$channel = strtolower(strip_diacritics($row[0]));
#$timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1])-$grab_date_start_shift*60, "Ymd-H");
$timestamp = $DB->UserTimeStamp($DB->UnixTimeStamp($row[1]), "Ymd-H");

$grab_name = "DVB-".$timestamp."-".$channel."-".ereg_replace("[/ ()?&]", "_", strip_diacritics($row[3]));
        
// dvbgrab -b BEGIN_TIME -e END_TIME -i INPUT_CHANNEL -o OUTPUT_FILE
$command = "./dvbgrab -b \"".$begin_time."\" -e \"".$end_time."\" -i ".$channel." -o ".$grab_storage."/".$grab_name.".ts 2>&1 >> $dvbgrab_log";
if ($fp = fopen($dvbgrab_log, 'a')) {
  fwrite($fp, sprintf("\n\nINFO: %s starting grab %s on channel %s time %s-%s\n", date("Y-m-d G:i:s"), $grab_name, $channel, $begin_time, $end_time));
}
$output = system($command);
echo $output;
// if (strstr($output, 'Terminated')) {
// vymaz cache lstatu
// clearstatcache();
// is_file a file_exists neumi soubory vetsi nez 2048 takze misto toho pouziju radsi systemovy ls
// if (file_exists($grab_storage."/".$grab_name.".ts") || is_file($grab_storage."/".$grab_name.".ts")) {

$test = "/bin/ls -lah $grab_storage/$grab_name.ts; if [ $? -eq 0 ] ; then echo true; else echo false; fi;";
$outputTest = system($test);
if (strstr($outputTest, 'true')) {
// ok
  if ($fp) {
    fwrite($fp, sprintf("\n\nINFO: %s %s saved successfully\n", date("Y-m-d G:i:s"), $grab_name));
    fclose($fp);
  }
  $SQL = "update grab set grb_status='done' where grb_id=$grb_id";
  db_sql($SQL);
  status_update();

  $SQL = "select count(*) from request where grb_id=$grb_id and grb_enc=1";
  db_sql($SQL);
  $rs = db_sql($SQL);
  $row = $rs->FetchRow();
  if ($row[0]) {  // nekdo chtel encodovat do MPEG4
    $SQL = "insert into encode set grb_id=$grb_id, grb_name='$grab_name', grb_date_start='$begin_time'";
    db_sql($SQL);
    // encodovaci smycka si bude vybirat z tabulky encode podle casu zacatku grabu a postupne prevadet a po dokonceni odesilat info uzivatelum
  }
  // posli vsem requestujicim uzivatelum zpravu, ze grab je nekde ke stazeni
  $msg = "grab: $grab_name id: $grb_id\n";
  $msg .= "od: $begin_time\n";
  $msg .= "do: $end_time\n";
  $msg .= "z: $channel\n";
  $msg .= "MD5sum: ";
  $msg .= md5_file("$grab_storage/$grab_name.ts");
  $msg .= "je pripraven ke stazeni na:\n";
//  $SQL = "select usr_name, usr_email, usr_ip
//            from user left join request,
//                 grab left join request
//            where grab.grb_id=$grb_id and
//                  grab.grb_enc=0";
  $SQL = "select distinct usr_name, usr_email, usr_ip, req_id from user u, grab g, request r where
          r.grb_id=$grb_id and
          u.usr_id=r.usr_id and
          r.grb_enc=0";

  $rs = db_sql($SQL);
  while ($row = $rs->FetchRow()) {
    $userDir = strtolower(strip_diacritics($row[0]));
    $MAX_RAND= mt_getrandmax();
    $randomSeed = mt_rand($MAX_RAND/2, $MAX_RAND);
    if (!is_dir($grab_root."/".$userDir)) {
      $command = "mkdir -p $grab_root/$userDir";
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
    $command = "ln -s ".$grab_storage."/".$grab_name.".ts"." ".$grab_root."/".$userDir."/".$randomSeed."_".$grab_name.".ts";
    system($command);
    $output = "http://".$hostname."/".$userDir."/".$randomSeed."_".$grab_name.".ts";
    $msgUser = $msg.$output;
    $update = "update request set req_output='$output' where req_id='".$row["req_id"]."'";
    echo $update;
    db_sql($update);
    mail($row[1], "hotovy grab", $msgUser, "From: $admin_email\r\n");
  }
} else {
// bad
  if ($fp) {
    fwrite($fp, sprintf("\n\nERROR: %s dvbgrab error %s\n", date("Y-m-d G:i:s"), $output));
    fclose($fp);
  }
  $SQL = "update grab set grb_status='error' where grb_id=$grb_id";
  db_sql($SQL);
  status_update();
  // posli vsem requestujicim uzivatelum zpravu, ze grab se nepovedl
  $msg = "grab: $grab_name id: $grb_id\n";
  $msg .= "od: $begin_time\n";
  $msg .= "do: $end_time\n";
  $msg .= "z: $channel\n";
  $msg .= "se bohuzel neuskutecnil, kvuli problemum pri nahravani\n";
  $SQL = "select distinct usr_name, usr_email from user u, grab g, request r where
          r.grb_id=$grb_id and
          u.usr_id=r.usr_id";
//
//  $SQL = "select usr_name, usr_email"
//            ." from user join request join grab"
//            ." on user.usr_id = 
//            ." where grb_id=$grb_id";
  $rs = db_sql($SQL);
  // posleme chybu i spravci
  mail($error_email, "ERROR: neuskutecneny grab", $msg, "From: $error_email\r\n");
  while ($row = $rs->FetchRow()) {
    mail($row[1], "neuskutecneny grab", $msg, "From: $error_email\r\n");
  }
}
?>
