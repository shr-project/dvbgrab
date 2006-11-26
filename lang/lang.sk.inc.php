<?

define("_MsgGlobalTitle",":: projekt DVBgrab ::");
define("_MsgGlobalRetry","Znovu");
define("_MsgGlobalBack","Späť na hlavnú stránku");

define("_MsgIndex","Vitajte na stránkach projektu DVBgrab.");
define("_MsgIndexP1","Po prihlásení máte možnosť prezerať si televízny program na 10 dní dopredu a označovať v ňom programy, o ktoré máte záujem. Po nagrabovaní sa záujemcovi pošle email o jeho uložení a odkaz na stiahnutie.");
define("_MsgIndexP2","Po objednaní grabu je možnosť pomocou odkazu \"do TS\" zrušiť štandardnú kompresiu do MPEG4. Potom bude uložený približne 2,5x väčší súbor, ale lepšie editovatelný napríklad pre vystrihovanie reklám a úpravy začiatku a konca programu. Bohužiaľ bez tejto kompresie sa stáva, že výsledný súbor je väčší než 2GB a vtedy nejde stiahnuť, pretože súčasná verzia http serveru apache2 s tým má problémy.");
define("_MsgIndexP3","Graby se uchovávajú len obmedzenú dobu (obyčajne aj 1 mesiac, minimálne 7 dní od nahrania programu). Pokiaľ si ho nestihnete stiahnuť, tak môže byť jednoducho zmazaný, pretože nemáme nekonečnú diskovú kapacitu. (zmazaný program bude označený v zozname \"Moje graby\")");
define("_MsgIndexPW1","Ku grabu majú prístup len tí, ktorí si ho zadali. Toto obmedzenie je nastavené schválne. Zjednodušene povedané, z pohľadu zákona ku grabu môže mať prístup len človek, ktorý si ho vlastnoručne zaškrtol.");
define("_MsgIndexPW2","Grabovací server je stále preťažený komprimáciou, preto sa doba doručenia správy o hotovom grabu stále predlžuje. Pretože kvalita kompresie už bola znížená a aj tak to nestíha, tak prosím nenahrávajte každú blbosť ;-).");
define("_MsgIndexPW3","DVBgrab se stále vyvíja, tak čítajte <a href=\"news.php\">novinky</a>.");

define("_MsgIndexLogFail","Prihlásenie sa nepodarilo! Zadaná nesprávna kombinácia mena a hesla.");
define("_MsgIndexUser","Užívateľ");
define("_MsgIndexLogOk","bol úspešne prihlásený.");
define("_MsgIndexLogout","Užívateľ bol úspešne odhlásený.");
define("_MsgIndexRegFailIp","Chyba registrácie: Z tejto ip adresy už bol jeden užívateľ zaregistrovaný");
define("_MsgIndexRegFailData","Chyba registrácie: Je nutné zadať meno, heslo a email.");
define("_MsgIndexRegFailEmail","Chyba registrácie: Nesprávny formát emailovej adresy.");
define("_MsgIndexRegFailPass","Chyba registrácie: Zadané heslá sa nezhodujú.");
define("_MsgIndexRegFailName","Chyba registrácie: Užívateľ s týmto prihlasovacím menom už existuje, zvoľte prosím iné!");
define("_MsgIndexRegOk","bol úspešne zaregistrovaný.");
   
define("_MsgConstsMonday","pondelok");
define("_MsgConstsTuesday","utorok");
define("_MsgConstsWednesday","streda");
define("_MsgConstsThursday","štvrtok");
define("_MsgConstsFriday","piatok");
define("_MsgConstsSaturday","sobota");
define("_MsgConstsSunday","nedeľa");

define("_MsgConstsMondayShort","Po");
define("_MsgConstsTuesdayShort","Ut");
define("_MsgConstsWednesdayShorti","St");
define("_MsgConstsThursdayShort","Št");
define("_MsgConstsFridayShort","Pi");
define("_MsgConstsSaturdayShort","So");
define("_MsgConstsSundayShort","Ne");

define("_MsgConstsJan","január");
define("_MsgConstsFeb","február");
define("_MsgConstsMar","marec");
define("_MsgConstsApr","apríl");
define("_MsgConstsMay","máj");
define("_MsgConstsJun","jún");
define("_MsgConstsJul","júl");
define("_MsgConstsAug","august");
define("_MsgConstsSep","september");
define("_MsgConstsOct","október");
define("_MsgConstsNov","november");
define("_MsgConstsDec","december");

define("_MsgPlanGrabLink","link na stiahnutie");
define("_MsgPlanGrabDeleted","Tento grab bol už zmazaný");
define("_MsgPlanGrabLinkNone","Neexistuje odkaz na nahraný program");
define("_MsgPlanListSchedTitle","Zoznam naplánovaných grabov");
define("_MsgPlanListMygrabTitle","Zoznam mojich grabov");
define("_MsgPlanListDoneTitle","Zoznam hotových grabov");
define("_MsgPlanSchedCount","Naplánovaných grabov");
define("_MsgPlanDoneCount","Hotových grabov");
define("_MsgPlanNothing","Neboli nájdené žiadne záznamy");

define("_MsgJsonLoading","Načítavajú se detaily grabu");
define("_MsgJsonTelName","Televízny program");
define("_MsgJsonTelSeries","Séria");
define("_MsgJsonTelEpisode","Epizóda");
define("_MsgJsonTelPart","Časť");
define("_MsgJsonChnName","Televízny kanál");
define("_MsgJsonGrbName","Názov grabu");
define("_MsgJsonTelDateStart","Začiatok programu");
define("_MsgJsonTelDateEnd","Koniec programu");
define("_MsgJsonGrbDateStart","Začiatok nahrávania");
define("_MsgJsonGrbDateEnd","Koniec nahrávania");
define("_MsgJsonReqOutput","Súbor");
define("_MsgJsonReqOutputMd5","MD5");
define("_MsgJsonReqOutputEnc","Kodek");
define("_MsgJsonReqOutputSize","Veľkosť");

define("_MsgAccountValidateLogin","Vyplňte prihlasovacie meno!");
define("_MsgAccountValidatePass","Vyplňte heslo!");
define("_MsgAccountValidatePassNoEql","Heslá sa nezhodujú!");
define("_MsgAccountValidateEmail","Vyplňte email!");
define("_MsgAccountValidateIp","Vyplňte ip!");
define("_MsgAccountValidateEmailFormat","Neplatný email!");
define("_MsgAccountValidateIpFormat","Neplatný formát IP adresy, musí být xxx.xxx.xxx.xxx!");

define("_MsgAccountNoUser","Užívateľ nie je prihlásený.");
define("_MsgAccountLogged","Prihlásený:");
define("_MsgAccountNoLogged","Neprihlásený:");
define("_MsgAccountLogout","Odhlásiť");
define("_MsgAccountRecordCount","Zadaných grabov:");
define("_MsgAcountNoLoggedNotice","Pre sprístupnenie položiek v menu vľavo sa prihláste:");

define("_MsgAccountLoginFormTitle","Prihlásenie");
define("_MsgAccountLostPass","Zabudli ste svoje heslo?");

define("_MsgAccountRegistrationTitle","Ste tu prvý krát? Vyplňte, prosím, krátku registráciu:");
define("_MsgAccountRegistrationFormTitle","Registrácia");
define("_MsgAccountRegisterButton","Registrovať");
define("_MsgAccountChangeButton","Zmeniť");
define("_MsgAccountLoginButton","Prihlásiť");

define("_MsgAccountChangeFormTitle","Nastavenie účtu");
define("_MsgAccountLogin","Prihlasovacie meno:");
define("_MsgAccountPass","Heslo:");
define("_MsgAccountPass2","Zopakovať heslo:");
define("_MsgAccountEmail","E-mail:");
define("_MsgAccountEmailWarning","E-mail vyplňte správny, na túto adresu Vám budú chodiť oznámenie o grabe a odkazy na stiahnutie!");
define("_MsgAccountIp","IP pre sťahovanie:");
define("_MsgAccountIcq","Icq#:");
define("_MsgAccountJabber","Jabber:");
define("_MsgAccountEncoder","Video kodek:");

define("_MsgAccountChanges","Na stránkach DVBgrabu boli vyžiadané nejaké zmeny v nastavení účtu:");
define("_MsgAccountChangeIp","požaduje zmenu sťahovacej IP:");
define("_MsgAccountChangeIpSubject","požiadavka na zmenu IP");
define("_MsgAccountChangeIpNotice","Zmena IP adresy pre sťahovanie sa neprejaví okamžite, až bude zmena vykonaná, bude zaslaný potvrdzujúci email");
define("_MsgAccountChangesSubject","zmeny v nastavení účtu");
define("_MsgAccountChangesNotice","Požadované zmeny boli uložené a odoslaný informačný mail.");
define("_MsgAccountNoChangesNotice","Nebola zadaná žiadna zmena.");

define("_MsgMenuTvProgram","TV program");
define("_MsgMenuPlanSched","Plánované graby");
define("_MsgMenuPlanDone","Hotové graby");
define("_MsgMenuPlanMygrab","Moje graby");
define("_MsgMenuPlanAccount","Nastavenie");
define("_MsgMenuEmailUs","Napíšte nám");
define("_MsgMenuNews","Novinky");
define("_MsgMenuDocs","Dokumentácia");
define("_MsgMenuLogout","Odhlásiť sa");

define("_MsgSearchTitle","Hľadaj v tv programe:");
define("_MsgSearchButton","Hľadaj");
define("_MsgSearchErrorNoInput","Chyba: nebol zadaný vyhľadávaný reťazec.");
define("_MsgSearchErrorManyInput","Chyba: bolo zadaných príliš veľa slov k vyhľadaniu.");
define("_MsgSearchResultsCount","Nájdených záznamov:");
define("_MsgSearchResultsCountsLimit","Najdených príliš veľa záznamov, zobrazujem prvých");

define("_MsgProgTitleDay","Zobraziť televízny program na deň");
define("_MsgProgShowButton","Zobraziť");
define("_MsgProgSearchButton","Hľadaj");
define("_MsgProgSearch","Hľadaj v tv programe:");
define("_MsgProgTitle","Televízny program");
define("_MsgProgNextDay","Nasledujúci deň");
define("_MsgProgPrevDay","Predchádzajúci deň");
define("_MsgProgNotAvailable","Televízny program na tento deň nie je k dispozícii!");
define("_MsgProgPremiere","/P/");
define("_MsgProgLastChance","/D/");
define("_MsgProgPreviouslySchown","/R/");
define("_MsgProgNew","/N/");

define("_MsgStatusOthers","Ostatní");
define("_MsgStatusName","Stav grabu");
define("_MsgStatusMy","Môj");
define("_MsgStatusScheduled","Naplánovaný");
define("_MsgStatusSaving","Nahráva sa");
define("_MsgStatusSaved","Nahraný");
define("_MsgStatusEncoding","Komprimuje sa");
define("_MsgStatusEncoded","Skomprimovaný");
define("_MsgStatusDone","Hotový");
define("_MsgStatusDeleted","Zmazaný");
define("_MsgStatusMissed","Premeškaný");
define("_MsgStatusError","Neúspech");
define("_MsgStatusUndefined","Nedefinovaný");

define("_MsgGrabFailAddQuota","ERROR: tento týždeň už nie je možné zadávať ďalšie graby");
define("_MsgGrabFailAddTime","ERROR: požiadavka na grab na už odvysielaný program");
define("_MsgGrabFailAddExist","ERROR: grab už existuje");
define("_MsgGrabFailAddTel","ERROR: daný program neexistuje");
define("_MsgGrabFailDelTime","ERROR: grab už skončil alebo prebieha");
define("_MsgGrabFailDelOwner","ERROR: nie je možné zrušiť cudzí grab");
define("_MsgGrabFailDelExist","ERROR: daný grab neexistuje");
define("_MsgGrabConfirmStart","Chcete program");
define("_MsgGrabConfirmGrab","vážne grabnúť?");
define("_MsgGrabConfirmGrabToo","vážne tiež grabnúť?");
define("_MsgGrabLinkStorno","zrušiť grab");
define("_MsgGrabLinkGrabToo","tiež grabnúť");
define("_MsgGrabLinkGrab","grabnúť");
define("_MsgGrabLinkShow","zobraziť v kontexte");
define("_MsgGrabAddOk","grab naplánovaný");

define("_MsgSendPassTitle","Vygenerovanie dočasného hesla");
define("_MsgSendPassButton","Pošli mi nové heslo!");
define("_MsgSendPassCheckFailed1","Kombinacia užívateľ");
define("_MsgSendPassCheckFailed2","a mail");
define("_MsgSendPassCheckFailed3","nebola nájdená");
define("_MsgSendPassEmailStart","Na stránkach DVBgrabu bolo vyžiadané nové heslo pre účet:");
define("_MsgSendPassEmailSubject","Vyžiadané zaslanie nového hesla");
define("_MsgSendPassNotice1","Heslo užívateľa");
define("_MsgSendPassNotice2","bolo poslané na mail");

define("_MsgSetupChangedOk","Konfigurácia v súbore config.php bola úspešne uložená");
define("_MsgSetupCronList","Nasledujúci text vložte do konfigurácie cron démona (crontab -e)");
define("_MsgSetupWelcome","Vitajte v konfiguračnom rozhraní pre projekt DVB grab");
define("_MsgSetupText","Všetky nastavenia sa ukladajú do súboru config.php. Preto by tento súbor mal byť prepisovateľný vlastníkom a po nastavení čitateľný len vlastníkom. Pred upravovaním preto spustite configure.sh a potom secure.sh. Rovnaký config.php je potom treba prekopírovať do adresára backend, ktorý sa presunie na grabovací stroj.");
define("_MsgSetupValue","Hodnota");
define("_MsgSetupKey","Kľúč");
define("_MsgSetupDbName","Názov databázy, do ktorej budeme ukladať dáta");
define("_MsgSetupDbType","Typ databázového stroja, k dispozícii je vďaka AdoDB: MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, Foxpro, Access, ADO, Sybase, FrontBase, DB2, SAP DB, SQLite, Netezza, LDAP, and generic ODBC, ODBTP");
define("_MsgSetupDbHost","Názov počítača, kde pobeží databázový stroj");
define("_MsgSetupDbUser","Meno užívateľa, akým sa budeme prihlasovať do databázy");
define("_MsgSetupDbPass","Heslo, s akým sa budeme prihlasovať do databázy");
define("_MsgSetupErrorStatus","Množstvo informácií o vzniknutej chybe:");
define("_MsgSetupErrorStatus0","* 0 - Každá chyba je vypísaná na stránke");
define("_MsgSetupErrorStatus1","* 1 - Každá chyba je odoslaná na chybový email");
define("_MsgSetupErrorStatus2","* 2 - Každá chyba je ignorovaná. Toto je východiskové nastavenie");
define("_MsgSetupErrorEmail","Email, kam budú odosielané informácie o chybách webového rozhrania");
define("_MsgSetupAdminEmail","Email, kam budú odosielané informácie o chybách v grabovacom systéme");
define("_MsgSetupReportEmail","Email, kam budú odosielané súhrnné informácie o využití systému");
define("_MsgSetupProxyServer","IP adresa HTTP proxy servera, pokiaľ musí byť použitý pre prístup k vonkajším www stránkam");
define("_MsgSetupProxyPort","Port pre HTTP proxy");
define("_MsgSetupGrabHistory","Koľko dní sa majú uchovávať nagrabované programy pre stiahnutie");
define("_MsgSetupTvDays","Koľko dní dopredu má byť k dispozícii tv program");
define("_MsgSetupMidnight","Ktorú hodinu budeme považovať za polnoc pri rozdeľovaní programov do jednotlivých dní");
define("_MsgSetupHourFracItem","Do akých veľkých úsekov budeme zoskupovať zoznam programov. 24 by malo byť deliteľné hodnotou bez zvyšku.");
define("_MsgSetupGrabQuota","Koľko grabov môže zadať užívateľ týždenne");
define("_MsgSetupDvbgrabLog","Do akého súboru sa majú ukladať informácie o priebehu grabovania");
define("_MsgSetupGrabDateStartShift","O koľko minút sa má posunúť začiatok nahrávania programu");
define("_MsgSetupGrabDateStopShift","O koľko minút sa má posunúť koniec nahrávania programu");
define("_MsgSetupHostname","Názov počítača, kam se budú programy nahrávať");
define("_MsgSetupGrabStorage","Adresár, do ktorého sa budú nahrávať programy");
define("_MsgSetupGrabStorageSize","Koľko GB priestoru máme vyhradeného pre nahrané programy");
define("_MsgSetupGrabRoot","Adresár, kam sa budú ukladať odkazy na hotové programy. Musí byť prístupný pre http server");
define("_MsgSetupEndText","Nezabudnite spustiť secure.sh a potom skopírovať config.php do adresára backend a celý adresár backend na grabovací stroj.");
define("_MsgSetupResetButton","Obnoviť");
define("_MsgSetupSubmitButton","Nastaviť");
define("_MsgSetupTvgDesc","Sťahovače televíznych programov");
define("_MsgSetupTvgName","Meno");
define("_MsgSetupTvgEnabled","Povolený?");
define("_MsgSetupTvgRunAt","Kedy spúštať cronom");
define("_MsgSetupTvgRun","Príkaz");
define("_MsgSetupTvgNew","Nový");
define("_MsgSetupTvgFailed","Pridanie nového sťahovača zlyhalo, pravdepodobne nebol zadaný kompletne.");
define("_MsgSetupRecordTimeAfterLast","Ako dlho nahrávať program, pokiaľ nepoznáme nasledujúci (napríklad posledný program v noci má nasledujúci až druhý deň ráno) [s]");
define("_MsgSetupGrabStorageMinSize","Minimálne množstvo miesta na grabovacom disku, pri ktorom začneme premazávať staršie graby");
define("_MsgSetupGrabBackendLang","Jazyk používaný v backend skriptoch (cs,en,fr,..)");
define("_MsgSetupBackendStripDiacritics","1 pokiaľ sa má skúšať použiť názov programu bez diakritiky ako názov grabu a 0 pokiaľ sa má použiť tel_id");
define("_MsgSetupUserInactivityLimit","Po koľkých dňoch neaktivity bude užívateľský účet zrušený");

define("_MsgBackendGrabError","sa nepodarilo uložiť");
define("_MsgBackendGrabErrorSub","DVBgrab: Nevydarené nahrávanie");
define("_MsgBackendEncodeError","sa nepodarilo skomprimovať");
define("_MsgBackendEncodeErrorSub","DVBgrab: Nevydarené komprimovanie");
define("_MsgBackendSuccess","je pripravený na stiahnutie");
define("_MsgBackendSuccessSub","DVBgrab: Hotový grab");
define("_MsgBackendGrabList","Zoznam grabu za");
define("_MsgBackendAccountCleaned","Užívateľský účet bol zrušený. Počet dní neaktivity, po ktorom sa účty rušia:");
define("_MsgBackendAccountCleanedSub","Užívateľský účet bol zrušený");
define("_MsgBackendFilesizeWarningSize","Na disku pre uchovávanie grabov dochádza miesto, začínajú sa mazať aj graby, ktoré by ešte mali ostať dostupné");
define("_MsgBackendFilesizeWarningSizeSub","Nie je miesto na disku pre graby");

define("_MsgXmlTvFormatErrorNoChn","Nebol nájdený odpovedajúci televízny kanál");
define("_MsgXmlTvFormatErrorManyChn","Odpovedá viacerým televíznym kanálom");
define("_MsgXmlTvFormatErrorNoneChn","Nezadaný televízny kanál");
define("_MsgXmlTvFormatErrorNoDateStart","Nezadaný začiatok programu");
define("_MsgXmlTvFormatErrorData","Textové dáta, ktoré nie je možné priradiť k žiadnemu tagu");
define("_MsgXmlTvFormatErrorNotAll","Nie sú zadané všetky povinné hodnoty");
define("_MsgXmlTvIgnored","Ignorovaný, duplicita");
define("_MsgXmlTvInserted","Vložený");
define("_MsgXmlTvUpdated","Zmenený");
define("_MsgXmlTvSuccess","Tv program úspešne aktualizovaný");
define("_MsgXmlTvFailed","Tv program aktualizovaný s chybami");
?>
