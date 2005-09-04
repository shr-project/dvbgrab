<?php
require("dblib.php");
require("const.php");
require("authenticate.php");
require_once("status.inc.php");
require("config.php");
require("header.php");

$menuitem = "";
require("menu.php");

global $DB;  // pripojeni do databaze
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
        var goodIp = document.edit.usr_ip.value.match(/^147.32.\d{1,3}.\d{1,3}$/gi);
        if (goodIp) 
            return true;
        else 
            return false;
    }       
    function checkEdit() {
        if (document.edit.usr_name.value=='') {
            alert("Vypl�te p�ihla�ovac� jm�no!");
            ret=false;        
            document.edit.usr_name.focus();
        } else if (document.edit.usr_pass1.value!=document.edit.usr_pass2.value) {
            alert("Hesla se neshoduj�!"); 
            document.edit.usr_pass2.focus();
            ret=false;
        } else if (document.edit.usr_email.value=='') {
            alert("Vypl�te email!"); 
            document.edit.usr_email.focus();
            ret=false;
        } else if (document.edit.usr_ip.value=='') {
            alert("Vypl�te ip!"); 
            document.edit.usr_ip.focus();
            ret=false;
        } else if (!emailCheck()) {
            alert("Neplatn� email!");
            document.edit.usr_email.focus();
            ret=false;
        } else if (!ipCheck()) {
            alert("Neplatn� IP adresa, mus� b�t 147.32.xxx.xxx!");
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
    <th class="inputCenter" colspan="2">Nastaven� ��tu</th>
</tr>   
<tr>
    <td class="inputName">P�ihla�ovac� jm�no:</td>
    <td><input type="text" readonly disabled name="usr_name" value="<?= $usr_name ?>"></td>
</tr>
<tr>    
    <td class="inputName">Heslo:</td>
    <td><input type="password" name="usr_pass1"></td>
</tr>   
<tr>    
    <td class="inputName">Zopakovat heslo:</td>
    <td><input type="password" name="usr_pass2"></td>
</tr>   
<tr>
    <td class="inputName">e-mail:</td>
    <td><input type="text" name="usr_email" value="<?= $usr_email ?>"></td>
</tr>
<tr>
    <td class="inputName">Stahov�n� povoleno z IP:</td>
    <td><input type="text" name="usr_ip" value="<?= $usr_ip ?>"></td>
</tr>
<tr>
    <td class="inputCenter" colspan="2"><hr></td>
</tr>
<tr>
    <td class="inputName">icq#:</td>
    <td><input type="text" name="usr_icq" value="<?= $usr_icq ?>"></td>
</tr>
<tr>
    <td class="inputName">jabber:</td>
    <td><input type="text" name="usr_jabber" value="<?= $usr_jabber ?>"></td>
    </tr>
<tr>
    <td class="inputCenter" colspan="2">
            <input type="submit" value="Nastavit">
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
          $msg = "Na str�nk�ch DVBgrabu byly vy��d�ny n�jak� zm�ny v nastaven� ��tu:\n";
          
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
            $msg .= "heslo: z $old_usr_pass na $usr_pass\n";
          }
          if ($old_usr_email != $usr_email) {
            if ($changed) {
              $SQL .= ", ";  // je-li uz nejaka zmena v sql UPDATE tak dalsi musim oddelit carkou
            }
            $SQL .= "usr_email = '$usr_email'";
            $changed = true;
            $msg .= "email: z $old_usr_email na $usr_email\n";
          }
          if ($old_usr_icq != $usr_icq) {
            if ($changed) {
              $SQL .= ", ";  // je-li uz nejaka zmena v sql UPDATE tak dalsi musim oddelit carkou
            }
            $SQL .= "usr_icq = '$usr_icq'";
            $changed = true;
            $msg .= "icq: z $old_usr_icq na $usr_icq\n";
          }
          if ($old_usr_jabber != $usr_jabber) {
            if ($changed) {
              $SQL .= ", ";  // je-li uz nejaka zmena v sql UPDATE tak dalsi musim oddelit carkou
            }
            $SQL .= "usr_jabber = '$usr_jabber'";
            $changed = true;
            $msg .= "jabber: z $old_usr_jabber na $usr_jabber\n";
          }
          if ($old_usr_ip != $usr_ip) {
            if ($changed) {
              $SQL .= ", ";  // je-li uz nejaka zmena v sql UPDATE tak dalsi musim oddelit carkou
            }
            $SQL .= "usr_ip = '$usr_ip'";
            $changed = true;
            $msg .= "ip: z $old_usr_ip na $usr_ip\n";
            $amsg = "U�ivatel $usr_name po�aduje zm�nu stahovac� IP:\n";
            $amsg .= "Pokud se rozhodne� tuto misi p��jmout, tak je t�eba na grabovac�m serveru spustit toto:\n\n";
            $amsg .= "USR_ID=\"$usr_id\" php -f changeUsrIp.php\n\n";
			      $amsg .= "A jako obvykle, pokud tebe nebo n�koho z tv�ho t�mu p�i akci zajmou a budou mu�it, ministr grabov�n� se od v�eho distancuje:\n";
            mail($admin_email, "DVBgrab: po�adavek na zm�nu IP", $amsg, "From: $admin_email\r\n");
            ?>
            <p class="warning">Zm�na IP adresy pro stahov�n� se neprojev� okam�it�, a� bude zm�na provedena bude zasl�n potvrzuj�c� email</p>
            <?
          }
          if ($changed) {
            $SQL.= " where usr_id=$usr_id";
            db_sql($SQL);
            mail($usr_email, "DVBgrab: zm�ny v nastaven� ��tu", $msg, "From: $admin_email\r\n");
       ?>
          <center>
          Po�adovan� zm�ny byly ulo�eny a odesl�n informa�n� mail.<br />
          <a href="index.php">Zp�t na hlavn� str�nku</a></br>
          </center>
       <?
          } else {
       ?>
          <center>
          Nebyla zad�na ��dn� zm�na.<br />
          <a href="index.php">Zp�t na hlavn� str�nku</a></br>
          <a href="account.php?action=edit">Znova</a>
          </center>
       <?
          }
       break;
}
echo '</td></tr></table>';

require("footer.php");

// vim: noexpandtab tabstop=4
?>
