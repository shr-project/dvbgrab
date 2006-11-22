<?php
require_once("authentication.php");
require_once("language.inc.php");
require_once("dolib.inc.php");

function getUserIp() {
  // zkontrolujeme unikatnost ip adresy
  if (getenv(HTTP_X_FORWARDED_FOR)) {
    $usr_ip = getenv(HTTP_X_FORWARDED_FOR);
  } else {
    $usr_ip = getenv(REMOTE_ADDR);
  }
  return $usr_ip;
}

function printUserRegistration($update,$usr_id) {
  global $PHP_SELF;
?>
<script type="text/javascript">
<!--
  function emailCheck(){
    var frm = getDocumentById('register');
    var goodEmail = frm.usr_email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
    if (goodEmail) 
      return true;
    else 
      return false;
  }     
  function ipCheck(){
    var frm = getDocumentById('register');
    var goodIp = frm.usr_ip.value.match(/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/gi);
    if (goodIp) 
      return true;
    else 
      return false;
  }     
  function checkRegister() {
    var frm = getDocumentById('register');
    if (frm.usr_name.value=='') {
      alert("<?php echo _MsgAccountValidateLogin ?>");
      ret=false;    
      frm.usr_name.focus();
<?php if (!$update) { ?>
    } else if (frm.usr_pass1.value=='') {
      alert("<?php echo _MsgAccountValidatePass ?>");
      frm.usr_pass1.focus();
      ret=false;
<?php } ?>
    } else if (frm.usr_pass1.value!=frm.usr_pass2.value) {
      alert("<?php echo _MsgAccountValidatePassNoEql ?>"); 
      frm.usr_pass2.focus();
      ret=false;
    } else if (frm.usr_email.value=='') {
      alert("<?php echo _MsgAccountValidateEmail ?>"); 
      frm.usr_email.focus();
      ret=false;
    } else if (frm.usr_ip.value=='') {
      alert("<?php echo _MsgAccountValidateIp ?>"); 
      frm.usr_ip.focus();
      ret=false;
    } else if (!emailCheck()) {
      alert("<?php echo _MsgAccountValidateEmailFormat ?>");
      frm.usr_email.focus();
      ret=false;
    } else if (!ipCheck()) {
      alert("<?php echo _MsgAccountValidateIpFormat ?>");
      frm.usr_ip.focus();
      ret=false;
    } else ret=true;
    return ret;
  }
-->
</script>
<?php
  if ($update) {
    $SQL = "select u.usr_name, u.usr_email, u.usr_icq, u.usr_jabber, u.usr_ip, e.enc_id, e.enc_codec
            from usergrb u, encoder e
            where e.enc_id = u.enc_id and u.usr_id=$usr_id";
    $rs = do_sql($SQL);
    $row = $rs->FetchRow();
    $usr_name=$row[0];
    $usr_email=$row[1];
    $usr_icq=$row[2];
    $usr_jabber=$row[3];
    $usr_ip=$row[4];
    $usr_enc_id=$row[5];
    $usr_enc_codec=$row[6];
  } else {
    $usr_name=""; $usr_email="";$usr_icq="";$usr_jabber="";$usr_ip=getUserIp();$usr_enc_id=1;$usr_enc_codec="";
  }
?>
<form id="register" action="<?=$PHP_SELF."?action=editDo"?>" method="post" onsubmit="return checkRegister()">
<table class="registration">
<tr>
  <th class="input center" colspan="2">
    <?php if ($update) {
            echo _MsgAccountChangeFormTitle;
          } else {
            echo _MsgAccountRegistrationFormTitle;
          }
    ?>
  </th>
</tr>   
<tr>
  <td class="key"><?php echo _MsgAccountLogin ?></td>
  <td class="value"><input size="30" type="text" <?php if ($update) { echo " disabled=\"disabled\" "; } ?> name="usr_name" value="<?= $usr_name ?>"/></td>
</tr>
<tr>    
  <td class="key"><?php echo _MsgAccountPass ?></td>
  <td class="value"><input size="30" type="password" name="usr_pass1"/></td>
</tr>   
<tr>    
  <td class="key"><?php echo _MsgAccountPass2 ?></td>
  <td class="value"><input size="30" type="password" name="usr_pass2"/></td>
</tr>   
<tr>
  <td class="key"><?php echo _MsgAccountEmail ?></td>
  <td class="value"><input size="30" type="text" name="usr_email" value="<?= $usr_email ?>"/></td>
</tr>
<tr>
  <td colspan="2" class="warning"><?php echo _MsgAccountEmailWarning ?></td>
</tr>
<tr>
  <td class="key"><?php echo _MsgAccountIp ?></td>
  <td class="value"><input size="30" type="text" <?php if (!$update) { echo " disabled=\"disabled\" "; } ?> name="usr_ip" value="<?= $usr_ip ?>"/></td>
</tr>
<tr>
  <td class="key"><?php echo _MsgAccountEncoder ?></td>
  <td class="value"><select name="usr_enc_id">
<?php
  $SQL = "select enc_id, enc_codec from encoder order by enc_codec";
  $rs = do_sql($SQL);
  while ($row = $rs->FetchRow()) {
    echo '<option value="'.$row[0].'"';
    if ($row[0] == $usr_enc_id) {
      echo " selected=\"selected\"";
    }
    echo ">$row[1]</option>\n";
  }
?>
  </select></td>
</tr>
<tr>
  <td class="center" colspan="2"><hr /></td>
</tr>
<tr>
  <td class="key"><?php echo _MsgAccountIcq ?></td>
  <td class="value"><input size="30" type="text" name="usr_icq" value="<?= $usr_icq ?>"/></td>
</tr>
<tr>
  <td class="key"><?php echo _MsgAccountJabber ?></td>
  <td class="value"><input size="30" type="text" name="usr_jabber" value="<?= $usr_jabber ?>"/></td>
</tr>
<tr>
  <td class="center" colspan="2">
    <input size="30" type="submit" value="<?php if ($update) { 
                                        echo _MsgAccountChangeButton;
                                      } else {
                                        echo _MsgAccountRegisterButton;
                                      }
                                ?>"/>
  </td>
</tr>
</table>
</form>
<?php 
} 
function printUserLogin() {
  global $PHP_SELF;
?>
<script type="text/javascript">
<!--
  function checkLogin() {
    var frm = getDocumentById('login');
    if (frm.usr_name.value=='') {
      ret=false;
      alert("<?php echo _MsgAccountValidateLogin ?>");
      frm.usr_name.focus();
    } else {
      if (frm.usr_pass.value=='') {
        ret=false;
        alert("<?php echo _MsgAccountValidatePass ?>");
        frm.usr_pass.focus();
      } else {
        ret=true;
        return ret;
      }
    }
  }
//-->
</script>

<form id="login" action="<?=$PHP_SELF."?action=login"?>" method="post" onsubmit="return checkLogin()">
<table class="registration">
<tr>
  <th class="input center" colspan="2"><?php echo _MsgAccountLoginFormTitle ?></th>
</tr>
<tr>
  <td class="key"><?php echo _MsgAccountLogin ?></td>
  <td class="value"><input size="30" type="text" name="usr_name"/></td>
</tr>
<tr>
  <td class="key"><?php echo _MsgAccountPass ?></td>
  <td class="value"><input size="30" type="password" name="usr_pass"/></td>
</tr>
<tr>
  <td colspan="2"><a href="sendPass.php?action=sendPassword"><?php echo _MsgAccountLostPass ?></a></td>
</tr>
<tr>
  <td class="input center" colspan="2">
    <input size="30" type="submit" value="<?php echo _MsgAccountLoginButton ?>"/>
  </td>
</tr>
</table>
</form>
<?php

}

function printSendPassword() {
  global $PHP_SELF;
?>
<script type="text/javascript">
<!--
  function emailCheck(){
    var frm = getDocumentById('sendPassword');
    var goodEmail = frm.usr_email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(
\..{2,2}))$)\b/gi);
    if (goodEmail)
      return true;
    else
      return false;
  }

  function checkSendPassword() {
    var frm = getDocumentById('sendPassword');
    if (frm.usr_name.value=='') {
      alert("<?echo _MsgAccountValidateLogin ?>");
      ret=false;
      frm.usr_name.focus();
    } else if (frm.usr_email.value=='') {
      alert("<?echo _MsgAccountValidateEmail ?>");
      frm.usr_email.focus();
      ret=false;
    } else if (!emailPassCheck()) {
      alert("<?echo _MsgAccountValidateEmailFormat ?>");
      frm.usr_email.focus();
      ret=false;
    } else {
      ret=true;
    }
    return ret;
  }
-->
</script>

<form id="sendPassword" action="<?=$PHP_SELF."?action=sendPasswordDo"?>" method="post" onsubmit="return checkSendPassword()">
<table class="registration">
  <tr>
    <th class="input center" colspan="2"><?echo _MsgSendPassTitle ?></th>
  </tr>
  <tr>
    <td class="key"><?echo _MsgAccountLogin ?></td>
    <td class="value"><input size="30" type="text" name="usr_name" /></td>
  </tr>
  <tr>
    <td class="key"><?echo _MsgAccountEmail ?></td>
    <td class="value"><input size="30" type="text" name="usr_email" /></td>
  </tr>
  <tr>
    <td class="input center" colspan="2">
      <input size="30" type="submit" value="<?echo _MsgSendPassButton ?>" />
    </td>
  </tr>
</table>
</form>
<?php
}
?>
