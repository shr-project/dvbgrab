<?php
require_once("authentication.php");

$usr_id = (int)$_COOKIE["usr_id"];
$usr_pass = $_COOKIE["usr_pass"];

if (!authenticated($usr_id, $usr_pass)) {
    include "header.php";
    $menuitem = "";
    require("menu.php");

    echo '<td valign="top">';
    echo "<p>$msgAccountNoUser<br><a href=\"./\">$msgAccountLoginFormTitle</a></p>\n";
    echo '</td>';
    include "footer.php";

    exit;
}
