<?php

require_once 'Log.php';
require_once 'config.php';

$logFileConf = array('mode' => 0666, 'timeFormat' => '%X %x');
$logMailConf = array('subject' => 'DVBgrab log message', 'from' => _Config_from_email);
#$logmail = &Log::singleton('mail', _Config_error_email, 'error', $confmail);

$logdbg = &Log::singleton('file', _Config_dvbgrab_log, 'debug', $logFileConf);
$logerr = &Log::singleton('file', _Config_dvbgrab_log, 'error', $logFileConf);
$logsys  = &Log::singleton('file', _Config_dvbgrab_log.'.sys', 'sys', $logFileConf);
$logsql  = &Log::singleton('file', _Config_dvbgrab_log.'.err', 'sql', $logFileConf);
