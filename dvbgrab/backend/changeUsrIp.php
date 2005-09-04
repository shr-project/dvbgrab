#!/usr/bin/php -q
<?php
require("./config.php");
require("./dblib.php");
require("./status.inc.php");

// zruseni ceske diakritiky
function strip_diacritics($str) {
//       return strtr($str, "ĂĄĂ¨ĂŻĂŠĂŹĂ­Ă˛ĂłĂ¸ÂšÂťĂşĂšĂ˝ÂžĂ~AĂ~HĂ~OĂ~IĂ~LĂ~MĂ~RĂ~SĂ~XÂŠÂŤĂ~ZĂ~]ÂŽ", "acdeeinorstuuyzACDEEINORSTUYZ");
        return strtr($str, "áčďéěíňóřšťúůýžÁČĎÉĚÍŇÓŘŠŤÚÝŽ", "acdeeinorstuuyzACDEEINORSTUYZ");
};

$usr_id = getenv("USR_ID");

// zjisti informace o danem cloveku
$SQL = "select usr_name, usr_email, usr_ip
          from user
          where usr_id=$usr_id";
$rs = db_sql($SQL);
$row = $rs->FetchRow();

    $userDir = strtolower(strip_diacritics($row[0]));
    if (!is_dir($grab_root."/".$userDir)) {
      $command = "mkdir $grab_root/$userDir";
      system($command);
    }
    if ($fp = fopen($grab_root."/".$userDir."/.htaccess", 'w')) {
      fwrite($fp, sprintf("Order deny,allow\n"));
      fwrite($fp, sprintf("Deny from all\n"));
      fwrite($fp, sprintf("Allow from ".$row[2]."\n"));
      fclose($fp);
      $msgUser = "IP adresa pro stahovani uzivatele $row[0] byla zmenena na $row[2]\n";
      echo "$msgUser";
      mail($row[1], "DVBgrab zmena IP provedena", $msgUser, "From: $admin_email\r\n");
    } else {
      echo "Nepovedlo se provest zmenu";
    }
?>
