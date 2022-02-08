<?php

require_once("config.php");
require_once("mail.php");

function logDebug($text) {
    echo "DEBUG: ".date("Y-m-d G:i")." $text\n";
}

function logInfo($text) {
    echo "INFO: ".date("Y-m-d G:i")." $text\n";
}

function logError($text) {
    global $error_email;

    $msg = "ERROR: ".date("Y-m-d G:i")." $text\n";
    echo $msg;
    send_mail($error_email, "Tvgrab ERROR", $msg);
}

?>
