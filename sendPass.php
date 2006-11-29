
<?php
require("authentication.php");
require_once("dolib.inc.php");
require_once("account.inc.php");
require_once("const.php");
require_once("config.php");
require_once("language.inc.php");
$menuitem = "";
require("header.php");

global $DB;  // pripojeni do databaze

function assign_rand_value($num)
{
  // accepts 1 - 36
  if ($num < 0) {
    $num = $num * -1;
  }
  if ($num > 36) {
    $num = $num % 36;
  }
  $randField = "abcdefghijklmnopqrstuvwxyz0123456789";
  return $randField[$num];
}

function get_rand_id($length) {
  if($length>0) { 
    $rand_id="";
    for($i=1; $i<=$length; $i++) {
      mt_srand((double)microtime() * 1000000);
      $num = mt_rand(1,36);
      $rand_id .= assign_rand_value($num);
    }
  }
  return $rand_id;
}

switch ($_GET["action"]) {
    case "sendPassword":
       printSendPassword();
       break;
    case "sendPasswordDo":
          $usr_name=safeUsername($_POST["usr_name"]);
          $usr_email=$_POST["usr_email"];
    
          $SQL = "select usr_id, usr_name, usr_email 
                         from userinfo u 
                         where usr_name='$usr_name' and usr_email='$usr_email'";
          $rs = do_sql($SQL);
          $rowCount = $rs->RecordCount();
          if ($rowCount != 1) { 
            echo "<div class=\"center\">";
            echo _MsgSendPassCheckFailed1." $user "._MsgSendPassCheckFailed2." $mail "._MsgSendPassCheckFailed3." <br />";
            echo "<a href=\"index.php\">"._MsgGlobalBack."</a></br>";
            echo "<a href=\"sendPass.php?action=sendPassword\">"._MsgGlobalRetry."</a>";
            echo "</div>";
          } else {
            if (_Config_auth_db_used == '1' && autenticatedExistExtern($usr_name)) {
              echo _MsgAccountPassExternAuthNoChange."\n";
              return;
            }
            $row = $rs->FetchRow();
            $usr_id = $row[0];
            $user = $row[1];
            $mail = $row[2];
            $newPass=get_rand_id(10);
            $SQL = "update userinfo set usr_pass='$newPass'
                    where usr_id=$usr_id";
            do_sql($SQL);
            $msg = _MsgSendPassEmailStart."\n";
            $msg .= "username: $user\n";
            $msg .= "email: $mail\n\n";
            $msg .= "new password: $newPass\n";
            send_mail($mail, "DVBgrab: "._MsgSendPassEmailSubject, $msg);
            echo "<div class=\"center\">";
            echo _MsgSendPassNotice1." $user "._MsgSendPassNotice2." $mail <br />";
            echo "<a href=\"index.php\">"._MsgGlobalBack."</a>";
          }
       break;
}

require("footer.php");

// vim: noexpandtab tabstop=4
?>
