<?php
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">

<?php
require_once("language.inc.php");
require_once("view.inc.php");
require_once("status.inc.php");
?>

<head>
  <meta name="description" content="Application for scheduled DVB stream recording" />
  <meta name="keywords" content="DVB,grab,record,stream" />
  <meta name="author" content="all: Martin Jansa; Martin.Jansa@mk.cvut.cz" />
  <meta name="copyright" content="Martin Jansa 2006" />
  <meta name="generator" content="Vim editor" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="content-language" content="cs" />
  <meta http-equiv="content-style-type" content="text/css" />
  <meta name="resource-type" content="document" />
  <link rel="stylesheet" type="text/css" href="css/dvbgrab.css" />
  <title><? echo _MsgGlobalTitle ?></title>
</head>
