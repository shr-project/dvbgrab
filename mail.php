<?php

require_once("config.php");

/**
 * Sends a email from grab server.
 * @param to to address
 * @param subject subject
 * @param body message body encoded by iso-8859-2
 */
function send_mail($to, $subject, $body) {
    global $admin_email;

    $header = "From: $admin_email \r\n";
    $header .= "Content-Type: text/plain; charset=ISO-8859-2\r\n";
    $header .= "Mime-Version: 1.0\r\n";
    $header .= "Content-Transfer-Encoding: 8bit\r\n";

    mail($to, $subject, $body, $header);
}

?>
