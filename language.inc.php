<?
require_once("language.accept.inc.php");
require_once("config.php");
require_once("dolib.inc.php");
require_once("authentication.php");

// nastaveni jazykove verze (precteni udaje nastaveni prohlizece)

if (!empty($_GET['lang'])) {
  $userLang = $_GET['lang'];
  setcookie("lang", $userLang, time()+60*60*24*365*2);
  if (authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
    $usr_id=$_COOKIE["usr_id"];
    $SQL = "update userinfo set usr_lang='$userLang' where usr_id=$usr_id";
    do_sql($SQL);
  }
} else if (!empty($_COOKIE['lang'])) {
  $userLang = $_COOKIE['lang'];
} else {
  if (authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
    $usr_id=$_COOKIE["usr_id"];
    $SQL = "select usr_lang from userinfo where usr_id=$usr_id";
    $rs = do_sql($SQL);
    if ($row = $rs->FetchRow()) {
      $userLang=$row[0];
    }
  }
  if (empty($userLang)) {
    $langs=array('cs_CZ.UTF-8','en_US.UTF-8');
    $locale=al2gt($langs, 'text/html');
    #setlocale('LC_ALL', $locale);
    $userLang=preg_replace("/\_.*/", "", $locale);
  }
}
if (!empty($userLang)) {
  require_once("lang/lang.$userLang.inc.php");
} else {
  require_once("lang/lang."._Config_grab_backend_lang.".inc.php");
}
?>
