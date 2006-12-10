#!/usr/bin/php -q
<?php
require_once("config.php");
require_once("dolib.inc.php");
require_once("loggers.inc.php");

$SQL="update request set req_status='saved' 
      where req_status IN ('encoding','encoded')";
do_sql($SQL);
echo "Returning all request with state 'encoding' or 'encoded' to state 'saved'\n";
$SQL="update encoder set enc_pid=NULL"; 
do_sql($SQL);
echo "Removing all encoder pids from database.\n";
?>
