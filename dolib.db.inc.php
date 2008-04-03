<?php

require_once("adodb/adodb.inc.php");
require_once("adodb/adodb-exceptions.inc.php");

class dbClass {
  private $DB;
  private $log;
  private $host;
  private $user;
  private $pass;
  private $dbname;
  private $dbtype;
  private $tryCount;
  private $logToFile;
  private $errorTimeout = 300;

  public function __construct($_log, $_host, $_user, $_pass, $_dbname, $_dbtype, $_tryCount, $_logToFile) {
    $this->log = $_log;
    $this->host = $_host;
    $this->user = $_user;
    $this->pass = $_pass;
    $this->dbname = $_dbname;
    $this->dbtype = $_dbtype;
    $this->tryCount = $_tryCount;
    $this->logToFile = $_logToFile;
    $this->DB = &NewADOConnection($this->dbtype);
    $this->log->log("dbClass for $this->dbname created");
  }

  public function getDB() {
    return $this->DB;
  }

  public function connect() {
    if (!isset($this->$DB)) {
      $this->log->log("ADOdbClass for $this->dbname created");
      $this->DB = &NewADOConnection($dbtype);
    } else if ($this->DB->IsConnected()) {
      $this->log->log("ADOdbClass for $this->dbname closed");
      // clean and reconnect
      $this->DB->Close();
    }
    $t = 0;
    while (!$this->DB->IsConnected() && $t < $this->tryCount) {
      $t++;
      try {
        if (!$this->DB->PConnect($this->host, $this->user, $this->pass, $this->dbname)) {
          $this->log->log("Sorry, cannot connect to database $this->dbname");
          handle_error("SQL: Sorry, cannot connect to database $this->dbname");
          sleep($this->errorTimeout);
        }
      } catch (exception $e) {
        $this->log->log("Exception during connect to database $this->dbname ".$e->getMessage());
        handle_error("SQL: Sorry, cannot connect to database $this->dbname".$e->getMessage());
        sleep($this->errorTimeout);
      }
    }
  }

  public function sql($stmt) {
    if (!isset($this->DB) || !$this->DB->IsConnected()) {
      $this->connect();
    }
    if ($this->logToFile) {
      $this->log->log("SQL:\"".$stmt."\"");
    }

    $t = 0;
    while (!isset($rs) && $t < $this->tryCount) {
      $t++;
      try {  
        $rs = $this->DB->Execute($stmt);
        if (!isset($rs)) {
          $this->log->log("SQL: {$stmt}[br]".$this->DB->ErrorMsg().": ".$this->DB->ErrorNo());
          handle_error("SQL: {$stmt}[br]".$this->DB->ErrorMsg().": ".$this->DB->ErrorNo());
          if (!$this->DB->IsConnected()) {
            connect();
          }
        }
      } catch (exception $e) {
        $this->log->log("Exception during SQL:\"".$stmt."\"\n".$e->getMessage());
        handle_error("SQL: Exception during SQL:\"".$stmt."\"\n".$e->getMessage());
        sleep($this->errorTimeout);
      }
    }
    return $rs;
  }
}
?>
