<?

define("_MsgGlobalTitle",":: projekt DVBgrab ::");
define("_MsgGlobalRetry","Znova");
define("_MsgGlobalBack","Zpět na hlavní stránku");

define("_MsgIndex","Vítejte na stránkách projektu DVBgrab.");
define("_MsgIndexP1","Po přihlášení máte možnost prohlížet televizní program na 10 dní dopředu a označovat v něm pořady, o které máte zájem. Po nagrabování se zájemci pošle email o jeho uložení a odkaz ke stažení.");
define("_MsgIndexP2","Po objednání grabu je možnost pomoci odkazu \"do TS\" zrušit standartní kompresi do MPEG4. Pak bude uložen přibližně 2,5x větší soubor, ale lépe editovatelný třeba pro vystřihování reklam a úpravy začátku a konce pořadu. Bohužel bez této komprese se stává, že výsledný soubor je větší než 2GB a pak nejde stáhnout, protože současná verze http serveru apache2 s tím má problémy.");
define("_MsgIndexP3","Graby se uchovávají pouze omezenou dobu (obvykle i 1 měsíc, minimálně 7 dní od nahrání pořadu). Pokud si ho nestihnete stáhnout tak může být prostě smazán, protože nemáme nekonečnou diskovou kapacitu. (smazaný pořad bude označen v seznamu \"Moje graby\")");
define("_MsgIndexPW1","Ke grabu mají přístup pouze ti, kteří si ho zadali. Toto omezení je nastaveno schválně. Zjednodušeně řečeno, z pohledu zákona ke grabu může mít přístup pouze člověk, který si ho vlastnoručne zaškrtl.");
define("_MsgIndexPW2","Grabovací server je stále přetížen komprimací, proto se doba doručení zpravy o hotovém grabu stále prodlužuje. Protože kvalita komprese už byla snížena a stejně to nestíhá, tak prosím nenahrávejte každou blbost ;-).");
define("_MsgIndexPW3","DVBgrab se stále vyvíjí tak čtěte <a href=\"news.php\">novinky</a>.");

define("_MsgIndexLogFail","Přihlášení se nepovedlo! Zadána špatná kombinace jména a hesla.");
define("_MsgIndexUser","Uživatel");
define("_MsgIndexLogOk","byl úspěšně přihlášen.");
define("_MsgIndexLogout","Uživatel byl úspěšně odhlášen.");
define("_MsgIndexRegFailIp","Chyba registrace: Z této ip adresy již byl jeden uživatel zaregistrován");
define("_MsgIndexRegFailData","Chyba registrace: Je nutné zadat jméno, heslo a email.");
define("_MsgIndexRegFailEmail","Chyba registrace: Nesprávný formát emailové adresy.");
define("_MsgIndexRegFailPass","Chyba registrace: Zadaná hesla se neshodují.");
define("_MsgIndexRegFailName","Chyba registrace: Uživatel s tímto přihlašovacím jménem již existuje, zvolte prosím jiné!");
define("_MsgIndexRegOk","byl úspěšně zaregistrován.");
   
define("_MsgConstsMonday","pondělí");
define("_MsgConstsTuesday","úterý");
define("_MsgConstsWednesday","středa");
define("_MsgConstsThursday","čtvrtek");
define("_MsgConstsFriday","pátek");
define("_MsgConstsSaturday","sobota");
define("_MsgConstsSunday","neděle");

define("_MsgConstsMondayShort","Po");
define("_MsgConstsTuesdayShort","Út");
define("_MsgConstsWednesdayShorti","St");
define("_MsgConstsThursdayShort","Čt");
define("_MsgConstsFridayShort","Pá");
define("_MsgConstsSaturdayShort","So");
define("_MsgConstsSundayShort","Ne");

define("_MsgConstsJan","leden");
define("_MsgConstsFeb","únor");
define("_MsgConstsMar","březen");
define("_MsgConstsApr","duben");
define("_MsgConstsMay","květen");
define("_MsgConstsJun","červen");
define("_MsgConstsJul","červenec");
define("_MsgConstsAug","srpen");
define("_MsgConstsSep","září");
define("_MsgConstsOct","říjen");
define("_MsgConstsNov","listopad");
define("_MsgConstsDec","prosinec");

define("_MsgPlanGrabLink","link na stažení");
define("_MsgPlanGrabDeleted","Tento grab byl už smazán");
define("_MsgPlanGrabLinkNone","Neexistuje odkaz na nahraný pořad");
define("_MsgPlanListSchedTitle","Seznam naplánovaných grabů");
define("_MsgPlanListMygrabTitle","Seznam mých grabů");
define("_MsgPlanListDoneTitle","Seznam hotových grabů");
define("_MsgPlanSchedCount","Naplánovaných grabů");
define("_MsgPlanDoneCount","Hotových grabů");
define("_MsgPlanNothing","Nenalezeny žádné záznamy");

define("_MsgJsonLoading","Načítají se detaily grabu");
define("_MsgJsonTelName","Televizní pořad");
define("_MsgJsonTelSeries","Serie");
define("_MsgJsonTelEpisode","Epizoda");
define("_MsgJsonTelPart","Část");
define("_MsgJsonChnName","Televizní kanál");
define("_MsgJsonGrbName","Název grabu");
define("_MsgJsonTelDateStart","Začátek pořadu");
define("_MsgJsonTelDateEnd","Konec pořadu");
define("_MsgJsonGrbDateStart","Začátek nahrávání");
define("_MsgJsonGrbDateEnd","Konec nahrávání");
define("_MsgJsonReqOutput","Soubor");
define("_MsgJsonReqOutputMd5","MD5");
define("_MsgJsonReqOutputEnc","Kodek");
define("_MsgJsonReqOutputSize","Velikost");

define("_MsgAccountValidateLogin","Vyplňte přihlašovací jméno!");
define("_MsgAccountValidatePass","Vyplňte heslo!");
define("_MsgAccountValidatePassNoEql","Hesla se neshodují!");
define("_MsgAccountValidateEmail","Vyplňte email!");
define("_MsgAccountValidateIp","Vyplňte ip!");
define("_MsgAccountValidateEmailFormat","Neplatný email!");
define("_MsgAccountValidateIpFormat","Neplatný formát IP adresy, musí být xxx.xxx.xxx.xxx!");

define("_MsgAccountNoUser","Uživatel není přihlášen.");
define("_MsgAccountLogged","Přihlášen:");
define("_MsgAccountNoLogged","Nepřihlášen:");
define("_MsgAccountLogout","Odhlásit");
define("_MsgAccountRecordCount","Zadáno grabů:");
define("_MsgAcountNoLoggedNotice","Pro zpřístupnění položek v menu vlevo se přihlašte:");

define("_MsgAccountLoginFormTitle","Přihlášení");
define("_MsgAccountLostPass","Zapoměli jste své heslo?");

define("_MsgAccountRegistrationTitle","Jste tu poprvé? Vyplňte, prosím, krátkou registraci:");
define("_MsgAccountRegistrationFormTitle","Registrace");
define("_MsgAccountRegisterButton","Registrovat");
define("_MsgAccountChangeButton","Změnit");
define("_MsgAccountLoginButton","Přihlásit");

define("_MsgAccountChangeFormTitle","Nastavení účtu");
define("_MsgAccountLogin","Přihlašovací jméno:");
define("_MsgAccountUsername","Přihlašovací jméno:");
define("_MsgAccountPass","Heslo:");
define("_MsgAccountPass2","Zopakovat heslo:");
define("_MsgAccountEmail","E-mail:");
define("_MsgAccountEmailWarning","E-mail vyplňte správný, na tuto adresu Vám budou chodit oznámení o grabu a odkazy na stažení!");
define("_MsgAccountIp","IP pro stahování:");
define("_MsgAccountIcq","Icq#:");
define("_MsgAccountJabber","Jabber:");
define("_MsgAccountEncoder","Video kodek:");

define("_MsgAccountChanges","Na stránkách DVBgrabu byly vyžádány nějaké změny v nastavení účtu:");
define("_MsgAccountChangeIp","požaduje změnu stahovací IP:");
define("_MsgAccountChangeIpSubject","požadavek na změnu IP");
define("_MsgAccountChangeIpNotice","Změna IP adresy pro stahování se neprojeví okamžitě, až bude změna provedena bude zaslán potvrzující email");
define("_MsgAccountChangesSubject","změny v nastavení účtu");
define("_MsgAccountChangesNotice","Požadované změny byly uloženy a odeslán informační mail.");
define("_MsgAccountNoChangesNotice","Nebyla zadána žádná změna.");

define("_MsgMenuTvProgram","TV program");
define("_MsgMenuPlanSched","Plánované graby");
define("_MsgMenuPlanDone","Hotové graby");
define("_MsgMenuPlanMygrab","Moje graby");
define("_MsgMenuPlanAccount","Nastavení");
define("_MsgMenuEmailUs","Napište nám");
define("_MsgMenuNews","Novinky");
define("_MsgMenuDocs","Dokumentace");
define("_MsgMenuLogout","Odhlásit se");

define("_MsgSearchTitle","Hledej v tv programu:");
define("_MsgSearchButton","Hledej");
define("_MsgSearchErrorNoInput","Chyba: nebyl zadán vyhledávaný řetězec.");
define("_MsgSearchErrorManyInput","Chyba: bylo zadáno příliš mnoho slov k vyhledání.");
define("_MsgSearchResultsCount","Nalezených záznamů:");
define("_MsgSearchResultsCountsLimit","Nalezeno příliš mnoho záznamů, zobrazuji prvních");

define("_MsgProgTitleDay","Zobrazit televizní program pro den");
define("_MsgProgShowButton","Zobrazit");
define("_MsgProgSearchButton","Hledej");
define("_MsgProgSearch","Hledej v tv programu:");
define("_MsgProgTitle","Televizní program");
define("_MsgProgNextDay","Následující den");
define("_MsgProgPrevDay","Předchozí den");
define("_MsgProgNotAvailable","Televizní program pro tento den není k dispozici!");
define("_MsgProgPremiere","/P/");
define("_MsgProgLastChance","/D/");
define("_MsgProgPreviouslySchown","/R/");
define("_MsgProgNew","/N/");

define("_MsgStatusOthers","Ostatní");
define("_MsgStatusName","Stav grabu");
define("_MsgStatusMy","Můj");
define("_MsgStatusScheduled","Naplánovaný");
define("_MsgStatusSaving","Nahrává se");
define("_MsgStatusSaved","Nahraný");
define("_MsgStatusEncoding","Komprimuje se");
define("_MsgStatusEncoded","Zkomprimovaný");
define("_MsgStatusDone","Hotový");
define("_MsgStatusDeleted","Smazaný");
define("_MsgStatusMissed","Promeškaný");
define("_MsgStatusError","Neúspěch");
define("_MsgStatusUndefined","Nedefinovaný");

define("_MsgGrabFailAddQuota","ERROR: tento týden již nelze zadávat další graby");
define("_MsgGrabFailAddTime","ERROR: požadavek o grab na už odvysílaný pořad");
define("_MsgGrabFailAddExist","ERROR: grab již existuje");
define("_MsgGrabFailAddTel","ERROR: daný pořad neexistuje");
define("_MsgGrabFailDelTime","ERROR: grab už skončil, nebo probíhá");
define("_MsgGrabFailDelOwner","ERROR: nelze zrušit cizí grab");
define("_MsgGrabFailDelExist","ERROR: daný grab neexistuje");
define("_MsgGrabConfirmStart","Chcete pořad");
define("_MsgGrabConfirmGrab","vážně grabnout?");
define("_MsgGrabConfirmGrabToo","vážně taky grabnout?");
define("_MsgGrabLinkStorno","zrušit grab");
define("_MsgGrabLinkGrabToo","taky grabnout");
define("_MsgGrabLinkGrab","grabnout");
define("_MsgGrabLinkShow","zobrazit v kontextu");
define("_MsgGrabAddOk","grab naplánován");

define("_MsgSendPassTitle","Vygenerování dočasného hesla");
define("_MsgSendPassButton","Pošli mi nové heslo!");
define("_MsgSendPassCheckFailed1","Kombinace uživatel");
define("_MsgSendPassCheckFailed2","a mail");
define("_MsgSendPassCheckFailed3","nenalezena");
define("_MsgSendPassEmailStart","Na stránkách DVBgrabu bylo vyžádáno nové heslo pro účet:");
define("_MsgSendPassEmailSubject","Vyžádáno zaslání nového hesla");
define("_MsgSendPassNotice1","Heslo uživatele");
define("_MsgSendPassNotice2","bylo posláno na mail");

define("_MsgSetupChangedOk","Konfigurace v souboru config.php byla úspěšně uložena");
define("_MsgSetupWelcome","Vítejte v konfiguračním rozhraní pro projekt DVB grab");
define("_MsgSetupText","Všechna nastavení se ukládají do souboru config.php. Proto by tento soubor měl být přepisovatelný vlastníkem a po nastavení čitelný jenom vlastníkem. Před upravováním proto spusťte configure.sh a potom secure.sh. Stejny config.php je pak třeba překopírovat do adresáře backend, který se přesune na grabovací stroj.");
define("_MsgSetupValue","Hodnota");
define("_MsgSetupKey","Klíč");
define("_MsgSetupDbName","Název databáze do které budeme ukládat data");
define("_MsgSetupDbType","Typ databázového stroje, k dispozici je díky AdoDB: MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, Foxpro, Access, ADO, Sybase, FrontBase, DB2, SAP DB, SQLite, Netezza, LDAP, and generic ODBC, ODBTP");
define("_MsgSetupDbHost","Název počítače, kde poběží databázový stroj");
define("_MsgSetupDbUser","Jméno uživatele, jak se budeme přihlašovat do databáze");
define("_MsgSetupDbPass","Heslo s jakym se budeme přihlašovat do databáze");
define("_MsgSetupErrorStatus","Množství informací o vzniké chybě:");
define("_MsgSetupErrorStatus0","* 0 - Každá chyba je vypsána do stránky");
define("_MsgSetupErrorStatus1","* 1 - Každá chyba je odeslána na chybový email");
define("_MsgSetupErrorStatus2","* 2 - Každá chyba je ignorována. Toto je výchozí nastavení");
define("_MsgSetupErrorEmail","Email kam budou odesílány informace o chybách webového rozhraní");
define("_MsgSetupAdminEmail","Email kam budou odesílány informace o chybách v grabovacím systému");
define("_MsgSetupReportEmail","Email kam bodou odesílány souhrné informace o využití systému");
define("_MsgSetupProxyServer","IP adresa HTTP proxy serveru, pokud musí být použit pro přístup k vnějším www stránkám");
define("_MsgSetupProxyPort","Port pro HTTP proxy");
define("_MsgSetupGrabHistory","Kolik dnů se mají uchovávat nagrabované pořady pro stažení");
define("_MsgSetupTvDays","Kolik dnů dopředu má být k dispozici tv program");
define("_MsgSetupMidnight","Kterou hodinu budeme považovat za půlnoc při rozdělování pořadů do jednotlivých dnů");
define("_MsgSetupHourFracItem","Do jak velikých úseků budeme seskupovat seznam pořadů. 24 by mělo být dělitelné hodnotou beze zbytku.");
define("_MsgSetupGrabQuota","Kolik grabů může zadat uživatel týdně");
define("_MsgSetupDvbgrabLog","Do jakého souboru se mají ukládat informace o průběhu grabování");
define("_MsgSetupGrabDateStartShift","O kolik minut se má posunout začátek nahrávání pořadu");
define("_MsgSetupGrabDateStopShift","O kolik minut se má posunout konec nahrávání pořadu");
define("_MsgSetupHostname","Název počítače kde se budou pořady nahrávat");
define("_MsgSetupGrabStorage","Adresář do kterého se budou nahrávat pořady");
define("_MsgSetupGrabStorageSize","Kolik GB prostoru máme vyhrazeno pro nahrané pořady");
define("_MsgSetupGrabRoot","Adresář kam se budou ukládat odkazy na hotové pořady. Musí být přístupný pro http server");
define("_MsgSetupEndText","Nezapomeňtě spustit secure.sh a pak zkopírovat config.php do adresáře backend a celý adresář backend na grabovací stroj.");
define("_MsgSetupResetButton","Obnovit");
define("_MsgSetupSubmitButton","Nastavit");
define("_MsgSetupTvgDesc","Stahovače televizních programů");
define("_MsgSetupTvgName","Jméno");
define("_MsgSetupTvgEnabled","Povolen?");
define("_MsgSetupTvgRunAt","Kdy souštět cronem");
define("_MsgSetupTvgRun","Příkaz");
define("_MsgSetupTvgNew","Nový");
define("_MsgSetupTvgFailed","Přidání nového stahovače selhalo, pravděpodobně nebyl zadán kompletně.");
define("_MsgSetupRecordTimeAfterLast","Jak dlouho nahrávat pořad pokud neznáme následující (třeba poslední pořad v noci má následující až druhý den ráno) [s]");
define("_MsgSetupGrabStorageMinSize","Minimální množství místa na grabovacím disku, při kterém začneme promazávat starší graby");
define("_MsgSetupGrabBackendLang","Jazyk používaný v backend skriptech (cs,en,fr,..)");
define("_MsgSetupBackendStripDiacritics","1 pokud se má zkoušet použít název pořadu bez diakritiky jako název grabu a 0 pokud se má použít tel_id");
define("_MsgSetupUserInactivityLimit","Po kolika dnech neaktivity bude uživatelský účet zrušen");

define("_MsgBackendGrabError","se nepodarilo ulozit");
define("_MsgBackendGrabErrorSub","DVBgrab: Nepodarene nahravani");
define("_MsgBackendEncodeError","se nepodarilo zkomprimovat");
define("_MsgBackendEncodeErrorSub","DVBgrab: Nepodarene komprimovani");
define("_MsgBackendSuccess","je pripraveny ke stazeni");
define("_MsgBackendSuccessSub","DVBgrab: Hotovy grab");
define("_MsgBackendGrabList","Seznam grabu za");

define("_MsgXmlTvFormatErrorNoChn","Nenalezen odpovídající televizní kanál");
define("_MsgXmlTvFormatErrorManyChn","Odpovídá více televizním kanálům");
define("_MsgXmlTvFormatErrorNoneChn","Nezadán televizní kanál");
define("_MsgXmlTvFormatErrorNoDateStart","Nezadán začátek pořadu");
define("_MsgXmlTvFormatErrorData","Textová data, které nelze přiřadit k žádnému tagu");
define("_MsgXmlTvFormatErrorNotAll","Nejsou zadány všechny povinné hodnoty");
define("_MsgXmlTvIgnored","Ignorován, duplicita");
define("_MsgXmlTvInserted","Vložen");
define("_MsgXmlTvUpdated","Změněn");
define("_MsgXmlTvSuccess","Tv program úspěšně aktualizován");
define("_MsgXmlTvFailed","Tv program aktualizován s chybami");

define("_MsgNews1","Opraveno/Přidáno vyhledávání v plánovaných grabech (Velmi užitecné pro grabovače seriálů ;-))");
define("_MsgNews2","Omezeno zobrazovaní hotových grabů na posledních 100 zaznamů, to samé pro zobrazení mých grabů.");
define("_MsgNews3","Přidána možnost nechat si poslat nové vygenerované heslo.");
define("_MsgNews4","Přidána volba \"Nastavení\", pro úpravy uživatelských účtů.");
define("_MsgNews5","Registrován jubilejní 100. uživatel. Bohužel vůbec nic nevyhrává protože má plnou mailovou schránku.:-P");
define("_MsgNews6","Přidána možnost zadávat graby rovnou z vyhledávací stránky.");
define("_MsgNews7","Opravy několika chyb, přidání nových funkcí, změny textů a <a href=\"anketa.php\">anketa.</a>");
?>
