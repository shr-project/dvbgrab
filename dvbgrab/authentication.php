<?php

// autentizuje uzivatele
function authenticated($usr_id, $usr_pass_md5) {
	$SQL = "select usr_id from user where 
				usr_id=".(int)$usr_id." and
				md5(usr_pass)='$usr_pass_md5'";
	$rs = db_sql($SQL);
	return ($rs->recordCount() == 1);
}

// naloguje uzivatele
function login($usr_name, $usr_pass) {
	$SQL = "select usr_id from user where 
				usr_name='$usr_name' and
				usr_pass='$usr_pass'";
	$rs = db_sql($SQL);
	if ($row = $rs->FetchRow()) {
	        setcookie("usr_id", $row[0], time()+60*60*24*365*2);
		setcookie("usr_pass", md5($usr_pass), time()+60*60*24*365*2);
		return true;
	} else return false;
}

// odloguje uzivatele
function logout() {
    setcookie("usr_id","", time()-3600);
    setcookie("usr_pass","", time()-3600);
    header("Location:index.php?mes=quit");
}

function auth_failed() {
	include "header.php";
	echo "<p>U¾ivatel není pøihlá¹en. <a href=\"./\">Pøihlásit se.</a></p>\n";
	include "footer.php";
}
?>
