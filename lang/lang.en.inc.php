<?

define("_MsgGlobalTitle",":: DVBgrab project ::");
define("_MsgGlobalRetry","Retry");
define("_MsgGlobalBack","Back to home page");

define("_MsgIndex","Welcome on DVBgrab pages.");
define("_MsgIndexP1","After login you can browse TV program for next 10 days and mark programmes for recording. When recording is finished user receives email with download link.");
define("_MsgIndexP2","After recording request its posible to cancel standart MPEG4 compression with link \"to TS\". Then will be about 2,5 times bigger file, but better for post processing (cut off advertisement, better cut begin and end of programme. Regrettably without compression its quite often output file bigger than 2G and cannot be downloaded, because http server apache2.");
define("_MsgIndexP3","Recordings are saved only for short time (usually about 1 month, at least 7 days from record time). You have to download it in time, or it can be deleted, because we haven't unlimited disc capacity. (deleted record will be marked in list \"My records\")");
define("_MsgIndexPW1","Recording can be downloaded only by those, who asks for. This restriction is because copyright. Law says, that man have to ask for himself.");
define("_MsgIndexPW2","Recording server is always in heavy load because encoding, so delay between record and mail delivery is longer and longer. Quality of encoding is now lower, but its not enough, plese don't record every trash ;-).");
define("_MsgIndexPW3","DVBgrab is still developed so read <a href=\"news.php\">news</a>.");

define("_MsgIndexLogFail","Login failed! Bad username or password.");
define("_MsgIndexUser","User");
define("_MsgIndexLogOk","sucessfully login.");
define("_MsgIndexLogout","User successfully logout.");
define("_MsgIndexRegFailIp","Registration error: From this IP address was already registred another user");
define("_MsgIndexRegFailData","Registration error: Username,password and email is required.");
define("_MsgIndexRegFailEmail","Registration error: Wrong email address format.");
define("_MsgIndexRegFailPass","Registration error: Password isn't the same.");
define("_MsgIndexRegFailName","Registration error: User with this username already exists, choose different one!");
define("_MsgIndexRegOk","was successfuly registred.");
   
define("_MsgConstsMonday","monday");
define("_MsgConstsTuesday","tuesday");
define("_MsgConstsWednesday","wednesday");
define("_MsgConstsThursday","thursday");
define("_MsgConstsFriday","friday");
define("_MsgConstsSaturday","saturday");
define("_MsgConstsSunday","sunday");

define("_MsgConstsMondayShort","Mo");
define("_MsgConstsTuesdayShort","Tu");
define("_MsgConstsWednesdayShorti","We");
define("_MsgConstsThursdayShort","Th");
define("_MsgConstsFridayShort","Fr");
define("_MsgConstsSaturdayShort","Sa");
define("_MsgConstsSundayShort","Su");

define("_MsgConstsJan","january");
define("_MsgConstsFeb","february");
define("_MsgConstsMar","march");
define("_MsgConstsApr","april");
define("_MsgConstsMay","may");
define("_MsgConstsJun","june");
define("_MsgConstsJul","july");
define("_MsgConstsAug","august");
define("_MsgConstsSep","september");
define("_MsgConstsOct","october");
define("_MsgConstsNov","november");
define("_MsgConstsDec","december");

define("_MsgPlanGrabLink","Download link");
define("_MsgPlanGrabDeleted","This grab was deleted");
define("_MsgPlanGrabLinkNone","There is no link");
define("_MsgPlanListSchedTitle","List of scheduled records");
define("_MsgPlanListMygrabTitle","List of my records");
define("_MsgPlanListDoneTitle","List of finished records");
define("_MsgPlanSchedCount","Scheduled records");
define("_MsgPlanDoneCount","Finished records");
define("_MsgPlanNothing","Nothing was found");

define("_MsgJsonLoading","Loading record details");
define("_MsgJsonTelName","Tv programme");
define("_MsgJsonTelSeries","Serie");
define("_MsgJsonTelEpisode","Episode");
define("_MsgJsonTelPart","Part");
define("_MsgJsonChnName","Tv channel");
define("_MsgJsonGrbName","Record name");
define("_MsgJsonTelDateStart","Programme start");
define("_MsgJsonTelDateEnd","Programme stop");
define("_MsgJsonGrbDateStart","Record start");
define("_MsgJsonGrbDateEnd","Record stop");
define("_MsgJsonReqOutput","Name");
define("_MsgJsonReqOutputMd5","MD5");
define("_MsgJsonReqOutputEnc","Codec");
define("_MsgJsonReqOutputEnc","Size");

define("_MsgAccountValidateLogin","Username required");
define("_MsgAccountValidatePass","Password required");
define("_MsgAccountValidatePassNoEql","Passwords isn't the same");
define("_MsgAccountValidateEmail","Email address required");
define("_MsgAccountValidateIp","IP address required");
define("_MsgAccountValidateEmailFormat","Wrong email address");
define("_MsgAccountValidateIpFormat","Wrong IP address, pattern xxx.xxx.xxx.xxx");

define("_MsgAccountNoUser","No user login.");
define("_MsgAccountLogged","Logged as:");
define("_MsgAccountNoLogged","Not logged:");
define("_MsgAccountLogout","Logout");
define("_MsgAccountRecordCount","Request count:");
define("_MsgAcountNoLoggedNotice","Login to enable items on left menu:");

define("_MsgAccountLoginFormTitle","Login");
define("_MsgAccountLostPass","Forget your password?");

define("_MsgAccountRegistrationTitle","Are you here for first time? Please fill in this short registration:");
define("_MsgAccountRegistrationFormTitle","Registration");
define("_MsgAccountRegisterButton","Registr");
define("_MsgAccountChangeButton","Change");
define("_MsgAccountLoginButton","Login");

define("_MsgAccountChangeFormTitle","Account Settings");
define("_MsgAccountLogin","Username:");
define("_MsgAccountUsername","Username:");
define("_MsgAccountPass","Password:");
define("_MsgAccountPass2","Retype password:");
define("_MsgAccountEmail","E-mail:");
define("_MsgAccountEmailWarning","E-mail has to be valid, to this address you will receive recording finish notice and download links!");
define("_MsgAccountIp","IP for download:");
define("_MsgAccountIcq","Icq#:");
define("_MsgAccountJabber","Jabber:");
define("_MsgAccountEncoder","Video Codec:");

define("_MsgAccountChanges","On DVBgrab pages was requested some changes in account settings:");
define("_MsgAccountChangeIp","requests for dowload IP change:");
define("_MsgAccountChangeIpSubject","request for IP change");
define("_MsgAccountChangeIpNotice","Request for IP address change is send to admins and will be processed and then confirmed by email");
define("_MsgAccountChangesSubject","changes in account settings");
define("_MsgAccountChangesNotice","Requested changes was saved and information email send.");
define("_MsgAccountNoChangesNotice","There is no change.");

define("_MsgMenuTvProgram","TV program");
define("_MsgMenuPlanSched","Scheduled records");
define("_MsgMenuPlanDone","Finished records");
define("_MsgMenuPlanMygrab","My records");
define("_MsgMenuPlanAccount","Account");
define("_MsgMenuEmailUs","Write us");
define("_MsgMenuNews","News");
define("_MsgMenuDocs","Documentation");
define("_MsgMenuLogout","Loggout");

define("_MsgSearchTitle","Search in tv program:");
define("_MsgSearchButton","Search");
define("_MsgSearchErrorNoInput","Error: no search query.");
define("_MsgSearchErrorManyInput","Error: too many search words.");
define("_MsgSearchResultsCount","Search results:");
define("_MsgSearchResultsCountsLimit","Too many results, showing only first");

define("_MsgProgTitleDay","Show television schedule for day");
define("_MsgProgShowButton","Show");
define("_MsgProgSearchButton","Search");
define("_MsgProgSearch","Search in tv schedule:");
define("_MsgProgTitle","Television schedule");
define("_MsgProgNextDay","Next day");
define("_MsgProgPrevDay","Previous day");
define("_MsgProgNotAvailable","Schedule isn't available!");
define("_MsgProgPremiere","/P/");
define("_MsgProgLastChance","/D/");
define("_MsgProgPreviouslySchown","/R/");
define("_MsgProgNew","/N/");

define("_MsgStatusOthers","Others");
define("_MsgStatusName","Status");
define("_MsgStatusMy","My");
define("_MsgStatusScheduled","Scheduled");
define("_MsgStatusSaving","Recording");
define("_MsgStatusSaved","Recorded");
define("_MsgStatusEncoding","Encoding");
define("_MsgStatusEncoded","Encoded");
define("_MsgStatusDone","Ready");
define("_MsgStatusDeleted","Deleted");
define("_MsgStatusMissed","Missed");
define("_MsgStatusError","Failed");
define("_MsgStatusUndefined","Undefined");

define("_MsgGrabFailAddQuota","ERROR: you cannot request this week, because you have achieved weekly grab quota");
define("_MsgGrabFailAddTime","ERROR: this programme is gone");
define("_MsgGrabFailAddExist","ERROR: record already exists");
define("_MsgGrabFailAddTel","ERROR: this programme doesn't exist");
define("_MsgGrabFailDelTime","ERROR: recording already finished on now in progress");
define("_MsgGrabFailDelOwner","ERROR: it is not possible to delete foreign grab");
define("_MsgGrabFailDelExist","ERROR: this record doesn't exists");
define("_MsgGrabConfirmStart","Do you want programme");
define("_MsgGrabConfirmGrab","really record?");
define("_MsgGrabConfirmGrabToo","really record too?");
define("_MsgGrabLinkStorno","cancel request");
define("_MsgGrabLinkGrabToo","record too");
define("_MsgGrabLinkGrab","record");
define("_MsgGrabLinkShow","show in context");
define("_MsgGrabAddOk","record scheduled");

define("_MsgSendPassTitle","New temporary password");
define("_MsgSendPassButton","Send me new random password!");
define("_MsgSendPassCheckFailed1","User");
define("_MsgSendPassCheckFailed2","with mail");
define("_MsgSendPassCheckFailed3","not found");
define("_MsgSendPassEmailStart","On Dvbgrab pages was requested new password for account:");
define("_MsgSendPassEmailSubject","New random password request");
define("_MsgSendPassNotice1","User password");
define("_MsgSendPassNotice2","was send to email");

define("_MsgSetupChangedOk","Settings in config.php was successfully saved");
define("_MsgSetupCronList","Put following lines into cron daemon configuration (crontab -e)");
define("_MsgSetupWelcome","Welcome in setup interface for DVBgrab project");
define("_MsgSetupText","All settings are stored in file config.php. This file should be overwritten by owner and after new settings is written only read by his owner. Before setup run configure.sh a then secure.sh. This config.php has to be copied to backend directory, and this directory moved to recording server.");
define("_MsgSetupValue","Value");
define("_MsgSetupKey","Key");
define("_MsgSetupDbName","Name of database used for storing data");
define("_MsgSetupDbType","Database type, thanks to ADOdb we can use: MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, Foxpro, Access, ADO, Sybase, FrontBase, DB2, SAP DB, SQLite, Netezza, LDAP, and generic ODBC, ODBTP");
define("_MsgSetupDbHost","Computer name where is database located");
define("_MsgSetupDbUser","Database user name");
define("_MsgSetupDbPass","Password for database user to access our data");
define("_MsgSetupErrorStatus","Verbosity of errors:");
define("_MsgSetupErrorStatus0","* 0 - Every error is written to page");
define("_MsgSetupErrorStatus1","* 1 - Every error is send to error mail");
define("_MsgSetupErrorStatus2","* 2 - Every error is ignored. This is default");
define("_MsgSetupErrorEmail","Email for web interface errors");
define("_MsgSetupAdminEmail","Email for recording system errors");
define("_MsgSetupReportEmail","Email for daily reports");
define("_MsgSetupProxyServer","Proxy server IP address, if has to be set for access tv program pages");
define("_MsgSetupProxyPort","Proxy server port");
define("_MsgSetupGrabHistory","How many days should be records saved for downloading");
define("_MsgSetupTvDays","How many days ahaed should be tv program available");
define("_MsgSetupMidnight","Which our we consider as midnight for devide programs to days");
define("_MsgSetupHourFracItem","How many hours shoud we group together in progrem list. 24 should be integral multiple of this.");
define("_MsgSetupGrabQuota","How many request can user set weekly");
define("_MsgSetupDvbgrabLog","File for dvbgrab log messages from recordnig");
define("_MsgSetupGrabDateStartShift","How many minutes should recording start before programme start");
define("_MsgSetupGrabDateStopShift","How many minutes should recording stop after programme should end");
define("_MsgSetupHostname","Computer name, where we record programme");
define("_MsgSetupGrabStorage","Directory for storing recorded files");
define("_MsgSetupGrabStorageSize","How many GB of disc space we have reserver");
define("_MsgSetupGrabRoot","Directory for download links. Has to by accessible by http server");
define("_MsgSetupEndText","Don't forget to run secure.sh a then copy config.php to backend directory and whole backend directory to recording server.");
define("_MsgSetupResetButton","Restore");
define("_MsgSetupSubmitButton","Save");
define("_MsgSetupTvgDesc","Tv grabbers");
define("_MsgSetupTvgName","Name");
define("_MsgSetupTvgEnabled","Enabled?");
define("_MsgSetupTvgRunAt","Running time for crontab");
define("_MsgSetupTvgRun","Run command");
define("_MsgSetupTvgNew","New one");
define("_MsgSetupTvgFailed","Creating new tv grabber failed, probably because is not complete.");
define("_MsgSetupRecordTimeAfterLast","How long we should record if next programme and stop_time isn't known [s]");
define("_MsgSetupGrabStorageMinSize","Minimal disk space, when we should try removing older records from grab storage");
define("_MsgSetupGrabBackendLang","Language used in backend scripts (cs,en,fr,..)");
define("_MsgSetupBackendStripDiacritics","1 if we should try to strip diacritics and use programme name in record filename, 0 if tel_id should be used");
define("_MsgSetupUserInactivityLimit","How many days after last login/grab can be user account removed");

define("_MsgBackendGrabError","recording failed");
define("_MsgBackendGrabErrorSub","DVBgrab: Recording failed");
define("_MsgBackendEncodeError","encoding failed");
define("_MsgBackendEncodeErrorSub","DVBgrab: Encoding failed");
define("_MsgBackendSuccess","ready for download");
define("_MsgBackendSuccessSub","DVBgrab: Successfull grab");
define("_MsgBackendGrabList","List of grabs in");
define("_MsgBackendAccountCleaned","User account was deleted. Number of inactive days before account removal:");
define("_MsgBackendAccountCleanedSub","User account was deleted");

define("_MsgXmlTvFormatErrorNoChn","No channel found");
define("_MsgXmlTvFormatErrorManyChn","Many channels match given xmltv channel id");
define("_MsgXmlTvFormatErrorNoneChn","No channel spec in programme row");
define("_MsgXmlTvFormatErrorNoDateStart","No television start in programme row");
define("_MsgXmlTvFormatErrorData","Character data without matching xml tag");
define("_MsgXmlTvFormatErrorNotAll","Not found all required elements");
define("_MsgXmlTvIgnored","Ignored, duplicity");
define("_MsgXmlTvInserted","Inserted");
define("_MsgXmlTvUpdated","Updated");
define("_MsgXmlTvSuccess;","Tv schedule successfully updated");
define("_MsgXmlTvFailed;","Tv schedule updated with errors");

define("_MsgNews1","Repair/Add search possibility in tv program (Mostly used for series requests ;-))");
define("_MsgNews2","Number of showed records limited to 100.");
define("_MsgNews3","New option for sending new random password.");
define("_MsgNews4","New option \"Account\", for user account settings.");
define("_MsgNews5","Anniversery 100. user registred. No price because she has full mailbox.:-P");
define("_MsgNews6","New option to request record directly from search page.");
define("_MsgNews7","Bug hunting day!, few new function, new texts and <a href=\"anketa.php\">inquiry</a>");


?>
