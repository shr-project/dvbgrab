<?php
// New user registration
require_once("authentication.php");
require_once("dblib.php");


$main_page="index.php";
$usr_ip = getenv(REMOTE_ADDR);

// zkontrolujeme, zda bylo zadano jmeno, heslo a email
$usr_name = safeUsername($_POST["usr_name"]);
$usr_pass = $_POST["usr_pass1"];
$usr_email = $_POST["usr_email"];
if ($usr_name == "" || $usr_pass == "" || $usr_email == "") {
        header("Location:$main_page?msg=reg_fail_data");
        exit;
}

// zkontrolujeme format emailove adresy
if (!eregi("^[a-zA-Z0-9_\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $usr_email)) {
        header("Location:$main_page?msg=reg_fail_email");
        exit;
}

// zkontrolujeme, zda obe zadana hesla jsou totozna
if ($usr_pass != $_POST["usr_pass2"]) {
        header("Location:$main_page?msg=reg_fail_pass");
        exit;
}

// zkontrolujeme, zda uzivatel daneho jmena uz neexistuje
$SQL = "select usr_id from user where
                        usr_name='$usr_name'";
$rs = db_sql($SQL);
if ($rs->rowCount() == 1) {
        header("Location:$main_page?msg=reg_fail_name");
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
header("Location:$main_page?msg=reg_ok");
exit;
?>
