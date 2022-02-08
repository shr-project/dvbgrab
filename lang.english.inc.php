<?

$msgGlobalTitle=":: Tvgrab project - public beta ::";
$msgGlobalRetry="Retry";
$msgGlobalBack="Back to home page";

$msgIndex="Welcome on Tvgrab pages.";
$msgIndexP1="After login you can browse TV schedule and mark shows for recording. Finished grab will be announced by email.";

$msgIndexLogFail="Login failed! Bad username or password.";
$msgIndexUser="User";
$msgIndexLogOk="sucessfully login.";
$msgIndexLogout="User successfully logout.";
$msgIndexRegFailData="Registration error: Username,password and email is required.";
$msgIndexRegFailEmail="Registration error: Wrong email address format.";
$msgIndexRegFailPass="Registration error: Password isn't the same.";
$msgIndexRegFailName="Registration error: User with this username already exists, choose different one!";
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

$msgPlanGrabDeleted="This grab was deleted";
$msgPlanListSchedTitle="List of scheduled records";
$msgPlanListMygrabTitle="List of my records";
$msgPlanListDoneTitle="List of finished records";
$msgPlanSchedCount="Scheduled records";
$msgPlanDoneCount="Finished records";
$msgPlanDoneInfo="Encoding to .avi takes few hours. Finished .avi file is reported by email.";
$msgPlanNothing="Nothing was found";

$msgAccountValidateLogin="Username required";
$msgAccountValidatePass="Password required";
$msgAccountValidatePassNoEql="Passwords isn't the same";
$msgAccountValidateEmail="Email address required";
$msgAccountValidateIp="IP address required";
$msgAccountValidateEmailFormat="Wrong email address";
$msgAccountValidateIpFormat="Wrong IP address, pattern xxx.xxx.xxx.xxx";

$msgAccountNoUser="No user login.";
$msgAccountLogged="Logged as:";
$msgAccountNoLogged="Not logged:";
$msgAccountLogout="Logout";
$msgAccountRecordCount="Request count:";
$msgAcountNoLoggedNotice="Login to enable items on left menu:";

$msgAccountLoginFormTitle="Login";
$msgAccountLostPass="Do you forget your password?";

$msgAccountRegistrationTitle="Are you here for first time? Fill in this short registration:";
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
$msgCurrentIp="Current IP:";
$msgAccountIcq="Icq#:";
$msgAccountJabber="Jabber:";
$msgAccountEncoder="Video Codec:";

$msgAccountChanges="Requested changes in account settings:";
$msgAccountChangesSubject="changes in account settings";
$msgAccountChangesNotice="Requested changes was saved and information email send.";
$msgAccountNoChangesNotice="There is no change.";
$msgAccountChangeIpNotice="The IP address change will take place after next grab.";

$msgMenuTvProgram="TV program";
$msgMenuPlanSched="Scheduled records";
$msgMenuPlanDone="Finished records";
$msgMenuPlanMygrab="My records";
$msgMenuPlanAccount="Account";
$msgMenuInfo="Info";
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
$msgStatusError="error during recording";
$msgStatusProcessing="is now recording";
$msgStatusMy="my records";

$msgGrabFailAddQuota="ERROR: you cannot request this week, because you have achieved weekly grab quota";
$msgGrabFailAddTime="ERROR: this programme is gone";
$msgGrabFailAddExist="ERROR: record already exists";
$msgGrabFailAddTel="ERROR: this programme doesn't exist";
$msgGrabFailDellTime="ERROR: recording already finished on now in progress";
$msgGrabFailDelOwner="ERROR: it is not possible to delete foreign grab";
$msgGrabFailDellExist="ERROR: this record doesn't exists";
$msgGrabConfirmStart="Do you want programme";
$msgGrabConfirmGrab="really record?";
$msgGrabLinkStorno="cancel request";
$msgGrabLinkGrab="record";
$msgGrabLinkShow="show in context";

$msgSendPassTitlei="New temporary password";
$msgSendPassButton="Send me new random password!";
$msgSendPassCheckFailed1="User";
$msgSendPassCheckFailed2="with mail";
$msgSendPassCheckFailed3="not found";
$msgSendPassEmailStart="On Tvgrab pages was requested new password for account:";
$msgSendPassEmailSubject="New random password request";
$msgSendPassNotice1="User password"; 
$msgSendPassNotice2="was send to email";

$msgSetupChangedOk="Settings was successfully saved";
$msgSetupWelcome="Welcome in setup interface for Tvgrab project";
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
$msgSetupProxyServer="Proxy server IP address, if has to be set for access tv program pages";
$msgSetupProxyServer="Proxy server port";
$msgSetupGrabHistory="How many days should be records saved for downloading";
$msgSetupTvDays="How many days ahaed should be tv program available";
$msgSetupMidnight="Which our we consider as midnight for devide programs to days";
$msgSetupHourFracItem="How many hours shoud we group together in progrem list. 24 should be integral multiple of this.";
$msgSetupGrabQuota="How many request can user set weekly";
$msgSetupDvbgrabLog="File for Tvgrab log messages from recordnig";
$msgSetupGrabDateStartShift="How many minutes should recording start before programme start";
$msgSetupGrabDateStopShift="How many minutes should recording stop after programme should end";
$msgSetupGrabStorage="Directory for storing recorded files";
$msgSetupGrabStorageSize="Minimal number of MB to keep free on disk";
$msgSetupGrabRoot="Directory to store user grabs. Should be accessible from FTP.";
$msgSetupEndText="Don't forget to run secure.sh a then copy config.php to backend directory and whole backend directory to recording server.";
$msgSetupResetButton="Restore";
$msgSetupSubmitButton="Save";

?>
