<?php
require("authenticate.php");
require_once("dolib.inc.php");
require_once("const.php");
require_once("config.php");
require_once("language.inc.php");
$menuitem=5;
require_once("header.php");
require_once("account.inc.php");
switch ($_GET["action"]) {
  case "edit":
//    $usr_id = $_COOKIE["usr_id"];

    printUserRegistration(true, $usr_id);
    break;
  case "editDo":
    $usr_email=$_POST["usr_email"];
    $usr_pass=$_POST["usr_pass1"];
    $usr_icq=$_POST["usr_icq"];
    $usr_jabber=$_POST["usr_jabber"];
    $usr_ip=$_POST["usr_ip"];
    $usr_enc_id=(int)$_POST["usr_enc_id"];

    $SQL = "select usr_id, usr_name, usr_email, usr_pass, usr_icq, usr_jabber, usr_ip, enc_id
            from userinfo u 
            where usr_id=$usr_id";
    $msg = _MsgAccountChanges."\n";
          
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    $usr_name=$row[1];
    $old_usr_pass=$row[3];
    $old_usr_email=$row[2];
    $old_usr_icq=$row[4];
    $old_usr_jabber=$row[5];
    $old_usr_ip=$row[6];
    $old_usr_enc_id=$row[7];

    $changed = false;
    $SQL = "update userinfo set ";
    if ($usr_pass != "" && $old_usr_pass != $usr_pass) {
      $SQL .= "usr_pass = '$usr_pass'";
      $changed = true;
      $msg .= _MsgAccountPass." $old_usr_pass -> $usr_pass\n";
    }
    if ($old_usr_email != $usr_email) {
      if ($changed) {
        $SQL .= ", ";
      }
      $SQL .= "usr_email = '$usr_email'";
      $changed = true;
      $msg .= _MsgAccountEmail." $old_usr_email -> $usr_email\n";
    }
    if ($old_usr_icq != $usr_icq) {
      if ($changed) {
        $SQL .= ", ";
      }
      $SQL .= "usr_icq = '$usr_icq'";
      $changed = true;
      $msg .= _MsgAccountIcq." $old_usr_icq -> $usr_icq\n";
    }
    if ($old_usr_jabber != $usr_jabber) {
      if ($changed) {
        $SQL .= ", ";
      }
      $SQL .= "usr_jabber = '$usr_jabber'";
      $changed = true;
      $msg .= _MsgAccountJabber." $old_usr_jabber -> $usr_jabber\n";
    }
    if ($old_usr_ip != $usr_ip) {
      if ($changed) {
        $SQL .= ", ";
      }
      $SQL .= "usr_ip = '$usr_ip'";
      $SQL .= ", ";
      $SQL .= "usr_last_update = ".$DB->OffsetDate(0);
      $changed = true;
      $msg .= _MsgAccountIp." $old_usr_ip -> $usr_ip\n";
      $msg .= _MsgAccountChangeIpNotice."\n";
      echo "<p class=\"warning\">"._MsgAccountChangeIpNotice."</p>";
    }
    if ($old_usr_enc_id != $usr_enc_id) {
      if ($changed) {
        $SQL .= ", ";
      }
      $SQL .= "enc_id = '$usr_enc_id'";
      $changed = true;
      $rs = do_sql("select enc_codec from encoder where enc_id = $old_usr_enc_id");
      $row = $rs->FetchRow();
      $old_enc_codec = $row[0];

      $rs = do_sql("select enc_codec from encoder where enc_id = $usr_enc_id");
      $row = $rs->FetchRow();
      $enc_codec = $row[0];
      $msg .= _MsgAccountEncoder." $old_enc_codec -> $enc_codec\n";
    }
    echo "<div>\n";
    if ($changed) {
      $SQL.= " where usr_id=$usr_id";
      do_sql($SQL);
      mail($usr_email, "DVBgrab: "._MsgAccountChangesSubject, $msg, "From: "._Config_admin_email."\r\n");
      echo _MsgAccountChangesNotice."<br />\n";
      echo "<a href=\"index.php\">"._MsgGlobalBack."</a></br>\n";
    } else {
       echo _MsgAccountNoChangesNotice."<br />\n";
       echo "<a href=\"index.php\">"._MsgGlobalBack."</a></br>\n";
       echo "<a href=\"account.php?action=edit\">"._MsgGlobalRetry."</a></br>\n";
    }
    echo "</div>\n";
    break;
}
require_once("footer.php");
?>
