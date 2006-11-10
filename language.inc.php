<?
require_once("language.accept.inc.php");
require_once("config.php");
// nastaveni jazykove verze (precteni udaje nastaveni prohlizece)

if (!empty($_GET['lang'])) {
  $userLang = $_GET['lang'];
  setcookie("lang", $userLang, time()+60*60*24*365*2);
} else if (!empty($_COOKIE['lang'])) {
  $userLang = $_COOKIE['lang'];
} else {
  $langs=array('cs_CZ.UTF-8','en_US.UTF-8');
  $locale=al2gt($langs, 'text/html');
  #setlocale('LC_ALL', $locale);
  $userLang=preg_replace("/\_.*/", "", $locale);
}
if (!empty($userLang)) {
  require_once("lang/lang.$userLang.inc.php");
} else {
  require_once("lang/lang."._Config_grab_backend_lang.".inc.php");
}
?>
