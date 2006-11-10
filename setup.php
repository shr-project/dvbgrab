<?php 
  require_once("language.inc.php");
  require_once("header.php");
  require_once("dolib.inc.php");
?>
<body>
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
    $hostname=$_POST["v_hostname"];
    $grab_root=$_POST["v_grab_root"];
    $grab_storage=$_POST["v_grab_storage"];
    $grab_storage_size=$_POST["v_grab_storage_size"];
    $grab_storage_min_size=$_POST["v_grab_storage_min_size"];
    $user_inactivity_limit=$_POST["v_user_inactivity_limit"];
    $grab_backend_lang=$_POST["v_grab_backend_lang"];
    $grab_backend_strip_diacritics=$_POST["v_grab_backend_strip_diacritics"];
    $record_time_after_last=$_POST["v_record_time_after_last"];

    $SQL="select tvg_id from tvgrabber";
    $res = do_sql($SQL);
    while ($row = $res->FetchRow()) {
      $id=$row["tvg_id"];
      $enabled=$_POST["v_tvg_".$id];
      $enable=(($enabled == "on")?"1":"0");
      #echo "$id is $enabled ~ $enable\n";
      $SQL="update tvgrabber set tvg_enabled=$enable where tvg_id=$id";
      do_sql($SQL);
    }
    $tvg_new_name=$_POST["v_tvg_newName"];
    $tvg_new_cron_time=$_POST["v_tvg_newCronTime"];
    $tvg_new_cron_cmd=$_POST["v_tvg_newCronCmd"];
    $tvg_new_enabled=$_POST["v_tvg_newEnabled"];
    if ($tvg_new_name != "") {
      if ($tvg_new_cron_time == "" || $tvg_new_cron_cmd == "") {
        echo "<h3 class=\"warning\">"._MsgSetupTvgFailed."</h3>";
      } else {
        $SQL="insert into tvgrabber(tvg_name,tvg_cron_time,tvg_cron_cmd,tvg_enabled) values('$tvg_new_name','$tvg_new_cron_time','$tvg_new_cron_cmd',".($tvg_new_enabled == "on"?"1":"0").")";
        do_sql($SQL);
      }
    }


    $config_file = file("config.php");
    $config_new = fopen("config.php", "w");
    $pattern = '/^define\("([^"]*)",.*/';
    for ($i = 0; $i < sizeof($config_file); $i++) {
      if (preg_match($pattern,$config_file[$i],$regs)) {
        $directive_name = $regs[1];
        $directive_var = preg_replace("/^_Config_/","",$directive_name);
//        echo "$directive_name => $$directive_var\n";
        $temp = 'define("'.$directive_name.'","'.$$directive_var."\");\n";
        fwrite($config_new, $temp);
      } else {
        fwrite($config_new, $config_file[$i]);
      }
    }
    fclose($config_new);
    echo "<h3 class=\"warning\">"._MsgSetupChangedOk."</h3>";
    break;

  case "refresh":
    include("config.php");
    break;
}
$db_name=_Config_db_name;
$db_type=_Config_db_type;
$db_host=_Config_db_host;
$db_user=_Config_db_user;
$db_pass=_Config_db_pass;
$error_status=_Config_error_status;
$error_email=_Config_error_email;
$admin_email=_Config_admin_email;
$report_email=_Config_report_email;
$from_email=_Config_from_email;
$proxy_server=_Config_proxy_server;
$proxy_port=_Config_proxy_port;
$grab_history=_Config_grab_history;
$tv_days=_Config_tv_days;
$midnight=_Config_midnight;
$hour_frac_item=_Config_hour_frac_item;
$grab_quota=_Config_grab_quota;
$dvbgrab_log=_Config_dvbgrab_log;
$dvbgrab_encode_log=_Config_dvbgrab_encode_log;
$grab_date_start_shift=_Config_grab_date_start_shift;
$grab_date_stop_shift=_Config_grab_date_stop_shift;
$hostname=_Config_hostname;
$grab_root=_Config_grab_root;
$grab_storage=_Config_grab_storage;
$grab_storage_size=_Config_grab_storage_size;
$grab_storage_min_size=_Config_grab_storage_min_size;
$user_inactivity_limit=_Config_user_inactivity_limit;
$grab_backend_lang=_Config_grab_backend_lang;
$grab_backend_strip_diacritics=_Config_grab_backend_strip_diacritics;
$record_time_after_last=_Config_record_time_after_last;

?>
<h2><? echo _MsgSetupWelcome ?></h2>
<p><? echo _MsgSetupText ?></p>
<center>
<form name="settings" action="<?=$PHP_SELF."?action=save"?>" method="post">
<table class="config">
<tr><th class="key"><? echo _MsgSetupKey ?></th><th class="value"><? echo _MsgSetupValue ?></th></tr>
<tr><td colspan="2" class="desc"><? echo _MsgSetupDbName ?></td></tr>
<tr><td class="key" id="db_name">db_name:</td>
<td class="value" id="v_db_name"><input class="value" type="text" name="v_db_name" value="<?php print $db_name;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupDbType ?></td></tr>
<tr><td class="key" id="db_type">db_type:</td>
<td class="value" id="v_db_type"><input class="value" type="text" name="v_db_type" value="<?php print $db_type;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupDbHost ?></td></tr>
<tr><td class="key" id="db_host">db_host:</td>
<td class="value" id="v_db_host"><input class="value" type="text" name="v_db_host" value="<?php print $db_host;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupDbUser ?></td></tr>
<tr><td class="key" id="db_user">db_user:</td>
<td class="value" id="v_db_user"><input class="value" type="text" name="v_db_user" value="<?php print $db_user;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupDbPass ?></td></tr>
<tr><td class="key" id="db_pass">db_pass:</td>
<td class="value" id="v_db_pass"><input class="value" type="text" name="v_db_pass" value="<?php print $db_pass;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupErrorStatus ?><br />
                                 <? echo _MsgSetupErrorStatus0 ?><br />
                                 <? echo _MsgSetupErrorStatus1 ?><br />
                                 <? echo _MsgSetupErrorStatus2 ?></td></tr>
<tr><td class="key" id="error_status">error_status:</td>
<td class="value" id="v_error_status"><input class="value" type="text" name="v_error_status" value="<?php print $error_status;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupErrorEmail ?></td></tr>
<tr><td class="key" id="error_email">error_email:</td>
<td class="value" id="v_error_email"><input class="value" type="text" name="v_error_email" value="<?php print $error_email;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupAdminEmail ?></td></tr>
<tr><td class="key" id="admin_email">admin_email:</td>
<td class="value" id="v_admin_email"><input class="value" type="text" name="v_admin_email" value="<?php print $admin_email;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupReportEmail ?></td></tr>
<tr><td class="key" id="report_email">report_email:</td>
<td class="value" id="v_report_email"><input class="value" type="text" name="v_report_email" value="<?php print $report_email;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupProxyServer ?></td></tr>
<tr><td class="key" id="proxy_server">proxy_server:</td>
<td class="value" id="v_proxy_server"><input class="value" type="text" name="v_proxy_server" value="<?php print $proxy_server;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupProxyPort ?></td></tr>
<tr><td class="key" id="proxy_port">proxy_port:</td>
<td class="value" id="v_proxy_port"><input class="value" type="text" name="v_proxy_port" value="<?php print $proxy_port;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabHistory ?></td></tr>
<tr><td class="key" id="grab_history">grab_history:</td>
<td class="value" id="v_grab_history"><input class="value" type="text" name="v_grab_history" value="<?php print $grab_history;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupTvDays ?></td></tr>
<tr><td class="key" id="tv_days">tv_days:</td>
<td class="value" id="v_tv_days"><input class="value" type="text" name="v_tv_days" value="<?php print $tv_days;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupMidnight ?></td></tr>
<tr><td class="key" id="midnight">midnight:</td>
<td class="value" id="v_midnight"><input class="value" type="text" name="v_midnight" value="<?php print $midnight;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupHourFracItem ?></td></tr>
<tr><td class="key" id="hour_frac_item">hour_frac_item:</td>
<td class="value" id="v_hour_frac_item"><input class="value" type="text" name="v_hour_frac_item" value="<?php print $hour_frac_item;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabQuota ?></td></tr>
<tr><td class="key" id="grab_quota">grab_quota:</td>
<td class="value" id="v_grab_quota"><input class="value" type="text" name="v_grab_quota" value="<?php print $grab_quota;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupUserInactivityLimit ?></td></tr>
<tr><td class="key" id="user_inactivity_limit">user_inactivity_limit:</td>
<td class="value" id="v_user_inactivity_limit"><input class="value" type="text" name="v_user_inactivity_limit" value="<?php print $user_inactivity_limit;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupDvbgrabLog ?></td></tr>
<tr><td class="key" id="dvbgrab_log">dvbgrab_log:</td>
<td class="value" id="v_dvbgrab_log"><input class="value" type="text" name="v_dvbgrab_log" value="<?php print $dvbgrab_log;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabDateStartShift ?></td></tr>
<tr><td class="key" id="grab_date_start_shift">grab_date_start_shift:</td>
<td class="value" id="v_grab_date_start_shift"><input class="value" type="text" name="v_grab_date_start_shift" value="<?php print $grab_date_start_shift;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabDateStopShift ?></td></tr>
<tr><td class="key" id="grab_date_stop_shift">grab_date_stop_shift:</td>
<td class="value" id="v_grab_date_stop_shift"><input class="value" type="text" name="v_grab_date_stop_shift" value="<?php print $grab_date_stop_shift;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupRecordTimeAfterLast ?></td></tr>
<tr><td class="key" id="record_time_after_last">record_time_after_last:</td>
<td class="value" id="v_record_time_after_last"><input class="value" type="text" name="v_record_time_after_last" value="<?php print $record_time_after_last;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupHostname ?></td></tr>
<tr><td class="key" id="hostname">hostname:</td>
<td class="value" id="v_hostname"><input class="value" type="text" name="v_hostname" value="<?php print $hostname;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabStorage ?></td></tr>
<tr><td class="key" id="grab_storage">grab_storage:</td>
<td class="value" id="v_grab_storage"><input class="value" type="text" name="v_grab_storage" value="<?php print $grab_storage;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabStorageSize ?></td></tr>
<tr><td class="key" id="grab_storage_size">grab_storage_size:</td>
<td class="value" id="v_grab_storage_size"><input class="value" type="text" name="v_grab_storage_size" value="<?php print $grab_storage_size;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabStorageMinSize ?></td></tr>
<tr><td class="key" id="grab_storage_min_size">grab_storage_min_size:</td>
<td class="value" id="v_grab_storage_min_size"><input class="value" type="text" name="v_grab_storage_min_size" value="<?php print $grab_storage_min_size;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabRoot ?></td></tr>
<tr><td class="key" id="grab_root">grab_root:</td>
<td class="value" id="v_grab_root"><input class="value" type="text" name="v_grab_root" value="<?php print $grab_root;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupGrabBackendLang ?></td></tr>
<tr><td class="key" id="grab_backend_lang">grab_backend_lang:</td>
<td class="value" id="v_grab_backend_lang"><input class="value" type="text" name="v_grab_backend_lang" value="<?php print $grab_backend_lang;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupBackendStripDiacritics ?></td></tr>
<tr><td class="key" id="grab_backend_strip_diacritics">grab_backend_strip_diacritics:</td>
<td class="value" id="v_grab_backend_strip_diacritics"><input class="value" type="text" name="v_grab_backend_strip_diacritics" value="<?php print $grab_backend_strip_diacritics;?>"></td></tr>

<tr><td colspan="2" class="desc"><? echo _MsgSetupTvgDesc ?></td></tr>
<? 
  $SQL="select tvg_id,tvg_name,tvg_cron_time,tvg_cron_cmd,tvg_enabled from tvgrabber";
  $res = do_sql($SQL);
  echo "<tr><td colspan=\"2\"><table style=\"width: 100%;\"><col width=\"90%\"><col width=\"10%\">\n";
  echo "<tr><td class=\"key\">"._MsgSetupTvgName."</td><td class=\"key\">"._MsgSetupTvgEnabled."</td></tr>";
  while ($row = $res->FetchRow()) {
        echo "<tr><td>".$row["tvg_name"]."</td><td class=\"value\" rowspan=\"3\"><input type=\"checkbox\" name=\"v_tvg_".$row["tvg_id"]."\" value=\"on\" ".(($row["tvg_enabled"]==1)?"checked":"")." /></td></tr>\n";
	echo "<tr><td>&nbsp;&nbsp;&nbsp;"._MsgSetupTvgRunAt.": ".$row["tvg_cron_time"]."</td></tr>\n";
	echo "<tr><td class=\"key_border\">&nbsp;&nbsp;&nbsp;"._MsgSetupTvgRun.": ".$row["tvg_cron_cmd"]."</td></tr>\n";
  }
  echo "<tr><td class=\"key\" colspan=\"2\">"._MsgSetupTvgNew."</td></tr>\n";
  echo "<tr><td>&nbsp;&nbsp;&nbsp;"._MsgSetupTvgName.": <input class=\"value\" type=\"text\" name=\"v_tvg_newName\"></td><td class=\"value\" rowspan=\"3\"><input type=\"checkbox\" name=\"v_tvg_newEnabled\" checked></td></tr>\n";
  echo "<tr><td>&nbsp;&nbsp;&nbsp;"._MsgSetupTvgRunAt.": <input class=\"value\" type=\"text\" name=\"v_tvg_newCronTime\"></td></tr>\n";
  echo "<tr><td class=\"key_border\">&nbsp;&nbsp;&nbsp;"._MsgSetupTvgRun.": <input class=\"value\" type=\"text\" name=\"v_tvg_newCronCmd\"></td></tr>\n";

  echo "</table></td></tr>\n";
?>
</tr>

<tr><td align="center" colspan="2"><input type="submit" value="<? echo _MsgSetupSubmitButton ?>"></td></tr>
<tr><td colspan="2" class="desc"><? echo _MsgSetupEndText ?></td></tr>
</table></form>
<form name="refresh" action="<?=$PHP_SELF."?action=refresh"?>" method="post"><input type="submit" value="<? echo _MsgSetupResetButton ?>" width="80%"></form>
</center>
</body>
</html>
