<?php
require_once("language.inc.php");
require_once("actions.php");
indexAction($_GET["action"],null,null,null);
require_once("account.inc.php");
require_once("dolib.inc.php");
require_once("header.php");

echo "<h2>"._MsgIndex."</h2>\n";
echo "<p>"._MsgIndexP1."</p>\n";
echo "<p>"._MsgIndexP3."</p>\n";
echo "<p class=\"warning\">"._MsgIndexPW1."</p>\n";
echo "<p class=\"warning\">"._MsgIndexPW3."</p>\n";

if ($usr_name != "" && !isset($_GET["msg"])) {
  echo "<p><b>"._MsgAccountLogged." $usr_name</b></p>";
}

printMsg($_GET["msg"]);

if (!authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
  echo '<div>';
  echo _MsgAcountNoLoggedNotice."<br />\n";
  printUserLogin();
  echo "<br />";
  echo _MsgAccountRegistrationTitle."<br />\n";
  printUserRegistration(false,null);
  echo '</div>';
} else {
  require_once("news.php");
}
require_once("footer.php");
?>
