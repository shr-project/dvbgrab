<?
/* 
     supported db_type: all from adodb 
     (MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, 
      Foxpro, Access, ADO, Sybase, FrontBase, DB2, SAP DB, 
      SQLite, Netezza, LDAP, and generic ODBC, ODBTP)
*/
$db_name= "dvbgrab";
$db_type= "mysql";
$db_host= "localhost";
$db_user= "dvbgrab";
$db_pass= "heslo";

/*
    * 0 - Any error is written into the page
    * 1 - Any error is reported with a message to error_email
    * 2 - Any error is reported via both email and page.
    * * - Any error is ignored.
*/
$error_status= "0";
$error_email= "root@localhost";

// email spravce dvbgrabu
$admin_email= "root@localhost";

// email na zasilani denni reportu
$report_email= "root@localhost";

// set this value, if proxy server is needed for downloading tv program
$proxy_server= "";
$proxy_port= "3128";

// kolik dnu zpatky zobrazovat program
$grab_history= "3";

// na max kolik dnu dopredu se bude zobrazovat program
$tv_days= "14";

// od ktere hodiny se jedna o novy den (tj. pulnoc nebereme jako pocatek noveho dne)
$midnight= "5";

// rozdeleni dne na casove useky po $hour_frac_item hodinach 
//(24%$hour_frac_item=0 tj. 24 hodin je delitelne $hour_frac_item - tohle by melo platit)
$hour_frac_item= "2";

// kolik grabu za tyden muze uzivatel zadat
// pokud zada grab, ktery se negrabne nebo ktery pozdeji zrusi, tak muze misto nej zadat dalsi
$grab_quota= "30";

// logovani udalosti dvb grabu (relativne od spusteni grab_loop.php)
$dvbgrab_log= "log/dvbgrab.log";

// posun zacatku nahravani v minutach
$grab_date_start_shift= "3";

// posun konce nahravani v minutach
$grab_date_stop_shift= "10";

// bonusovy posun konce nahravani ve vecernich hodinach (v minutach)
$grab_date_stop_bonus_shift= "20";


// prostor pro grabovani.
// Mel by byt na stejne partition jako $grab_root, protoze se pouzivaji hardlinky.
$grab_storage= "/home/ftp/tvgraby/all";

// root adresar uzivatelskych grabu, lze zpristupnit pres web
// NOTE: "<username>/" is appended to it.
$grab_root= "/home/ftp/tvgraby/users/ftp_";

// Url for users to download the grabs.
$grab_user_url= "ftp://ftp_<USER>@tvgrab.sh.cvut.cz";
//$grab_user_url= "http://tvgrab.sh.cvut.cz/users/<USER>";

// 4G is the minimum to keep free
$grab_storage_size= "4096";

// Limit of days to preserve .mpg
$max_mpg_days = 5;

?>
