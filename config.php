<?
/*    
     WARNING: dont change this file without good reason
     You have to let define clause formating untouched.
     setup.php will apply new setting by replacing ^define("variable","old_value"); no spaces!
*/

/* 
     supported db_type: all from adodb 
     (MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, 
      Foxpro, Access, ADO, Sybase, FrontBase, DB2, SAP DB, 
      SQLite, Netezza, LDAP, and generic ODBC, ODBTP)
*/
define("_Config_db_name","dvbgrab");
define("_Config_db_type","postgres");
define("_Config_db_host","localhost");
define("_Config_db_user","dvbgrab");
define("_Config_db_pass","dvbgrab");

/*
     External authentication
     Use external database for user authentication.
*/
define("_Config_auth_db_used","0");
define("_Config_auth_db_used_only","1");
define("_Config_auth_db_name","users");
define("_Config_auth_db_type","postgres");
define("_Config_auth_db_host","localhost");
define("_Config_auth_db_user","dvbgrab");
define("_Config_auth_db_pass","dvbgrab");
define("_Config_auth_db_select","select * from users where person='dvbgrab_username' and password='dvbgrab_password'");
define("_Config_auth_db_user_select","select * from users where person='dvbgrab_username'");

/*
    * 0 - Any error is written into the page
    * 1 - Any error is reported with a message to error_email
    * 2 - Any error is ignored. This is the default.
*/
define("_Config_error_status","0");
define("_Config_error_email","admin@somewhere");

// email spravce dvbgrabu
define("_Config_admin_email","admin@somewhere");

// email na zasilani denni reportu
define("_Config_report_email","admin@somewhere");

// email bude zasilan z
define("_Config_from_email","admin@somewhere");

// set this value, if proxy server is needed for downloading tv program
define("_Config_proxy_server","");
define("_Config_proxy_port","3128");

// kolik dnu zpatky budou uchovavany graby na disku
define("_Config_grab_history","3");

// na max kolik dnu dopredu se bude zobrazovat program
define("_Config_tv_days","14");

// od ktere hodiny se jedna o novy den (tj. pulnoc nebereme jako pocatek noveho dne)
define("_Config_midnight","05");

// rozdeleni dne na casove useky po _Config_hour_frac_item hodinach 
//(24%_Config_hour_frac_item=0 tj. 24 hodin je delitelne _Config_hour_frac_item - tohle by melo platit)
define("_Config_hour_frac_item","2");

// kolik grabu za tyden muze uzivatel zadat
// pokud zada grab, ktery se negrabne nebo ktery pozdeji zrusi, tak muze misto nej zadat dalsi
define("_Config_grab_quota","30");

// logovani udalosti dvb grabu
define("_Config_dvbgrab_log","/var/www/dvbgrab/dvbgrab.log");

// logovani udalosti dvb grabu
define("_Config_dvbgrab_encode_log","");

// posun zacatku nahravani v minutach
define("_Config_grab_date_start_shift","2");

// posun  konce nahravani v minutach
define("_Config_grab_date_stop_shift","20");

// jmeno stroje s http serverem dvbgrabu
define("_Config_hostname","dvbgrab.domain");

// root adresar http grabu
define("_Config_grab_root","/var/www/dvbgrab/storage");

// prostor pro grabovani
define("_Config_grab_storage","/var/www/dvbgrab/storage");

// az v grabovacim adresari bude vice nez 200G dat tak se budou promazavat starsi
define("_Config_grab_storage_size","200");

// az v grabovacim adresari bude mene nez 40G mista tak se budou take promazavat starsi
define("_Config_grab_storage_min_size","40");

// pocet neaktivnich dni po kterych bude uzivatelsky ucet smazan
define("_Config_user_inactivity_limit","40");

// pouzity jazyk v backendu
define("_Config_grab_backend_lang","en");

// odstranovat diakritiku v souborech v backendu nebo jen id
define("_Config_grab_backend_strip_diacritics","1");

// jak dlouho nahravat porad po kterem nic nenasleduje v sekundach
define("_Config_record_time_after_last","7200");
?>
