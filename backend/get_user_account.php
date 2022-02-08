#!/usr/bin/php4 -q
<?php
require_once("dblib.php");

// soubor, kam ulozime informace o uctech
$FTP_ACCOUNT_TMP = getenv("FTP_ACCOUNT_TMP");
if (($FTP_ACCOUNT_TMP == "")) {
	echo "ERROR: nebyla zadana hodnota promenne FTP_ACCOUNT_TMP";
	exit;
}

$SQL = "select usr_name, usr_pass from user
			order by usr_name";
$rs = db_sql($SQL);

$fp = @fopen($FTP_ACCOUNT_TMP, "w");
if (!$fp) {
	echo "ERROR: nepodarilo se otevrit soubor $FTP_ACCOUNT_TMP pro zapis\n";
	exit;
}

// vymaz soubor
ftruncate($fp, 0);

$TVGRAB_USER=getenv("TVGRAB_USER");
$TVGRAB_PASS=getenv("TVGRAB_PASS");
while($row=$rs->FetchRow()) {
        if ($row[0] == $TVGRAB_USER) continue;
	fwrite($fp, "ftp_".$row[0]."\n");
	fwrite($fp, $row[1]."\n");
}

// zapsani privilegovaneho uctu
if ($TVGRAB_USER != "") {
	fwrite($fp, $TVGRAB_USER."\n");
	fwrite($fp, $TVGRAB_PASS."\n");
}
				
fclose($fp);
?>
