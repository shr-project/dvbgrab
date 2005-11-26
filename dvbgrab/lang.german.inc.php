<?

$msgGlobalTitle=":: DVBgrab project ::";
$msgGlobalRetry="Retry";
$msgGlobalBack="Back to home page";

$msgIndex="Welcome on DVBgrab pages.";
$msgIndexP1="After login you can browse TV program for next 10 days and mark programmes for recording. When recording is finished user receive email with download link.";
$msgIndexP2="After recording request its posible to cancel standart MPEG4 compression with link \"to TS\". Then will be about 2,5 times bigger file, but better for post processing (cut off advertisement, better cut begin and end of programme. Regrettably without compression its quite often output file bigger than 2G and cannot be downloaded, because http server apache2.";
$msgIndexP3="Recordings are saved only for short time (usually about 1 month, at least 7 days from record time). You have to download it in time, or it can be deleted, because we haven't unlimited disc capacity. (deleted record will be marked in list \"My records\")";
$msgIndexPW1="Recording can be downloaded only by those, who asks for. This restriction is because copyright. Law says, that man have to ask for himself.";
$msgIndexPW2="Recording server is always in heavy load because encoding, so delay between record and mail delivery is longer and longer. Quality of encoding is now lower, but its not enough, plese don't record every trash ;-).";
$msgIndexPW3="DVBgrab is still developed so read <a href=\"news.php\">news</a>.";

$msgIndexLogFail="Login failed! Bad username or password.";
$msgIndexUser="User";
$msgIndexLogOk="sucessfully login.";
$msgIndexLogout="User successfully logout.";
$msgIndexRegFailIp="Registration error: From this IP address was already registred another user";
$msgIndexRegFailData="Registration error: Username,password and email is required.";
$msgIndexRegFailEmail="Chyba registrace: Wrong email address format.";
$msgIndexRegFailPass="Chyba registrace: Password isn't the same.";
$msgIndexRegFailName="Chyba registrace: User with this username already exists, choose different one!";
$msgIndexRegOk="was successfuly registred.";
   
$msgConstsMonday="monday";
$msgConstsTuesday="tuesday";
$msgConstsWednesday="wednesday";
$msgConstsThursday="thursday";
$msgConstsFriday="friday";
$msgConstsSaturday="saturday";
$msgConstsSunday="sunday";

$msgConstsMondayShort="Mo";
$msgConstsTuesdayShort="Tu";
$msgConstsWednesdayShorti="We";
$msgConstsThursdayShort="Th";
$msgConstsFridayShort="Fr";
$msgConstsSaturdayShort="Sa";
$msgConstsSundayShort="Su";

$msgConstsJan="january";
$msgConstsFeb="february";
$msgConstsMar="marz";
$msgConstsApr="april";
$msgConstsMay="may";
$msgConstsJun="juni";
$msgConstsJul="july";
$msgConstsAug="august";
$msgConstsSep="september";
$msgConstsOct="october";
$msgConstsNov="november";
$msgConstsDec="december";

$msgPlanGrabLink="Download link";
$msgPlanGrabDeleted="This grab was deleted";
$msgPlanGrabLinkNone="There is no link";
$msgPlanListSchedTitle="List of scheduled records";
$msgPlanListMygrabTitle="List of my records";
$msgPlanListDoneTitle="List of finished records";
$msgPlanSchedCount="Scheduled records";
$msgPlanDoneCount="Finished records";

$msgAccountValidateLogin="Username required";
$msgAccountValidatePass="Password required";
$msgAccountValidatePassNoEql="Passwords isn't the same";
$msgAccountValidateEmail="Email address required";
$msgAccountValidateIp="IP address required";
$msgAccountValidateEmailFormat="Wrong email address";
$msgAccountValidateIpFormat="Wrong IP address, pattern xxx.xxx.xxx.xxx";

$msgAccountNoUser="No user login.";
$msgAccountLogin="Login.";
$msgAccountLogged="Logged as:";
$msgAccountNoLogged="Not logged:";
$msgAccountLogout="Logout";
$msgAccountRecordCount="Request count:";
$msgAcountNoLoggedNotice="Login to enable items on left menu:";

$msgAccountLoginFormTitle="Login";
$msgAccountLostPass="Do you forget your password?";

$msgAccountRegistrationTitle="Are you here for first time? Please fill in this short registration:";
$msgAccountRegistrationFormTitle="Registration";
$msgAccountRegisterButton="Registr";
$msgAccountChangeButton="Change";
$msgAccountLoginButton="Login";

$msgAccountTitle="Account Settings";
$msgAccountLogin="Username:";
$msgAccountPass="Password:";
$msgAccountPass2="Retype password:";
$msgAccountEmail="E-mail:";
$msgAccountEmailWarning="E-mail have to be right, to this address You will receive recording finish notice and download links!";
$msgAccountIp="Download enabled from IP:";
$msgAccountIcq="Icq#:";
$msgAccountJabber="Jabber:";

$msgAccountChanges="On DVBgrab pages was requested some changes in account settings:";
$msgAccountChangeIp="requests for dowload IP change:";
$msgAccountChangeIpText="If you decide to accept this mission, you have to run on recording server this:";
$msgAccountChangeIpText2="And as usually, if you or anybody from your team will be arrested and tyrranized, recording minister reject responsibility:";
$msgAccountChangeIpSubject="request for IP change";
$msgAccountChangeIpNotice="Request for IP address change is send to admins and will be processed and then confirm by email";
$msgAccountChangesSubject="changes in account settings";
$msgAccountChangesNotice="Requested changes was saved and information email send.";
$msgAccountNoChangesNotice="There is no change.";

$msgMenuTvProgram="TV program";
$msgMenuPlanSched="Scheduled records";
$msgMenuPlanDone="Finished records";
$msgMenuPlanMygrab="My records";
$msgMenuPlanAccount="Account";
$msgMenuEmailUs="Wrote us";
$msgMenuNews="News";
$msgMenuDocs="Documentation";
$msgMenuLogout="Loggout";

$msgSearchTitle="Search in tv program:";
$msgSearchButton="Search";
$msgSearchErrorNoInput="Error: no search query.";
$msgSearchErrorManyInput="Error: too many search words.";
$msgSearchResultsCount="Search results:";
$msgSearchResultsCountsLimit="Too many results, showing only first";

$msgProgSearchButton="Search";
$msgProgTitleDay="Show television schedule for day";
$msgProgSearch="Search in tv schedule:";
$msgProgTitle="Television schedule";
$msgProgNextDay="Next day";
$msgProgPrevDay="Previous day";
$msgStatusScheduled="scheduled for record";
$msgStatusMyScheduled="scheduled for me";
$msgStatusMyNoComprim="scheduled for me without compression";
$msgStatusDone="finished records";
$msgStatusMissed="not recorded";
$msgStatusError="error during recording";
$msgStatusProcessing="is now recording";
$msgStatusMy="my records";

$msgGrabFailQuota="ERROR: you cannot request this week, because you have achieved weekly grab quota";
$msgGrabFailTime="ERROR: this programme is gone";
$msgGrabFailExist="ERROR: record already exists";
$msgGrabFailTel="ERROR: this programme doesn't exist";
$msgGrabFailTime="ERROR: recording already finished on now in progress";
$msgGrabFailExist="ERROR: this record doesn't exists";
$msgGrabConfirmStart="Do you want programme";
$msgGrabConfirmGrabMpeg4="really encode to MPEG4?";
$msgGrabConfirmGrabTS="realy only record and let in transport stream (.ts)?";
$msgGrabConfirmGrab="really record?";
$msgGrabConfirmGrabToo="really record too?";
$msgGrabLinkStorno="cancel request";
$msgGrabLinkGrabToo="record too";
$msgGrabLinkGrab="record";
$msgGrabLinkGrabTS="to TS";
$msgGrabLinkGrabMpeg4="to MPEG4";

$msgSendPassTitlei="New temporary password";
$msgSendPassButton="Send me new random password!";
$msgSendPassCheckFailed1="User";
$msgSendPassCheckFailed2="with mail";
$msgSendPassCheckFailed3="not found";
$msgSendPassEmailStart="On Dvbgrab pages was requested new password for account:";
$msgSendPassEmailSubject="New random password request";
$msgSendPassNotice1="User password"; 
$msgSendPassNotice2="was send to email";

$msgSetupChangedOk="Settings was successfully saved";
$msgSetupWelcome="Welcome in setup interface for DVBgrab project";
$msgSetupText="All settings are stored in file config.php. This file should be overwritten by owner and after new settings is written only read by his owner. Before setup run configure.sh a then secure.sh. This config.php has to be copied to backend directory, and this directory moved to recording server.";
$msgSetupValue="Value";
$msgSetupKey="Key";
$msgSetupDbName="Name of database used for storing data";
$msgSetupDbType="Database type, thanks to ADOdb we can use: MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, Foxpro, Access, ADO, Sybase, FrontBase, DB2, SAP DB, SQLite, Netezza, LDAP, and generic ODBC, ODBTP";
$msgSetupDbHost="Computer name where is database located";
$msgSetupDbUser="Database user name";
$msgSetupDbPass="Password for database user to access our data";
$msgSetupErrorStatus="Verbosity of errors:";
$msgSetupErrorStatus0="* 0 - Every error is written to page";
$msgSetupErrorStatus1="* 1 - Every error is send to error mail";
$msgSetupErrorStatus2="* 2 - Every error is ignored. This is default";
$msgSetupErrorEmail="Email for web interface errors";
$msgSetupAdminEmail="Email for recording system errors";
$msgSetupReportEmail="Email for daily reports";
$msgSetupProxyServer="Proxy server address, if has to be set for acces tv program pages";
$msgSetupGrabHistory="How many days should be records saved for downloading";
$msgSetupTvDays="How many days ahaed should be tv program available";
$msgSetupMidnight="Which our we consider as midnight for devide programs to days";
$msgSetupHourFracItem="How many hours shoud we group together in progrem list. 24 should be integral multiple of this.";
$msgSetupGrabQuota="How many request can user set weekly";
$msgSetupDvbgrabLog="File for dvbgrab log messages from recordnig";
$msgSetupGrabDateStartShift="How many minutes should recording start before programme start";
$msgSetupGrabDateStopShift="How many minutes should recording stop after programme should end";
$msgSetupHostname="Computer name, where we record programme";
$msgSetupGrabStorage="Directory for storing recorded files";
$msgSetupGrabStorageSize="How many GB of disc space we have reserver";
$msgSetupGrabRoot="Directory for download links. Has to by accessible by http server";
$msgSetupEndText="Don't forget to run secure.sh a then copy config.php to backend directory and whole backend directory to recording server.";
$msgSetupResetButton="Restore";
$msgSetupSubmitButton="Save";

$msgNews1="Repair/Add search possibility in tv program (Mostly used for series requests ;-))";
$msgNews2="Number of showed records limited to 100.";
$msgNews3="New option for sending new random password.";
$msgNews4="New option \"Account\", for user account settings.";
$msgNews5="Anniversery 100. user registred. No price because she has full mailbox.:-P";
$msgNews6="New option to request record directly from search page.";
$msgNews7="Bug hunting day!, few new function, new texts and <a href=\"anketa.php\">inquiry</a>";


?>
