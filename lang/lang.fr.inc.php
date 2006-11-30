define("_MsgProgNotAvailable","Schedule isn't available!");
define("_MsgGrabAddOk","record scheduled");
define("_MsgProgPremiere","/P/");
define("_MsgProgLastChance","/D/");
define("_MsgProgPreviouslySchown","/R/");
define("_MsgProgNew","/N/");
define("_MsgXmlTvFormatErrorNoChn","Nenalezen odpovídající televizní kanál");
define("_MsgXmlTvFormatErrorManyChn","Odpovídá více televizním kanálům");
define("_MsgXmlTvFormatErrorNoneChn","Nezadán televizní kanál");
define("_MsgXmlTvFormatErrorNoDateStart","Nezadán začátek pořadu");
define("_MsgXmlTvFormatErrorData","Textová data, které nelze přiřadit k žádnému tagu");
define("_MsgXmlTvFormatErrorNotAll","Nejsou zadány všechny povinné hodnoty");
define("_MsgXmlTvIgnored","Ignorovan, duplicita");
define("_MsgXmlTvInserted","Vlozen");
define("_MsgXmlTvSuccess;","Tv program úspěšně aktualizován");
define("_MsgXmlTvFailed;","Tv program aktualizován s chybami");
define("_MsgXmlTvUpdated","Změněn");
define("_MsgSetupRecordTimeAfterLast","Jak dlouho nahrávat pořad pokud neznáme následující (třeba poslední pořad v noci má následující až druhý den ráno) [s]");
define("_MsgSetupGrabStorageMinSize","Minimální množství místa na grabovacím disku, při kterém začneme promazávat starší graby");
define("_MsgSetupGrabBackendLang","Jazyk používaný v backend skriptech (cs,en,fr,..)");
define("_MsgSetupBackendStripDiacritics","Zda se má do názvů grabů vkládat název pořadu bez diakritiky nebo jen id");
define("_MsgSetupUserInactivityLimit","Po kolika dnech neaktivity bude uživatelský účet zrušen");
define("_MsgSetupCronList","Následující text vložte do konfigurace cron démona (crontab -e)");
define("_MsgSetupAuth","Dotazy do externí databáze pro ověřování uživatelů. Registrace poté ověří zda takový uživatel existuje v externí databázi a poté není heslo ukládáno lokálně v databázi dvbgrabu, ale používá se vždy z externí.");
define("_MsgSetupAuthDbUsed","Používat externí databázi nebo ukládat uživatele i s heslem do vlastní userinfo tabulky (0 nepoužít, 1 použít");
define("_MsgSetupAuthDbUsedOnly","Povolit registraci a používání DVBgrabu POUZE uživatelům z externí databáze (0 ne, 1 ano)");
define("_MsgSetupAuthDbSelect","SQL dotaz na uživatele, v tomto řetězci se nahradí 2 řetězce dvbgrab_username je nahrazeno zadaným uživatelským jménem a dvbgrab_password je md5 zadaného hesla.");
define("_MsgSetupAuthDbUserSelect","SQL dotaz na uživatele, jestli existuje, v tomto řetězci se nahradí pouze dvbgrab_username.");

define("_MsgAccountPassExternAuthNoChange","Heslo u externě ověřovaných uživatelů nelze tady měnit");
define("_MsgIndexLogFailExtern","Chyba při ověřování uživatele v externí databázi. Zadaná špatná kombinace jména a hesla.");
define("_MsgIndexLogFailExternName","Chyba při ověřování uživatele v externí databázi. Zadáno neexistující jméno");


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
define("_MsgJsonReqOutput","Název");
define("_MsgJsonReqOutputMd5","MD5");
define("_MsgJsonReqOutputEnc","Kodek");
define("_MsgJsonReqOutputSize","Velikost");


define("_MsgBackendAccountCleaned","Uživatelský účet byl zrušen. Počet dnů neaktivity po kterém se účty ruší:");
define("_MsgBackendAccountCleanedSub","Uživatelský účet byl zrušen");
define("_MsgBackendFilesizeWarningSize","Na disku pro uchovávání grabů dochází místo, začínají se mazat i graby, které by ještě měli zůstat dostupné");
define("_MsgBackendFilesizeWarningSizeSub","Není místo na disku pro graby");
define("_MsgAccountRemove","Úplně zrušit účet a všechny jeho graby");


zrusit
define("_MsgAccountUsername","Přihlašovací jméno:");
define("_MsgNews1","Repair/Add search possibility in tv program (Mostly used for series requests ;-))");
define("_MsgNews2","Number of showed records limited to 100.");
define("_MsgNews3","New option for sending new random password.");
define("_MsgNews4","New option \"Account\", for user account settings.");
define("_MsgNews5","Anniversery 100. user registred. No price because she has full mailbox.:-P");
define("_MsgNews6","New option to request record directly from search page.");
define("_MsgNews7","Bug hunting day!, few new function, new texts and <a href=\"anketa.php\">inquiry</a>");

