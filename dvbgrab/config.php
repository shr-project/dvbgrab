<?
/* 
     supported db_type: all from adodb 
     (MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, 
      Foxpro, Access, ADO, Sybase, FrontBase, DB2, SAP DB, 
      SQLite, Netezza, LDAP, and generic ODBC, ODBTP)
*/
$db_name= "dvbgrab";
$db_type= "mysql";
$db_host= "dvbgrab.mk.cvut.cz";
$db_user= "dvbgrab";
$db_pass= "heslo";

/*
    * 0 - Any error is written into the page
    * 1 - Any error is reported with a message to error_email
    * 2 - Any error is ignored. This is the default.
*/
$error_status= "1";
$error_email= "dvbgrab.admin@mk.cvut.cz";

// email spravce dvbgrabu
$admin_email= "dvbgrab.admin@mk.cvut.cz";

// email na zasilani denni reportu
$report_email= "dvbgrab.admin@mk.cvut.cz";

// set this value, if proxy server is needed for downloading tv program
$proxy_server= "";

// kolik dnu zpatky budou uchovavany graby na disku
$grab_history= "3";

// na kolik dnu dopredu se bude zobrazovat program
$tv_days= "14";

// od ktere hodiny se jedna o novy den (tj. pulnoc nebereme jako pocatek noveho dne)
$midnight= "5";

// rozdeleni dne na casove useky po $hour_frac_item hodinach 
//(24%$hour_frac_item=0 tj. 24 hodin je delitelne $hour_frac_item - tohle by melo platit)
$hour_frac_item= "2";

// kolik grabu za tyden muze uzivatel zadat
// pokud zada grab, ktery se negrabne nebo ktery pozdeji zrusi, tak muze misto nej zadat dalsi
$grab_quota= "50";

// logovani udalosti dvb grabu
$dvbgrab_log= "/var/log/dvbgrab.log";

// posun zacatku a konce nahravani v minutach
$grab_date_start_shift= "2";

// jmeno stroje s http serverem dvbgrabu
$hostname= "zeus.mk.cvut.cz";

// root adresar http grabu
$grab_root= "/var/www/html/dvbgrab";

// prostor pro grabovani
$grab_storage= "/pub/grab";

// az v grabovacim adresari bude vice nez 200G dat tak se budou promazavat starsi
$grab_storage_size= "200";

?>
