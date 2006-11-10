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

$name = getenv("GRB_NAME");

list($tecka, $account,$grb_name) = split('[/]', $name);
echo "Account: $account\n";
echo "File: $grb_name\n";
$grab_name=substr($grb_name,strpos($grb_name,'_')+1);
$grab_name=substr($grab_name,0,strrpos($grab_name,'.'));
echo "Grab: $grab_name\n";
$SQL = "select usr_email from user where LOWER(usr_name) REGEXP '".$account."'";
$rs = db_sql($SQL);
$row = $rs->FetchRow();
$usr_email = $row[0];
echo "Mail: $usr_email\n";

    $msg = "Hromadny spam, protoze nechodili maily ven, nekomu to mozna prislo uz hodnekrat\n";
    $msg .= "grab $grab_name\n";
    
    $test = "/bin/ls -lah $grab_storage/$grab_name.avi; if [ $? -eq 0 ] ; then echo true; else echo false; fi;";
    $outputTest = system($test);
    $found = false;
    if (strstr($outputTest, 'true')) {
      $found = true;
      $msg .= "zkomprimovan:\n";
      $msg .= "http://".$hostname."/".$account."/".$grb_name."\n";
    }
    $test = "/bin/ls -lah $grab_storage/$grab_name.ts; if [ $? -eq 0 ] ; then echo true; else echo false; fi;";
    $outputTest = system($test);
    if (strstr($outputTest, 'true')) {
      $found = true;
      $msg .= "nezkomprimovan:\n";
      $MAX_RAND= mt_getrandmax();
      $randomSeed = mt_rand($MAX_RAND/2, $MAX_RAND);
      $command = "ln -s ".$grab_storage."/".$grab_name.".ts"." ".$grab_root."/".$account."/".$randomSeed."_".$grab_name.".ts";
      system($command);
      $msg .= "http://".$hostname."/".$account."/".$randomSeed."_".$grab_name.".ts\n";
    }
    if ($found) {
      mail($usr_email, "Hromadne info o hotovych grabech", $msg, "From: $error_email\r\n");
    }
?>
