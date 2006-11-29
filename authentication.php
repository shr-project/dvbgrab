<?php
require_once("language.inc.php");
require_once("dolib.inc.php");

// autentizuje uzivatele
function authenticated($usr_id, $usr_pass_md5) {
  if (_Config_auth_db_used == '1') {
    $SQL = "select usr_name from userinfo where usr_id=".(int)$usr_id;
    $rs = do_sql($SQL);
    if ($row = $rs->FetchRow()) {
      $usr_name = $row[0];
      if (autenticatedExistExtern($usr_name)) {
        if (authenticatedExtern($usr_name,$usr_pass_md5)) {
          return true;
        }
      }
      if (_Config_auth_db_used_only == '1') {
        return false;
      }
    } else {
      return false; // uzivatel s usr_id vubec neexistuje
    }
  }

  $SQL = "select usr_id from userinfo
          where usr_id=".(int)$usr_id." and
            usr_pass='$usr_pass_md5'";
  $rs = do_sql($SQL);
  return ($rs->recordCount() == 1);
}

function authenticatedExtern($usr_name,$usr_pass_md5) {
  $SQL = str_replace('dvbgrab_username',$usr_name,_Config_auth_db_select);
  $SQL = str_replace('dvbgrab_password',$usr_pass_md5,$SQL);
  $rs = do_extern_sql($SQL);
  return ($rs->recordCount() == 1);
}

function autenticatedExistExtern($usr_name) {
  $SQL = str_replace('dvbgrab_username',$usr_name,_Config_auth_db_user_select);
  $rs = do_extern_sql($SQL);
  // uzivatel existuje tudiz se musi overovat tam
  return ($rs->FetchRow()); 
}

function authenticatedUser($usr_name,$usr_pass_md5) {
  if (_Config_auth_db_used == '1') {
    if (autenticatedExistExtern($usr_name)) {
      if (authenticatedExtern($usr_name,$usr_pass_md5)) {
        return true;
      }
    }
    if (_Config_auth_db_used_only == '1') {
      return false;
    }
  }
  $SQL = "select usr_id from userinfo
            where usr_name='$usr_name' and
              usr_pass='$usr_pass_md5'";
  $rs = do_sql($SQL);
  return ($rs->recordCount() == 1);
}


// naloguje uzivatele
function login($usr_name, $usr_pass_md5) {
  if (authenticatedUser($usr_name,$usr_pass_md5)) {
    setcookie("usr_id", $row[0], time()+60*60*24*365*2);
    setcookie("usr_pass", $usr_pass_md5, time()+60*60*24*365*2);
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
