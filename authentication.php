<?php
require_once("language.inc.php");
require_once("dolib.inc.php");

// autentizuje uzivatele
function authenticated($usr_id, $usr_pass_md5) {
  $SQL = "select usr_id from userinfo
          where usr_id=".(int)$usr_id." and
            md5(usr_pass)='$usr_pass_md5'";
  $rs = do_sql($SQL);
  return ($rs->recordCount() == 1);
}

// naloguje uzivatele
function login($usr_name, $usr_pass) {
  $SQL = "select usr_id from userinfo 
          where usr_name='$usr_name' and
            usr_pass='$usr_pass'";
  $rs = do_sql($SQL);
  if ($row = $rs->FetchRow()) {
    setcookie("usr_id", $row[0], time()+60*60*24*365*2);
    setcookie("usr_pass", md5($usr_pass), time()+60*60*24*365*2);
    return true;
  } else {
    return false;
  }
}

// odloguje uzivatele
function logout() {
  setcookie("usr_id","", time()-3600);
  setcookie("usr_pass","", time()-3600);
}

/**
 * Removes all evil characters from username.
 * @return lowercased string with only alphanumeric characters
 */
function safeUsername($value) {
    return ereg_replace("[^[:alnum:]]", "", strtolower($value));
}

?>
