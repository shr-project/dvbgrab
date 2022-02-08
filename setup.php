<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php 
  require_once("config.php");
  require_once("language.inc.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/tvgrab.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2">
<title><? echo $msgGlobalTitle ?></title>
</head>
<body>
<div align="center">
<a href="index.php" class="h"><img class="top" src="images/top.png" alt="<? echo $msgGlobalTitle ?>"></a>
</div>

<?
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
    $proxy_port=$_POST["v_proxy_port"];
    $grab_history=$_POST["v_grab_history"];
    $tv_days=$_POST["v_tv_days"];
    $midnight=$_POST["v_midnight"];
    $hour_frac_item=$_POST["v_hour_frac_item"];
    $grab_quota=$_POST["v_grab_quota"];
    $dvbgrab_log=$_POST["v_dvbgrab_log"];
    $grab_date_start_shift=$_POST["v_grab_date_start_shift"];
    $grab_date_stop_shift=$_POST["v_grab_date_stop_shift"];
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
    echo "<h3 class=\"warning\">$msgSetupChangedOk</h3>";
    break;

  case "refresh":
    include("config.php");
    break;
}
?>
<h2><? echo $msgSetupWelcome ?></h2>
<p><? echo $msgSetupText ?></p>
<center>
<form name="settings" action="<?=$PHP_SELF."?action=save"?>" method="post">
<table class="config">
<tr><th class="key"><? echo $msgSetupKey ?></th><th class="value"><? echo $msgSetupValue ?></th></tr>
<tr><td colspan="2" class="desc"><? echo $msgSetupDbName ?></td></tr>
<tr><td class="key" id="db_name">db_name:</td>
<td class="value" id="v_db_name"><input class="value" type="text" name="v_db_name" value="<?php print $db_name;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupDbType ?></td></tr>
<tr><td class="key" id="db_type">db_type:</td>
<td class="value" id="v_db_type"><input class="value" type="text" name="v_db_type" value="<?php print $db_type;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupDbHost ?></td></tr>
<tr><td class="key" id="db_host">db_host:</td>
<td class="value" id="v_db_host"><input class="value" type="text" name="v_db_host" value="<?php print $db_host;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupDbUser ?></td></tr>
<tr><td class="key" id="db_user">db_user:</td>
<td class="value" id="v_db_user"><input class="value" type="text" name="v_db_user" value="<?php print $db_user;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupDbPass ?></td></tr>
<tr><td class="key" id="db_pass">db_pass:</td>
<td class="value" id="v_db_pass"><input class="value" type="text" name="v_db_pass" value="<?php print $db_pass;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupErrorStatus ?><br />
                                 <? echo $msgSetupErrorStatus0 ?><br />
                                 <? echo $msgSetupErrorStatus1 ?><br />
                                 <? echo $msgSetupErrorStatus2 ?></td></tr>
<tr><td class="key" id="error_status">error_status:</td>
<td class="value" id="v_error_status"><input class="value" type="text" name="v_error_status" value="<?php print $error_status;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupErrorEmail ?></td></tr>
<tr><td class="key" id="error_email">error_email:</td>
<td class="value" id="v_error_email"><input class="value" type="text" name="v_error_email" value="<?php print $error_email;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupAdminEmail ?></td></tr>
<tr><td class="key" id="admin_email">admin_email:</td>
<td class="value" id="v_admin_email"><input class="value" type="text" name="v_admin_email" value="<?php print $admin_email;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupReportEmail ?></td></tr>
<tr><td class="key" id="report_email">report_email:</td>
<td class="value" id="v_report_email"><input class="value" type="text" name="v_report_email" value="<?php print $report_email;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupProxyServer ?></td></tr>
<tr><td class="key" id="proxy_server">proxy_server:</td>
<td class="value" id="v_proxy_server"><input class="value" type="text" name="v_proxy_server" value="<?php print $proxy_server;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupProxyPort ?></td></tr>
<tr><td class="key" id="proxy_port">proxy_port:</td>
<td class="value" id="v_proxy_port"><input class="value" type="text" name="v_proxy_port" value="<?php print $proxy_port;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupGrabHistory ?></td></tr>
<tr><td class="key" id="grab_history">grab_history:</td>
<td class="value" id="v_grab_history"><input class="value" type="text" name="v_grab_history" value="<?php print $grab_history;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupTvDays ?></td></tr>
<tr><td class="key" id="tv_days">tv_days:</td>
<td class="value" id="v_tv_days"><input class="value" type="text" name="v_tv_days" value="<?php print $tv_days;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupMidnight ?></td></tr>
<tr><td class="key" id="midnight">midnight:</td>
<td class="value" id="v_midnight"><input class="value" type="text" name="v_midnight" value="<?php print $midnight;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupHourFracItem ?></td></tr>
<tr><td class="key" id="hour_frac_item">hour_frac_item:</td>
<td class="value" id="v_hour_frac_item"><input class="value" type="text" name="v_hour_frac_item" value="<?php print $hour_frac_item;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupGrabQuota ?></td></tr>
<tr><td class="key" id="grab_quota">grab_quota:</td>
<td class="value" id="v_grab_quota"><input class="value" type="text" name="v_grab_quota" value="<?php print $grab_quota;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupDvbgrabLog ?></td></tr>
<tr><td class="key" id="dvbgrab_log">dvbgrab_log:</td>
<td class="value" id="v_dvbgrab_log"><input class="value" type="text" name="v_dvbgrab_log" value="<?php print $dvbgrab_log;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupGrabDateStartShift ?></td></tr>
<tr><td class="key" id="grab_date_start_shift">grab_date_start_shift:</td>
<td class="value" id="v_grab_date_start_shift"><input class="value" type="text" name="v_grab_date_start_shift" value="<?php print $grab_date_start_shift;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupGrabDateStopShift ?></td></tr>
<tr><td class="key" id="grab_date_stop_shift">grab_date_stop_shift:</td>
<td class="value" id="v_grab_date_stop_shift"><input class="value" type="text" name="v_grab_date_stop_shift" value="<?php print $grab_date_stop_shift;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupGrabStorage ?></td></tr>
<tr><td class="key" id="grab_storage">grab_storage:</td>
<td class="value" id="v_grab_storage"><input class="value" type="text" name="v_grab_storage" value="<?php print $grab_storage;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupGrabStorageSize ?></td></tr>
<tr><td class="key" id="grab_storage_size">grab_storage_size:</td>
<td class="value" id="v_grab_storage_size"><input class="value" type="text" name="v_grab_storage_size" value="<?php print $grab_storage_size;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo $msgSetupGrabRoot ?></td></tr>
<tr><td class="key" id="grab_root">grab_root:</td>
<td class="value" id="v_grab_root"><input class="value" type="text" name="v_grab_root" value="<?php print $grab_root;?>"></td></tr>

<tr><td align="center" colspan="2"><input type="submit" value="<? echo $msgSetupSubmitButton ?>"></td></tr>
<tr><td colspan="2" class="desc"><? echo $msgSetupEndText ?></td></tr>
</table></form>
<form name="refresh" action="<?=$PHP_SELF."?action=refresh"?>" method="post"><input type="submit" value="<? echo $msgSetupResetButton ?>" width="80%"></form>
</center>
<?
require("footer.php");
?>
