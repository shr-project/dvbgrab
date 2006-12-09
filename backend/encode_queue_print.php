#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");

// Shows all encoders
$SQL ="select grb_name,
              grb_date_start,
              enc_codec,
              req_status
       from request 
            left join grab using (grb_id) 
            left join encoder using (enc_id) 
       where req_status IN ('saved','saving','encode','encoded')
       order by req_status, 
                enc_codec, 
                grb_date_start";
$rs = do_sql($SQL);
$last_enc="";
$last_status="";
while($row=$rs->FetchRow()) {
  $akt_enc=$row[2];
  $akt_status=$row[3];
  if ($akt_status != $last_status) {
    $last_status = $akt_status;
    echo "\n\n---=== $akt_status ===---\n";
  }
  if ($akt_enc != $last_enc) {
    $last_enc = $akt_enc;
    echo "\n\n$akt_enc:\n";
  }
  echo "$row[0] ($row[1])\n";
}
$rs->Close();
?>
