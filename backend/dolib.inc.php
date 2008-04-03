<?php

require_once("config.php");
require_once("loggers.inc.php");
require_once("dolib.cmd.inc.php");
require_once("dolib.db.inc.php");

// log every SQL call or SYS call to log file
$logToFile = true;

$DB_class = new dbClass($logsql, _Config_db_host, _Config_db_user, _Config_db_pass, _Config_db_name, _Config_db_type, 10, $logToFile);
$AuthDB_class = new dbClass($logsql, _Config_auth_db_host, _Config_auth_db_user, _Config_auth_db_pass, _Config_auth_db_name, _Config_auth_db_type, 1, $logToFile);

$DB = $DB_class->getDB();
$AuthDB = $AuthDB_class->getDB();

function do_sql($sql) {
  global $DB_class;
  return $DB_class->sql($sql);
}

function do_extern_sql($sql) {
  global $AuthDB_class;
  return $AuthDB_class->sql($sql);
}

?>
