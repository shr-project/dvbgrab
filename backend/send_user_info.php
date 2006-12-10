<?php

require_once("config.php");
require_once("dolib.inc.php");
require_once("lang/lang."._Config_grab_backend_lang.".inc.php");

$SQL = "select usr_name, usr_email, usr_pass, usr_icq, usr_jabber, usr_last_activity, usr_ip, enc_codec from userinfo natural join encoder";

$msgMy = "DVBgrab info\n";
$rs = do_sql($SQL);
while ($row = $rs->FetchRow()) {
    $msg = _MsgAccountLogin." ".$row["usr_name"]."\n";
    $msg .= _MsgAccountPass." MD5: ".$row["usr_pass"]." try john the ripper ourself :) or better use function "._MsgAccountLostPass." on the web";
    $msg .= _MsgAccountEmail." ".$row["usr_email"]."\n";
    $msg .= _MsgAccountIcq." ".$row["usr_icq"]."\n";
    $msg .= _MsgAccountJabber." ".$row["usr_jabber"]."\n";
    $msg .= "LastActivity ".$row["usr_last_activity"]."\n";
    $msg .= _MsgAccountIp." ".$row["usr_ip"]."\n";
    $msg .= "Encoder ".$row["enc_codec"]."\n";
    send_mail($row["usr_email"], "DVBgrab info", $msgMy.$msg);
}
?>
