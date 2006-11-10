
<?php
require("dblib.php");
require("const.php");
require("authentication.php");
require("config.php");
require("header.php");
require_once("language.inc.php");

$menuitem = "";
require("menu.php");

global $DB;  // pripojeni do databaze
?>
<script type="text/javascript" language="JavaScript1.2">
<!--
    function emailCheck(){
        var goodEmail = document.sendPassword.usr_email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
        if (goodEmail) 
            return true;
        else 
            return false;
    }       
        
    function checkSendPassword() {
        if (document.sendPassword.usr_name.value=='') {
            alert("<?echo $msgAccountValidateLogin ?>");
            ret=false;        
            document.sendPassword.usr_name.focus();
        } else if (document.sendPassword.usr_email.value=='') {
            alert("<?echo $msgAccountValidateEmail ?>"); 
            document.sendPassword.usr_email.focus();
            ret=false;
        } else if (!emailPassCheck()) {
            alert("<?echo $msgAccountValidateEmailFormat ?>");
            document.sendPassword.usr_email.focus();
            ret=false;
        } else ret=true;
        return ret;
    }


-->
</script>
<?
function assign_rand_value($num)
{
// accepts 1 - 36
  switch($num)
  {
    case "1":
     $rand_value = "a";
    break;
    case "2":
     $rand_value = "b";
    break;
    case "3":
     $rand_value = "c";
    break;
    case "4":
     $rand_value = "d";
    break;
    case "5":
     $rand_value = "e";
    break;
    case "6":
     $rand_value = "f";
    break;
    case "7":
     $rand_value = "g";
    break;
    case "8":
     $rand_value = "h";
    break;
    case "9":
     $rand_value = "i";
    break;
    case "10":
     $rand_value = "j";
    break;
    case "11":
     $rand_value = "k";
    break;
    case "12":
     $rand_value = "l";
    break;
    case "13":
     $rand_value = "m";
    break;
    case "14":
     $rand_value = "n";
    break;
    case "15":
     $rand_value = "o";
    break;
    case "16":
     $rand_value = "p";
    break;
    case "17":
     $rand_value = "q";
    break;
    case "18":
     $rand_value = "r";
    break;
    case "19":
     $rand_value = "s";
    break;
    case "20":
     $rand_value = "t";
    break;
    case "21":
     $rand_value = "u";
    break;
    case "22":
     $rand_value = "v";
    break;
    case "23":
     $rand_value = "w";
    break;
    case "24":
     $rand_value = "x";
    break;
    case "25":
     $rand_value = "y";
    break;
    case "26":
     $rand_value = "z";
    break;
    case "27":
     $rand_value = "0";
    break;
    case "28":
     $rand_value = "1";
    break;
    case "29":
     $rand_value = "2";
    break;
    case "30":
     $rand_value = "3";
    break;
    case "31":
     $rand_value = "4";
    break;
    case "32":
     $rand_value = "5";
    break;
    case "33":
     $rand_value = "6";
    break;
    case "34":
     $rand_value = "7";
    break;
    case "35":
     $rand_value = "8";
    break;
    case "36":
     $rand_value = "9";
    break;
  }
  return $rand_value;
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
?>
<td valign="top">
<?
switch ($_GET["action"]) {
    case "sendPassword":
       ?>
       <table class="registration">
       <form name="sendPassword" method="post" onsubmit="return checkSendPassword()" action="<?=$PHP_SELF."?action=sendPasswordDo"?>">
         <tr>
           <th class="inputCenter" colspan="2"><?echo $msgSendPassTitle ?></th>
         </tr>   
         <tr>
           <td class="inputName"><?echo $msgAccountLogin ?></td>
           <td><input type="text" name="usr_name"></td>
         </tr>
         <tr>
           <td class="inputName"><?echo $msgAccountEmail ?></td>
           <td><input type="text" name="usr_email"></td>
         </tr>
         <tr>
           <td class="inputCenter" colspan="2">
             <input type="submit" value="<?echo $msgSendPassButton ?>">
           </td>
         </tr>
       <?
       break;
    case "sendPasswordDo":
          $usr_name=$_POST["usr_name"];
          $usr_email=$_POST["usr_email"];
    
          $SQL = "select usr_id, usr_name, usr_email 
                         from user u 
                         where usr_name='$usr_name' and usr_email='$usr_email'";
          $rs = db_sql($SQL);
          $rowCount = $rs->RecordCount();
          if ($rowCount != 1) { 
       ?> 
          <center>
          <? echo "$msgSendPassCheckFailed1 $user $msgSendPassCheckFailed2 $mail $msgSendPassCheckFailed3 <br />" ?>
          <a href="index.php"><?echo $msgGlobalBack ?></a></br>
          <a href="sendPass.php?action=sendPassword"><?echo $msgGlobalRetry ?></a>
          </center>
       <?
          } else {
            $row = $rs->FetchRow();
            $usr_id = $row[0];
            $user = $row[1];
            $mail = $row[2];
            $newPass=$user."_".get_rand_id(20);
            $SQL = "update user set usr_pass='$newPass'
                    where usr_id=$usr_id";
            db_sql($SQL);
            $msg = "$msgSendPassEmailStart\n";
            $msg .= "username: $user\n";
            $msg .= "email: $mail\n\n";
            $msg .= "new password: $newPass\n";
            mail($mail, "DVBgrab: $msgSendPassEmailSubject", $msg, "From: $admin_email\r\n");
       ?>
          <center>
          <? echo "$msgSendPassNotice1 $user $msgSendPassNotice2 $mail <br />"?>
          <a href="index.php"><?echo $msgGlobalBack ?></a>
          </center>
       <?
          }
       break;
}
echo '</td></tr></table>';

require("footer.php");

// vim: noexpandtab tabstop=4
?>
