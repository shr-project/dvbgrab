<?php
require("dblib.php");
require("const.php");
require("authenticate.php");
require_once("status.inc.php");
require("config.php");
require("header.php");
require_once("language.inc.php");


$menuitem = "";
require("menu.php");

global $DB;  // connection to database
?>
<script type="text/javascript" language="JavaScript1.2">
<!--
    function emailCheck(){
        var goodEmail = document.edit.usr_email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
        if (goodEmail) 
            return true;
        else 
            return false;
    }       
    function ipCheck(){
        var goodIp = document.edit.usr_ip.value.match(/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/gi);
        if (goodIp) 
            return true;
        else 
            return false;
    }       
    function checkEdit() {
        if (document.edit.usr_name.value=='') {
            alert("<?echo $msgAccountValidateLogin ?>");
            ret=false;        
            document.edit.usr_name.focus();
        } else if (document.edit.usr_pass1.value!=document.edit.usr_pass2.value) {
            alert("<?echo $msgAccountValidatePassNoEql ?>"); 
            document.edit.usr_pass2.focus();
            ret=false;
        } else if (document.edit.usr_email.value=='') {
            alert("<?echo $msgAccountValidateEmail ?>"); 
            document.edit.usr_email.focus();
            ret=false;
        } else if (document.edit.usr_ip.value=='') {
            alert("<?echo $msgAccountValidateIp ?>"); 
            document.edit.usr_ip.focus();
            ret=false;
        } else if (!emailCheck()) {
            alert("<?echo $msgAccountValidateEmailFormat ?>");
            document.edit.usr_email.focus();
            ret=false;
        } else if (!ipCheck()) {
            alert("<?echo $msgAccountValidateIpFormat ?>");
            document.edit.usr_ip.focus();
            ret=false;
        } else ret=true;
        return ret;
    }
-->
</script>
<td valign="top">
<?
switch ($_GET["action"]) {
    case "edit":
          $SQL = "select usr_name, usr_email, usr_icq, usr_jabber, usr_ip
                         from user u 
                         where usr_id=$usr_id";
          $rs = db_sql($SQL);
          $row = $rs->FetchRow();
          $usr_name=$row[0];
          $usr_email=$row[1];
          $usr_icq=$row[2];
          $usr_jabber=$row[3];
          $usr_ip=$row[4];
       ?>
<form name="edit" action="<?=$PHP_SELF."?action=editDo"?>" method="post" onsubmit="return checkEdit()">
<table class="registration">
<tr>
    <th class="inputCenter" colspan="2"><?echo $msgAccountTitle ?></th>
</tr>   
<tr>
    <td class="inputName"><?echo $msgAccountLogin ?></td>
    <td><input type="text" readonly disabled name="usr_name" value="<?= $usr_name ?>"></td>
</tr>
<tr>    
    <td class="inputName"><?echo $msgAccountPass ?></td>
    <td><input type="password" name="usr_pass1"></td>
</tr>   
<tr>    
    <td class="inputName"><?echo $msgAccountPass2 ?></td>
    <td><input type="password" name="usr_pass2"></td>
</tr>   
<tr>
    <td class="inputName"><?echo $msgAccountEmail ?></td>
    <td><input type="text" name="usr_email" value="<?= $usr_email ?>"></td>
</tr>
<tr>
    <td colspan="2" class="warning"><?echo $msgAccountEmailWarning ?></td>
</tr>
<tr>
    <td class="inputName"><?echo $msgAccountIp ?></td>
    <td><input type="text" name="usr_ip" value="<?= $usr_ip ?>"></td>
</tr>
<tr>
    <td class="inputCenter" colspan="2"><hr></td>
</tr>
<tr>
    <td class="inputName"><?echo $msgAccountIcq ?></td>
    <td><input type="text" name="usr_icq" value="<?= $usr_icq ?>"></td>
</tr>
<tr>
    <td class="inputName"><?echo $msgAccountJabber ?></td>
    <td><input type="text" name="usr_jabber" value="<?= $usr_jabber ?>"></td>
    </tr>
<tr>
    <td class="inputCenter" colspan="2">
            <input type="submit" value="<?echo $msgAccountChangeButton ?>">
    </td>
</tr>
</table>
</form>
       <?
       break;
    case "editDo":
          $usr_email=$_POST["usr_email"];
          $usr_pass=$_POST["usr_pass1"];
          $usr_icq=$_POST["usr_icq"];
          $usr_jabber=$_POST["usr_jabber"];
          $usr_ip=$_POST["usr_ip"];

          $SQL = "select usr_id, usr_name, usr_email, usr_pass, usr_icq, usr_jabber, usr_ip
                         from user u 
                         where usr_id=$usr_id";
          $msg = $msgAccountChanges."\n";
          
          $rs = db_sql($SQL);
          $row = $rs->FetchRow();
          $usr_name=$row[1];
          $old_usr_pass=$row[3];
          $old_usr_email=$row[2];
          $old_usr_icq=$row[4];
          $old_usr_jabber=$row[5];
          $old_usr_ip=$row[6];

          $changed = false;
          $SQL = "update user set ";
          if ($usr_pass != "" && $old_usr_pass != $usr_pass) {
            $SQL .= "usr_pass = '$usr_pass'";
            $changed = true;
            $msg .= "$msgAccountPass $old_usr_pass -> $usr_pass\n";
          }
          if ($old_usr_email != $usr_email) {
            if ($changed) {
              $SQL .= ", ";
            }
            $SQL .= "usr_email = '$usr_email'";
            $changed = true;
            $msg .= "$msgAccountEmail $old_usr_email -> $usr_email\n";
          }
          if ($old_usr_icq != $usr_icq) {
            if ($changed) {
              $SQL .= ", ";
            }
            $SQL .= "usr_icq = '$usr_icq'";
            $changed = true;
            $msg .= "$msgAccountIcq $old_usr_icq -> $usr_icq\n";
          }
          if ($old_usr_jabber != $usr_jabber) {
            if ($changed) {
              $SQL .= ", ";
            }
            $SQL .= "usr_jabber = '$usr_jabber'";
            $changed = true;
            $msg .= "$msgAccountJabber $old_usr_jabber -> $usr_jabber\n";
          }
          if ($old_usr_ip != $usr_ip) {
            if ($changed) {
              $SQL .= ", ";
            }
            $SQL .= "usr_ip = '$usr_ip'";
            $changed = true;
            $msg .= "$msgAccountIp $old_usr_ip -> $usr_ip\n";
            $amsg = "$msgAccountLogin $usr_name $msgAccountChangeIp\n";
            $amsg .= "$msgAccountChangeIpText \n\n";
            $amsg .= "USR_ID=\"$usr_id\" php -f changeUsrIp.php\n\n";
			      $amsg .= "$msgAccountChangeIpText2 \n";
            mail($admin_email, "DVBgrab: $msgAccountChangeIpSubject", $amsg, "From: $admin_email\r\n");
            ?>
            <p class="warning"><? echo "$msgAccountChangeIpNotice" ?></p>
            <?
          }
          if ($changed) {
            $SQL.= " where usr_id=$usr_id";
            db_sql($SQL);
            mail($usr_email, "DVBgrab: $msgAccountChangesSubject", $msg, "From: $admin_email\r\n");
       ?>
          <center>
          <? echo "$msgAccountChangesNotice" ?><br />
          <a href="index.php"><? echo "$msgGlobalBack" ?></a></br>
          </center>
       <?
          } else {
       ?>
          <center>
          <? echo "$msgAccountNoChangesNotice" ?><br />
          <a href="index.php"><? echo "$msgGlobalBack" ?></a></br>
          <a href="account.php?action=edit"><? echo "$msgGlobalRetry" ?></a>
          </center>
       <?
          }
       break;
}
echo '</td></tr></table>';

require("footer.php");

// vim: noexpandtab tabstop=4
?>
