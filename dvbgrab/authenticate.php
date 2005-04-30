<?php
require("authentication.php");

$usr_id = (int)$_COOKIE["usr_id"];
$usr_pass = $_COOKIE["usr_pass"];

if (!authenticated($usr_id, $usr_pass)) {
	auth_failed();
	exit;
}
