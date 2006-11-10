<?php

require_once 'Log.php';
require_once 'config.php';

$logFileConf = array('mode' => 0666, 'timeFormat' => '%X %x');
$logMailConf = array('subject' => 'DVBgrab log message', 'from' => $email_from);

#$logdbg = &Log::singleton('file', _Config_dvbgrab_log, 'debug', $conf);
#$logerr = &Log::singleton('file', _Config_dvbgrab_log, 'error', $conf);
#$logsql  = &Log::singleton('file', _Config_dvbgrab_log, 'sql', $conf);
#$logmail = &Log::singleton('mail', _Config_error_email, 'error', $confmail);

#$logdbg->log("DVBgrab init");
