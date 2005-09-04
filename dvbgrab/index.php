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
			alert("Vypl�te p�ihla�ovac� jm�no!");
			ret=false;        
			document.register.usr_name.focus();
		} else if (document.register.usr_pass1.value=='') {
			alert("Vypl�te heslo!");
			document.register.usr_pass1.focus();
			ret=false;
		} else if (document.register.usr_pass1.value!=document.register.usr_pass2.value) {
			alert("Hesla se neshoduj�!"); 
			document.register.usr_pass2.focus();
			ret=false;
		} else if (document.register.usr_email.value=='') {
			alert("Vypl�te email!"); 
			document.register.usr_email.focus();
			ret=false;
		} else if (!emailCheck()) {
			alert("Neplatn� email!");
			document.register.usr_email.focus();
			ret=false;
		} else ret=true;
		return ret;
	}

	function checkLogin() {
		if (document.login.usr_name.value=='') {
			ret=false;        
			alert("Vypl�te p�ihla�ovac� jm�no!");
			document.login.usr_name.focus();
		} else
			if (document.login.usr_pass.value=='') {
				ret=false;
				alert("Vypl�te heslo!");
				document.login.usr_pass.focus();
		} else ret=true;
		return ret;
	}
//-->
</script>

<h2>V�tejte na str�nk�ch projektu TV grab.</h2>
<p>Po p�ihl�en� m�te mo�nost prohl�et televizn� program na 14 dn� dop�edu a
ozna�ovat v n�m po�ady, o kter� m�te z�jem. Po nagrabov�n� se z�jemci po�le zpr�va 
(email, icq, jabber) o jeho ulo�en� (pravd�podobn� unik�tn� URI).</p>

<p>P�i objedn�n� grabu je mo�nost za�krtnout kompresi do MPEG4, co� zaji��uje men��
velikost souboru p�i stejn� kvalit�, ale zhor�uje mo�nosti �prav (vyst��h�v�n� reklamy, 
�pravy za��tku a konce po�adu).</p>

<p>Uchov�n� grabovan�ho po�adu je zaru�eno pouze 7 dn� od nahr�n� po�adu. Pokud si ho nestihnete
st�hnout tak m��e b�t prost� smaz�n, proto�e nem�me nekone�nou diskovou kapacitu.</p>

<p class="warning">Ke grabu m� p��stup pouze ten, kdo ho zadal. Toto omezen� je nastaveno
schv�ln�. Zjednodu�en� �e�eno, z pohledu z�kona k 1 grabu m��e m�t p��stup pouze 1 �lov�k.
</p>

<p class="warning">Nejdou stahovat soubory &gt;2GB. Bohu�el je to tak webovy server apache s t�m asi neum�
moc dob�e pracovat. Jak to vy�e�it zat�m netu��m, pokud si nech�te grabovat pouze do TS tak to mo�n� nep�jde st�hnout.
</p>

<p class="warning">4. 9. 2005 22:00 Opraveno/P�id�no vyhled�v�n� v pl�novan�ch grabech (Velmi u�itecn� pro grabova�e seri�l� ;-))</p>
<p class="warning">4. 9. 2005 23:00 Omezeno zobrazovan� hotov�ch grab� na posledn�ch 100 zaznam�, to sam� pro zobrazen� m�ch grab�.</p>
<p class="warning">4. 9. 2005 00:30 Par uprav usbhid.c a za��tek testov�n� n�kter�ch st�le nefunk�n�ch multimedi�ln�ch kl�ves na USB klavesnici (naprosto nesouvis� s DVBgrabem) ;-).</p>
<p class="warning">4. 9. 2005 01:00 P�id�na mo�nost nechat si poslat nov� vygenerovan� heslo.</p>
<p class="warning">4. 9. 2005 02:30 P�id�na volba "Nastaven�", pro �pravy u�ivatelsk�ch ��t�.</p>
<?php

if ($usr_name != "" && !isset($_GET["msg"])) {
	echo "<p><b>P�ihl�en� u�ivatel: $usr_name</b></p>";
}


switch ($_GET["msg"])  {
	case "log_fail":
		echo "<p class=\"warning\">P�ihl�en� se nepovedlo! Zad�na �patn� kombinace jm�na a hesla.</p>";
		break;
	case "log_ok":
		echo "<p class=\"info\">U�ivatel $usr_name byl �sp�n� p�ihl�en.</p>";
		break;
	case "logout":
		echo "<p class=\"info\">U�ivatel byl �sp�n� odhl�en.</p>";
		break;
	case "reg_fail_ip":
		echo "<p class=\"warning\">Chyba registrace: Z t�to ip adresy ji� byl jeden u�ivatel zaregistorv�n</p>";
		break;
	case "reg_fail_data":
		echo "<p class=\"warning\">Chyba registrace: Je nutn� zadat jm�no, heslo a email.</p>";
		break;
	case "reg_fail_email":
		echo "<p class=\"warning\">Chyba registrace: Nespr�vn� form�t emailov� adresy.</p>";
		break;
	case "reg_fail_pass":
		echo "<p class=\"warning\">Chyba registrace: Zadan� hesla se neshoduj�.</p>";
		break;
	case "reg_fail_name":
		echo "<p class=\"warning\">Chyba registrace: U�ivatel s t�mto p�ihla�ovac�m jm�nem ji� existuje, zvolte pros�m jin�!</p>";
		break;
	case "reg_ok":
		echo "<p class=\"info\">U�ivatel $usr_name byl �sp�n� zaregistrov�n.</p>";
		break;
	default:

}

// TODO vyresit zasilani hesla mailem
if (!authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
?>
Pro zp��stupn�n� polo�ek v menu vlevo se p�ihla�te:<br>
<form name="login" action="<?=$PHP_SELF."?action=login"?>" method="post" onsubmit="return checkLogin()">
<table class="registration">
<tr>
	<th class="inputCenter" colspan="2">P�ihl�en�<th>
</tr>
<tr>
	<td class="inputName">P�ihla�ovac� jm�no:</td>
	<td><input size="20" type="text" name="usr_name"></td>
</tr>
<tr>
	<td class="inputName">Heslo:</td>
	<td><input size="20" type="password" name="usr_pass"></td>
</tr>
<tr>
	<td colspan="2"><a href="sendPass.php?action=sendPassword">Zapom�li jste sv� heslo?</a></td>
</tr>
<tr>
	<td class="inputCenter" colspan="2">
		<input type="submit" value="P�ihl�sit se">
	</td>
</tr>
</table>
</form>

Jste tu poprv�? Vypl�te, pros�m, kr�tkou registraci:<br>
<form name="register" action="<?=$PHP_SELF."?action=register"?>" method="post" onsubmit="return checkRegister()">
<table class="registration">
<tr>
	<th class="inputCenter" colspan="2">Registrace</th>
</tr>
<tr>
	<td class="inputName">P�ihla�ovac� jm�no:</td>
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
