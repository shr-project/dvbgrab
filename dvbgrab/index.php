<?php
require("authentication.php");
require("dblib.php");
require_once("language.inc.php");

switch ($_GET["action"]) {

	// uzivatel se chce prilogovat, pokusime se ho autentizovat
	case "login":
		if (login($_POST["usr_name"], $_POST["usr_pass"])) {
			header("Location:$PHP_SELF?msg=log_ok");
			exit;
		} else {
			header("Location:$PHP_SELF?msg=log_fail");
			exit;
		}
		break;

	// odlogovani uzivatele
	case "logout":
		logout();
		header("Location:$PHP_SELF?msg=logout");
		exit;
		break;

	// registrace noveho uzivatele
	case "register":

		// zkontrolujeme unikatnost ip adresy
		if (getenv(HTTP_X_FORWARDED_FOR)) { 
			 $usr_ip = getenv(HTTP_X_FORWARDED_FOR);
		} else { 
			$usr_ip = getenv(REMOTE_ADDR); 
		}

		// TODO dat uzivateli vedet, ze z jeho $usr_ip je zaregistrovan $usr_name
		$SQL = "select usr_name from user where usr_ip='$usr_ip'";
		$rs = db_sql($SQL);
		if ($rs->rowCount() > 0) {
			header("Location:$PHP_SELF?msg=reg_fail_ip");
			exit;
		}
		
		// zkontrolujeme, zda bylo zadano jmeno, heslo a email
		$usr_name = $_POST["usr_name"];
		$usr_pass = $_POST["usr_pass1"];
		$usr_email = $_POST["usr_email"];
		if ($usr_name == "" || $usr_pass == "" || $usr_email == "") {
			header("Location:$PHP_SELF?msg=reg_fail_data");
			exit;
		}

		// zkontrolujeme format emailove adresy
		if (!eregi("^[a-zA-Z0-9_\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $usr_email)) {
			header("Location:$PHP_SELF?msg=reg_fail_email");
			exit;
		}

		// zkontrolujeme, zda obe zadana hesla jsou totozna
		if ($usr_pass != $_POST["usr_pass2"]) {
			header("Location:$PHP_SELF?msg=reg_fail_pass");
			exit;
		}

		// zkontrolujeme, zda uzivatel daneho jmena uz neexistuje
		$SQL = "select usr_id from user where
					usr_name='$usr_name'";
		$rs = db_sql($SQL);
		if ($rs->rowCount() == 1) {
			header("Location:$PHP_SELF?msg=reg_fail_name");
			exit;
		}

		// zaregistrujeme noveho uzivatele
		$SQL = "insert into user set
					usr_name='$usr_name',
					usr_pass='$usr_pass',
					usr_email='$usr_email',
					usr_icq=".(int)$_POST["usr_icq"].",
					usr_jabber='".$_POST["usr_jabber"]."',
					usr_ip='$usr_ip'";
		db_sql($SQL);

		// a hned ho zaloguj
		login($usr_name, $usr_pass);
		header("Location:$PHP_SELF?msg=reg_ok");
		exit;
		break;

	default:
}

require("status.inc.php");
require("header.php");

$menuitem = "";
require("menu.php");
?>
<td valign="top">
<script type="text/javascript" language="JavaScript1.2">
<!--

	function emailCheck(){
		var goodEmail = document.register.usr_email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
		if (goodEmail) 
			return true;
		else 
			return false;
	}

	function checkRegister() {
		if (document.register.usr_name.value=='') {
			alert("<?echo $msgAccountValidateLogin ?>");
			ret=false;        
			document.register.usr_name.focus();
		} else if (document.register.usr_pass1.value=='') {
			alert("<?echo $msgAccountValidatePass ?>");
			document.register.usr_pass1.focus();
			ret=false;
		} else if (document.register.usr_pass1.value!=document.register.usr_pass2.value) {
			alert("<?echo $msgAccountValidatePassNoEql ?>"); 
			document.register.usr_pass2.focus();
			ret=false;
		} else if (document.register.usr_email.value=='') {
			alert("<?echo $msgAccountValidateEmail ?>"); 
			document.register.usr_email.focus();
			ret=false;
		} else if (!emailCheck()) {
			alert("<?echo $msgAccountValidateEmailFormat ?>");
			document.register.usr_email.focus();
			ret=false;
		} else ret=true;
		return ret;
	}

	function checkLogin() {
		if (document.login.usr_name.value=='') {
			ret=false;        
			alert("<?echo $msgAccountValidateLogin ?>");
			document.login.usr_name.focus();
		} else
			if (document.login.usr_pass.value=='') {
				ret=false;
				alert("<?echo $msgAccountValidatePass ?>");
				document.login.usr_pass.focus();
		} else ret=true;
		return ret;
	}
//-->
</script>

<h2><?echo $msgIndex ?></h2>
<p><?echo $msgIndexP1 ?></p>
<p><?echo $msgIndexP2 ?></p>
<p><?echo $msgIndexP3 ?></p>
<p class="warning"><?echo $msgIndexPW1 ?></p>
<p class="warning"><?echo $msgIndexPW2 ?></p>
<p class="warning"><?echo $msgIndexPW3 ?></p>
<?php

if ($usr_name != "" && !isset($_GET["msg"])) {
	echo "<p><b>$msgAccountLogged $usr_name</b></p>";
}


switch ($_GET["msg"])  {
	case "log_fail":
		echo "<p class=\"warning\">$msgIndexLogFail</p>";
		break;
	case "log_ok":
		echo "<p class=\"info\">$msgIndexUser $usr_name $msgIndexLogOk</p>";
		break;
	case "logout":
		echo "<p class=\"info\">$msgIndexLogout</p>";
		break;
	case "reg_fail_ip":
		echo "<p class=\"warning\">$msgIndexRegFailIp</p>";
		break;
	case "reg_fail_data":
		echo "<p class=\"warning\">$msgIndexRegFailData</p>";
		break;
	case "reg_fail_email":
		echo "<p class=\"warning\">$msgIndexRegFailEmail</p>";
		break;
	case "reg_fail_pass":
		echo "<p class=\"warning\">$msgIndexRegFailPass";
		break;
	case "reg_fail_name":
		echo "<p class=\"warning\">$msgIndexRegFailName</p>";
		break;
	case "reg_ok":
		echo "<p class=\"info\">$msgIndexUser $usr_name $msgIndexRegOk</p>";
		break;
	default:

}

if (!authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
?>
<?echo $msgAcountNoLoggedNotice ?><br />
<form name="login" action="<?=$PHP_SELF."?action=login"?>" method="post" onsubmit="return checkLogin()">
<table class="registration">
<tr>
	<th class="inputCenter" colspan="2"><?echo $msgAccountLoginFormTitle ?><th>
</tr>
<tr>
	<td class="inputName"><?echo $msgAccountLogin ?></td>
	<td><input size="20" type="text" name="usr_name"></td>
</tr>
<tr>
	<td class="inputName"><?echo $msgAccountPass ?></td>
	<td><input size="20" type="password" name="usr_pass"></td>
</tr>
<tr>
	<td colspan="2"><a href="sendPass.php?action=sendPassword"><?echo $msgAccountLostPass ?></a></td>
</tr>
<tr>
	<td class="inputCenter" colspan="2">
		<input type="submit" value="<?echo $msgAccountLoginButton ?>">
	</td>
</tr>
</table>
</form>

<?echo $msgAccountRegistrationTitle ?><br>
<form name="register" action="<?=$PHP_SELF."?action=register"?>" method="post" onsubmit="return checkRegister()">
<table class="registration">
<tr>
	<th class="inputCenter" colspan="2"><?echo $msgAccountRegistrationFormTitle ?></th>
</tr>
<tr>
	<td class="inputName"><?echo $msgAccountLogin ?></td>
	<td><input type="text" name="usr_name"></td>
</tr>
<tr>
	<td class="inputName"><?echo $msgAccountPass ?></td>
	<td><input type="password" name="usr_pass1"></td>
</tr>
<tr>
	<td class="inputName"><?echo $msgAccountPass2 ?></td>
	<td><input type="password" name="usr_pass2"></td>
<tr>
	<td class="inputName"><?echo $msgAccountEmail ?></td>
	<td><input type="text" name="usr_email"></td>
</tr>
<tr>
    <td colspan="2" class="warning"><?echo $msgAccountEmailWarning ?></td>
</tr>
<tr>
	<td class="inputCenter" colspan="2"><hr></td>
</tr>
<tr>
	<td class="inputName"><?echo $msgAccountIcq ?></td>
	<td><input type="text" name="usr_icq"></td>
</tr>
<tr>
	<td class="inputName"><?echo $msgAccountJabber ?></td>
	<td><input type="text" name="usr_jabber"></td>
	</tr>
<tr>
	<td class="inputCenter" colspan="2">
        	<input type="submit" value="<?echo $msgAccountRegisterButton ?>">
	</td>
</tr>
</table>
</form>

<script type="text/javascript" language="JavaScript1.2">
<!--
	document.login.usr_name.focus();
//-->
</script>
</td></tr></table>
<?php
}

require("footer.php");

// vim: noexpandtab tabstop=4
?>
