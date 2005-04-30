<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/tvgrab.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2">
<title>:: projekt DVB grab ::</title>
</head>
<body>
<div align="center">
<a href="index.php" class="h"><img class="top" src="images/top.png" alt=":: TV grab ::"></a>
</div>

<?php 
  require_once("config.php");

switch ($_GET["action"]) {
  case "save":
    $db_name=$_POST["v_db_name"];
    $db_type=$_POST["v_db_type"];
    $db_host=$_POST["v_db_host"];
    $db_user=$_POST["v_db_user"];
    $db_pass=$_POST["v_db_pass"];
    $error_status=$_POST["v_error_status"];
    $error_email=$_POST["v_error_email"];
    $admin_email=$_POST["v_admin_email"];
    $report_email=$_POST["v_report_email"];
    $proxy_server=$_POST["v_proxy_server"];
    $grab_history=$_POST["v_grab_history"];
    $tv_days=$_POST["v_tv_days"];
    $midnight=$_POST["v_midnight"];
    $hour_frac_item=$_POST["v_hour_frac_item"];
    $grab_quota=$_POST["v_grab_quota"];
    $dvbgrab_log=$_POST["v_dvbgrab_log"];
    $grab_date_start_shift=$_POST["v_grab_date_start_shift"];
    $hostname=$_POST["v_hostname"];
    $grab_root=$_POST["v_grab_root"];
    $grab_storage=$_POST["v_grab_storage"];
    $grab_storage_size=$_POST["v_grab_storage_size"];

    $config_file = file("config.php");
    $config_new = fopen("config.php", "w");

    for ($i = 0; $i < sizeof($config_file); $i++) {
      if ($config_file[$i][0] != "$") {
        fwrite($config_new, $config_file[$i]);
      } else {
        $directive = explode("=", $config_file[$i]);
        $directive_name = substr(trim($directive[0]), 1);
        $temp = '$'.$directive_name."= \"".$$directive_name."\";\n";
        fwrite($config_new, $temp);
      }
    }
    fclose($config_new);
    echo "<h3 class=\"warning\">Konfigurace byla �sp�n� ulo�ena</h3>";
    break;

  case "refresh":
    include("config.php");
    break;
}
?>
<h2>V�tejte v konfigura�n�m rozhran� pro projekt DVB grab</h2>
<p>V�echna nastaven� se ukl�daj� do souboru config.php. Proto by tento soubor m�l b�t
p�episovateln� vlastn�kem a po nastaven� �iteln� jenom vlastn�kem. P�ed upravov�n�m proto
spus�te configure.sh a potom secure.sh. Stejny config.php je pak t�eba p�ekop�rovat do
adres��e backend, kter� se p�esune na grabovac� stroj.</p>
<center>
<form name="settings" action="<?=$PHP_SELF."?action=save"?>" method="post">
<table class="config">
<tr><th class="key">Kl��</th><th class="value">Hodnota</th></tr>
<tr><td colspan="2" class="desc">N�zev datab�ze do kter� budeme ukl�dat data</td></tr>
<tr><td class="key" id="db_name">db_name:</td>
<td class="value" id="v_db_name"><input class="value" type="text" name="v_db_name" value="<?php print $db_name;?>"></td></tr>

<tr><td colspan="2" class="desc">Typ datab�zov�ho stroje, k dispozici je d�ky AdoDB: 
                                 MySQL, PostgreSQL, Interbase, Firebird, Informix, 
                                 Oracle, MS SQL, Foxpro, Access, ADO, Sybase, FrontBase, 
                                 DB2, SAP DB, SQLite, Netezza, LDAP, and generic ODBC, ODBTP</td></tr>
<tr><td class="key" id="db_type">db_type:</td>
<td class="value" id="v_db_type"><input class="value" type="text" name="v_db_type" value="<?php print $db_type;?>"></td></tr>

<tr><td colspan="2" class="desc">N�zev po��ta�e, kde pob�� datab�zov� stroj</td></tr>
<tr><td class="key" id="db_host">db_host:</td>
<td class="value" id="v_db_host"><input class="value" type="text" name="v_db_host" value="<?php print $db_host;?>"></td></tr>

<tr><td colspan="2" class="desc">Jm�no u�ivatele, jak se budeme p�ihla�ovat do datab�ze</td></tr>
<tr><td class="key" id="db_user">db_user:</td>
<td class="value" id="v_db_user"><input class="value" type="text" name="v_db_user" value="<?php print $db_user;?>"></td></tr>

<tr><td colspan="2" class="desc">Heslo s jakym se budeme p�ihla�ovat do datab�ze</td></tr>
<tr><td class="key" id="db_pass">db_pass:</td>
<td class="value" id="v_db_pass"><input class="value" type="text" name="v_db_pass" value="<?php print $db_pass;?>"></td></tr>

<tr><td colspan="2" class="desc">Mno�stv� informac� o vznik� chyb�:<br />
                                 * 0 - Ka�d� chyba je vyps�na do str�nky<br />
                                 * 1 - Ka�d� chyba je odesl�na na chybov� email<br />
                                 * 2 - Ka�d� chyba je ignorov�na. Toto je v�choz� nastaven�</td></tr>
<tr><td class="key" id="error_status">error_status:</td>
<td class="value" id="v_error_status"><input class="value" type="text" name="v_error_status" value="<?php print $error_status;?>"></td></tr>

<tr><td colspan="2" class="desc">Email kam budou odes�l�ny informace o chyb�ch webov�ho rozhran�</td></tr>
<tr><td class="key" id="error_email">error_email:</td>
<td class="value" id="v_error_email"><input class="value" type="text" name="v_error_email" value="<?php print $error_email;?>"></td></tr>

<tr><td colspan="2" class="desc">Email kam budou odes�l�ny informace o chyb�ch v grabovac�m syst�mu</td></tr>
<tr><td class="key" id="admin_email">admin_email:</td>
<td class="value" id="v_admin_email"><input class="value" type="text" name="v_admin_email" value="<?php print $admin_email;?>"></td></tr>

<tr><td colspan="2" class="desc">Email kam bodou odes�l�ny souhrn� informace o vyu�it� syst�mu</td></tr>
<tr><td class="key" id="report_email">report_email:</td>
<td class="value" id="v_report_email"><input class="value" type="text" name="v_report_email" value="<?php print $report_email;?>"></td></tr>

<tr><td colspan="2" class="desc">Adresa proxy serveru, pokud mus� b�t pou�ita pro p��stup k vn�js�m www str�nk�m</td></tr>
<tr><td class="key" id="proxy_server">proxy_server:</td>
<td class="value" id="v_proxy_server"><input class="value" type="text" name="v_proxy_server" value="<?php print $proxy_server;?>"></td></tr>

<tr><td colspan="2" class="desc">Kolik dn� se maj� uchov�vat nagrabovan� po�ady pro sta�en�</td></tr>
<tr><td class="key" id="grab_history">grab_history:</td>
<td class="value" id="v_grab_history"><input class="value" type="text" name="v_grab_history" value="<?php print $grab_history;?>"></td></tr>

<tr><td colspan="2" class="desc">Kolik dn� dop�edu m� b�t k dispozici tv program</td></tr>
<tr><td class="key" id="tv_days">tv_days:</td>
<td class="value" id="v_tv_days"><input class="value" type="text" name="v_tv_days" value="<?php print $tv_days;?>"></td></tr>

<tr><td colspan="2" class="desc">Kterou hodinu budeme pova�ovat za p�lnoc p�i rozd�lov�n� po�ad� do jednotliv�ch dn�</td></tr>
<tr><td class="key" id="midnight">midnight:</td>
<td class="value" id="v_midnight"><input class="value" type="text" name="v_midnight" value="<?php print $midnight;?>"></td></tr>

<tr><td colspan="2" class="desc">Do jak velik�ch �sek� budeme seskupovat seznam po�ad�. 24 by m�lo b�t d�liteln� hodnotou beze zbytku.</td></tr>
<tr><td class="key" id="hour_frac_item">hour_frac_item:</td>
<td class="value" id="v_hour_frac_item"><input class="value" type="text" name="v_hour_frac_item" value="<?php print $hour_frac_item;?>"></td></tr>

<tr><td colspan="2" class="desc">Kolik grab� m��e zadat u�ivatel t�dn�</td></tr>
<tr><td class="key" id="grab_quota">grab_quota:</td>
<td class="value" id="v_grab_quota"><input class="value" type="text" name="v_grab_quota" value="<?php print $grab_quota;?>"></td></tr>

<tr><td colspan="2" class="desc">Do jak�ho souboru se maj� ukl�dat informace o pr�b�hu grabov�n�</td></tr>
<tr><td class="key" id="dvbgrab_log">dvbgrab_log:</td>
<td class="value" id="v_dvbgrab_log"><input class="value" type="text" name="v_dvbgrab_log" value="<?php print $dvbgrab_log;?>"></td></tr>

<tr><td colspan="2" class="desc">O kolik minut se m� posunout za��tek a konec nahr�v�n� po�adu</td></tr>
<tr><td class="key" id="grab_date_start_shift">grab_date_start_shift:</td>
<td class="value" id="v_grab_date_start_shift"><input class="value" type="text" name="v_grab_date_start_shift" value="<?php print $grab_date_start_shift;?>"></td></tr>

<tr><td colspan="2" class="desc">N�zev po��ta�e kde se budou po�ady nahr�vat</td></tr>
<tr><td class="key" id="hostname">hostname:</td>
<td class="value" id="v_hostname"><input class="value" type="text" name="v_hostname" value="<?php print $hostname;?>"></td></tr>

<tr><td colspan="2" class="desc">Adres�� do kter�ho se budou nahr�vat po�ady</td></tr>
<tr><td class="key" id="grab_storage">grab_storage:</td>
<td class="value" id="v_grab_storage"><input class="value" type="text" name="v_grab_storage" value="<?php print $grab_storage;?>"></td></tr>

<tr><td colspan="2" class="desc">Kolik GB prostoru m�me vyhrazeno pro nahran� po�ady</td></tr>
<tr><td class="key" id="grab_storage_size">grab_storage_size:</td>
<td class="value" id="v_grab_storage_size"><input class="value" type="text" name="v_grab_storage_size" value="<?php print $grab_storage_size;?>"></td></tr>

<tr><td colspan="2" class="desc">Adres�� kam se budou ukl�dat odkazy na hotov� po�ady. Mus� b�t p��stupn� pro http server</td></tr>
<tr><td class="key" id="grab_root">grab_root:</td>
<td class="value" id="v_grab_root"><input class="value" type="text" name="v_grab_root" value="<?php print $grab_root;?>"></td></tr>

<tr><td align="center" colspan="2"><input type="submit" value="Hotovo"></td></tr>
<tr><td colspan="2" class="desc">Nezapome�t� spustit secure.sh a pak zkop�rovat config.php 
                                 do adres��e backend a cel� adres�� backend na grabovac� stroj.</td></tr>
</table></form>
<form name="refresh" action="<?=$PHP_SELF."?action=refresh"?>" method="post"><input type="submit" value="Obnovit" width="80%"></form>
</center>
<?
require("footer.php");
?>
