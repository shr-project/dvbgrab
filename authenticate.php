<?php
require_once("authentication.php");

$usr_id = (int)$_COOKIE["usr_id"];
$usr_pass = $_COOKIE["usr_pass"];

if (!authenticated($usr_id, $usr_pass)) {
  require_once("header.php");

  echo "<p>"._MsgAccountNoUser."<br /><a href=\"./\">"._MsgAccountLoginFormTitle."</a></p>\n";
  require_once("footer.php");
  exit;
}
