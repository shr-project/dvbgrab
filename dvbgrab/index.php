<?php
require("authentication.php");
require("dblib.php");

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
			alert("Vyplòte pøihla¹ovací jméno!");
			ret=false;        
			document.register.usr_name.focus();
		} else if (document.register.usr_pass1.value=='') {
			alert("Vyplòte heslo!");
			document.register.usr_pass1.focus();
			ret=false;
		} else if (document.register.usr_pass1.value!=document.register.usr_pass2.value) {
			alert("Hesla se neshodují!"); 
			document.register.usr_pass2.focus();
			ret=false;
		} else if (document.register.usr_email.value=='') {
			alert("Vyplòte email!"); 
			document.register.usr_email.focus();
			ret=false;
		} else if (!emailCheck()) {
			alert("Neplatný email!");
			document.register.usr_email.focus();
			ret=false;
		} else ret=true;
		return ret;
	}

	function checkLogin() {
		if (document.login.usr_name.value=='') {
			ret=false;        
			alert("Vyplòte pøihla¹ovací jméno!");
			document.login.usr_name.focus();
		} else
			if (document.login.usr_pass.value=='') {
				ret=false;
				alert("Vyplòte heslo!");
				document.login.usr_pass.focus();
		} else ret=true;
		return ret;
	}
//-->
</script>

<h2>Vítejte na stránkách projektu TV grab.</h2>
<p>Po pøihlá¹ení máte mo¾nost prohlí¾et televizní program na 14 dní dopøedu a
oznaèovat v nìm poøady, o které máte zájem. Po nagrabování se zájemci po¹le zpráva 
(email, icq, jabber) o jeho ulo¾ení (pravdìpodobnì unikátní URI).</p>

<p>Pøi objednání grabu je mo¾nost za¹krtnout kompresi do MPEG4, co¾ zaji¹»uje men¹í
velikost souboru pøi stejné kvalitì, ale zhor¹uje mo¾nosti úprav (vystøíhávání reklamy, 
úpravy zaèátku a konce poøadu).</p>

<p>Uchování grabovaného poøadu je zaruèeno pouze 7 dní od nahrání poøadu. Pokud si ho nestihnete
stáhnout tak mù¾e být prostì smazán, proto¾e nemáme nekoneènou diskovou kapacitu.</p>

<p class="warning">Ke grabu má pøístup pouze ten, kdo ho zadal. Toto omezení je nastaveno
schválnì. Zjednodu¹enì øeèeno, z pohledu zákona k 1 grabu mù¾e mít pøístup pouze 1 èlovìk.
</p>

<p class="warning">Nejdou stahovat soubory &gt;2GB. Bohu¾el je to tak webovy server apache s tím asi neumí
moc dobøe pracovat. Jak to vyøe¹it zatím netu¹ím, pokud si necháte grabovat pouze do TS tak to mo¾ná nepùjde stáhnout.
</p>

<p class="warning">4. 9. 2005 22:00 Opraveno/Pøidáno vyhledávání v plánovaných grabech (Velmi u¾itecné pro grabovaèe seriálù ;-))</p>
<p class="warning">4. 9. 2005 23:00 Omezeno zobrazovaní hotových grabù na posledních 100 zaznamù, to samé pro zobrazení mých grabù.</p>
<p class="warning">4. 9. 2005 00:30 Par uprav usbhid.c a zaèátek testování nìkterých stále nefunkèních multimediálních kláves na USB klavesnici (naprosto nesouvisí s DVBgrabem) ;-).</p>
<p class="warning">4. 9. 2005 01:00 Pøidána mo¾nost nechat si poslat nové vygenerované heslo.</p>
<p class="warning">4. 9. 2005 02:30 Pøidána volba "Nastavení", pro úpravy u¾ivatelských úètù.</p>
<?php

if ($usr_name != "" && !isset($_GET["msg"])) {
	echo "<p><b>Pøihlá¹ený u¾ivatel: $usr_name</b></p>";
}


switch ($_GET["msg"])  {
	case "log_fail":
		echo "<p class=\"warning\">Pøihlá¹ení se nepovedlo! Zadána ¹patná kombinace jména a hesla.</p>";
		break;
	case "log_ok":
		echo "<p class=\"info\">U¾ivatel $usr_name byl úspì¹nì pøihlá¹en.</p>";
		break;
	case "logout":
		echo "<p class=\"info\">U¾ivatel byl úspì¹nì odhlá¹en.</p>";
		break;
	case "reg_fail_ip":
		echo "<p class=\"warning\">Chyba registrace: Z této ip adresy ji¾ byl jeden u¾ivatel zaregistorván</p>";
		break;
	case "reg_fail_data":
		echo "<p class=\"warning\">Chyba registrace: Je nutné zadat jméno, heslo a email.</p>";
		break;
	case "reg_fail_email":
		echo "<p class=\"warning\">Chyba registrace: Nesprávný formát emailové adresy.</p>";
		break;
	case "reg_fail_pass":
		echo "<p class=\"warning\">Chyba registrace: Zadaná hesla se neshodují.</p>";
		break;
	case "reg_fail_name":
		echo "<p class=\"warning\">Chyba registrace: U¾ivatel s tímto pøihla¹ovacím jménem ji¾ existuje, zvolte prosím jiné!</p>";
		break;
	case "reg_ok":
		echo "<p class=\"info\">U¾ivatel $usr_name byl úspì¹nì zaregistrován.</p>";
		break;
	default:

}

// TODO vyresit zasilani hesla mailem
if (!authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
?>
Pro zpøístupnìní polo¾ek v menu vlevo se pøihla¹te:<br>
<form name="login" action="<?=$PHP_SELF."?action=login"?>" method="post" onsubmit="return checkLogin()">
<table class="registration">
<tr>
	<th class="inputCenter" colspan="2">Pøihlá¹ení<th>
</tr>
<tr>
	<td class="inputName">Pøihla¹ovací jméno:</td>
	<td><input size="20" type="text" name="usr_name"></td>
</tr>
<tr>
	<td class="inputName">Heslo:</td>
	<td><input size="20" type="password" name="usr_pass"></td>
</tr>
<tr>
	<td colspan="2"><a href="sendPass.php?action=sendPassword">Zapomìli jste své heslo?</a></td>
</tr>
<tr>
	<td class="inputCenter" colspan="2">
		<input type="submit" value="Pøihlásit se">
	</td>
</tr>
</table>
</form>

Jste tu poprvé? Vyplòte, prosím, krátkou registraci:<br>
<form name="register" action="<?=$PHP_SELF."?action=register"?>" method="post" onsubmit="return checkRegister()">
<table class="registration">
<tr>
	<th class="inputCenter" colspan="2">Registrace</th>
</tr>
<tr>
	<td class="inputName">Pøihla¹ovací jméno:</td>
	<td><input type="text" name="usr_name"></td>
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
	<td><input type="text" name="usr_email"></td>
</tr>
<tr>
	<td class="inputCenter" colspan="2"><hr></td>
</tr>
<tr>
	<td class="inputName">icq#:</td>
	<td><input type="text" name="usr_icq"></td>
</tr>
<tr>
	<td class="inputName">jabber:</td>
	<td><input type="text" name="usr_jabber"></td>
	</tr>
<tr>
	<td class="inputCenter" colspan="2">
        	<input type="submit" value="Registrovat">
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
