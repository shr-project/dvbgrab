<?php
require_once("dolib.inc.php");
require_once("view.inc.php");
require_once("account.inc.php");
require_once("status.inc.php");

function grabAction($action,$query,$tv_date,$tel_id,$grb_id) {
  global $DB;
  global $usr_id;
  $addition = "tv_date=$tv_date&query=$query&";
  // nejdrive od ted
  $grab_time_limit = time()-30*60;

  switch ($action) {
    // zadani noveho grabu
    case "grab_add":
      // zjisti, zda porad existuje
      $SQL = "select t.tel_date_start, t.chn_id, g.grb_id
              from television t left join grab g on (t.tel_id=g.tel_id) 
              where t.tel_id=$tel_id";

      $rs = do_sql($SQL);
      $row = $rs->FetchRow();
      $tel_date_start = $row[0];
      $tel_chn_id = $row[1];
      $tel_grb_id = $row[2];
      if (!$row) {
        // porad s $tel_id neexistuje
        header("Location:$PHP_SELF?msg=grb_add_fail_tel&$addition#$tel_id");
        return;
      }
    
      // uzivatel vycerpal tydenni kvotu na graby
      if (get_user_grab($usr_id, $DB->UserDate($tel_date_start,"W")) >= _Config_grab_quota) {
        header("Location:$PHP_SELF?msg=grb_add_fail_quota&$addition#$tel_id");
        return;
      }
      // pozadavek o grab na uz odvysilany porad
      if ($DB->UnixTimeStamp($tel_date_start)<$grab_time_limit) {
        header("Location:$PHP_SELF?msg=grb_add_fail_time&$addition#$tel_id");
        return;
      }
      // grab jiz existuje, pridat dalsiho usera
      if ($tel_grb_id) {
        // check for duplicate request of the same user
        $SQL = "select * from request
                where grb_id = $tel_grb_id and usr_id=$usr_id";
        $rs_check = do_sql($SQL);
        if ($rs_check->RecordCount() == 0) {
          $SQL = "insert into request(grb_id,usr_id,req_status) 
                              VALUES ($tel_grb_id, $usr_id, 'scheduled')";
          do_sql($SQL);
          header("Location:$PHP_SELF?msg=grb_add_ok&$addition#$tel_id");
          return;
        } else {
          header("Location:$PHP_SELF?msg=grb_add_fail_exist&$addition#$tel_id");
          return;
        }
      // grab neexistuje a muzeme ho zadat
      } else {
        // zjisti cas nasledujiciho poradu na danem kanale -> to bude cas pro skonceni grabu
        $SQL = "select tel_date_start from television 
                where chn_id=$tel_chn_id and tel_date_start>'$tel_date_start'
                order by tel_date_start
                limit 1";
        $rs = do_sql($SQL);
        $row = $rs->FetchRow();
        if (!$row) {
          header("Location:$PHP_SELF?msg=grb_add_fail_tel&$addition#$tel_id");
          return;
        }
        $grb_date_start=$DB->UnixTimeStamp($tel_date_start)-_Config_grab_date_start_shift*60;
        $grb_date_stop=$DB->UnixTimeStamp($row[0])+_Config_grab_date_stop_shift*60;
        $grb_length=$grb_date_stop-$grb_date_start;
        $grb_date_start_hour=date('G',$grb_date_start);
        if ($grb_length>4*3600 and $grb_date_start_hour > 1 and $grb_date_start_hour < 5) {
          // porad je delsi nez 4 hodiny a je v noci
          // nemame k dispozici nasledujici porad (dalsi je az dalsi den rano)
          // omezime delku poradu na 2 hodiny
          $grb_date_stop = $grb_date_start+2*3600;
        }
        // zadame grab
        $SQL = "insert into grab(tel_id,grb_date_start,grb_date_end) 
                         VALUES ($tel_id, ".$DB->DBDate($grb_date_start).", ".$DB->DBDate($grb_date_stop).")";
        do_sql($SQL);
        // zjistime jeho grb_id
        $SQL = "select grb_id from grab 
                where tel_id=$tel_id";
        $rs = do_sql($SQL);
        $row = $rs->FetchRow();
         
        $SQL = "insert into request (grb_id,usr_id,req_status)
                             VALUES ($row[0], $usr_id,'scheduled')";
        do_sql($SQL);
        header("Location:$PHP_SELF?msg=grb_add_ok&$addition#$tel_id");
        return;
      }
    break;

  case "grab_del":
    // zjisti, zda grab existuje
    $SQL = "select g.tel_id, r.req_status, r.usr_id
            from grab g, request r
            where g.grb_id=r.grb_id and g.grb_id=$grb_id";
    // grab existuje
    $rs = do_sql($SQL);    
    $row = $rs->FetchRow();
    // grab s $grb_id neexistuje
    if (!$row) {
      header("Location:$PHP_SELF?msg=grb_del_fail_exist&$addition");
      return;
    }
    // grab uz skoncil nebo probiha
    if ($row[1] != 'scheduled') {
      header("Location:$PHP_SELF?msg=grb_del_fail_time&$addition#$row[0]");
      return;
    }

    while ($row[2] != $usr_id) {
      if (!($row = $rs->FetchRow())) {
        // nejedna se o muj grab
        header("Location:$PHP_SELF?msg=grb_del_fail_owner&$addition#$row[0]");
        return;
      }
    }
    if (($rs->RecordCount()) == 1) {
      // zadal jsem o ten porad jediny
      $SQL = "delete from request where grb_id=$grb_id";
      do_sql($SQL);
      $SQL = "delete from grab where grb_id=$grb_id";
      do_sql($SQL);
    } else {
      // ne je nas vic, takze jenom odeberu muj request
      $SQL = "delete from request
              where grb_id=$grb_id and usr_id=$usr_id";
      do_sql($SQL);
    }

    header("Location:$PHP_SELF?msg=grb_del_ok&$addition#$row[0]");
    return;
    break;
  default:
  }
}

function indexAction($action,$query,$tv_date,$tel_id) {
  global $DB;
switch ($action) {
  // uzivatel se chce prilogovat, pokusime se ho autentizovat
  case "login":
    if (login(safeUsername($_POST["usr_name"]), $_POST["usr_pass"])) {
      header("Location:$PHP_SELF?msg=log_ok");
      return;
    } else {
      header("Location:$PHP_SELF?msg=log_fail");
      return;
    }
    break;

  // odlogovani uzivatele
  case "logout":
    logout();
    header("Location:$PHP_SELF?msg=logout");
    return;
    break;

  // registrace noveho uzivatele
  case "register":
    $usr_ip=getUserIp();    

    $SQL = "select usr_name from usergrb where usr_ip='$usr_ip'";
    $rs = do_sql($SQL);
    if ($rs->rowCount() > 0) {
      header("Location:$PHP_SELF?msg=reg_fail_ip");
      return;
    }
    
    // zkontrolujeme, zda bylo zadano jmeno, heslo a email
    $usr_name = safeUsername($_POST["usr_name"]);
    $usr_pass = $_POST["usr_pass1"];
    $usr_email = $_POST["usr_email"];
    if ($usr_name == "" || $usr_pass == "" || $usr_email == "") {
      header("Location:$PHP_SELF?msg=reg_fail_data");
      return;
    }

    // zkontrolujeme format emailove adresy
    if (!eregi("^[a-zA-Z0-9_\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $usr_email)) {
      header("Location:$PHP_SELF?msg=reg_fail_email");
      return;
    }

    // zkontrolujeme, zda obe zadana hesla jsou totozna
    if ($usr_pass != $_POST["usr_pass2"]) {
      header("Location:$PHP_SELF?msg=reg_fail_pass");
      return;
    }

    // zkontrolujeme, zda uzivatel daneho jmena uz neexistuje
    $SQL = "select usr_id from usergrb where usr_name='$usr_name'";
    $rs = do_sql($SQL);
    if ($rs->rowCount() == 1) {
      header("Location:$PHP_SELF?msg=reg_fail_name");
      return;
    }

    // zaregistrujeme noveho uzivatele
    $usr_icq = (int)$_POST["usr_icq"];
    $usr_jabber = $_POST["usr_jabber"];
    $SQL = "insert into usergrb(usr_name,usr_pass,usr_email,usr_icq,usr_jabber,usr_ip,usr_last_activity)
                        VALUES('$usr_name','$usr_pass','$usr_email','$usr_icq','$usr_jabber','$usr_ip','.$DB->sysTimeStamp.')";
    do_sql($SQL);

    // a hned ho zaloguj
    login($usr_name, $usr_pass);
    header("Location:$PHP_SELF?msg=reg_ok");
    return;
    break;
  default:
  }
}

function printMsg($msg) {
  switch ($msg)  {
    case "log_fail":
      $txtmsg = "<p class=\"warning\">"._MsgIndexLogFail."</p>";
      $almsg=_MsgIndexLogFail;
      break;
    case "log_ok":
      $txtmsg = "<p class=\"info\">"._MsgIndexUser." $usr_name "._MsgIndexLogOk."</p>";
      $almsg=_MsgIndexUser." $usr_name "._MsgIndexLogOk;
      break;
    case "logout":
      $txtmsg = "<p class=\"info\">"._MsgIndexLogout."</p>";
      $almsg=_MsgIndexLogout;
      break;
    case "reg_fail_ip":
      $txtmsg = "<p class=\"warning\">"._MsgIndexRegFailIp."</p>";
      $almsg=_MsgIndexRegFailIp;
      break;
    case "reg_fail_data":
      $txtmsg = "<p class=\"warning\">"._MsgIndexRegFailData."</p>";
      $almsg=_MsgIndexRegFailData;
      break;
    case "reg_fail_email":
      $txtmsg = "<p class=\"warning\">"._MsgIndexRegFailEmail."</p>";
      $almsg=_MsgIndexRegFailEmail;
      break;
    case "reg_fail_pass":
      $txtmsg = "<p class=\"warning\">"._MsgIndexRegFailPass."</p>";
      $almsg = _MsgIndexRegFailPass;
      break;
    case "reg_fail_name":
      $txtmsg = "<p class=\"warning\">"._MsgIndexRegFailName."</p>";
      $almsg=_MsgIndexRegFailName;
      break;
    case "reg_ok":
      $txtmsg = "<p class=\"info\">"._MsgIndexUser." $usr_name "._MsgIndexRegOk."</p>";
      $almsg=_MsgIndexUser." $usr_name "._MsgIndexRegOk;
      break;
    case "grb_add_fail_quota":
      $txtmsg = "<p class=\"warning\">"._MsgGrabFailAddQuota."</p>";
      $almsg=_MsgGrabFailAddQuota;
      break;
    case "grb_add_fail_time":
      $txtmsg = "<p class=\"warning\">"._MsgGrabFailAddTime."</p>";
      $almsg=_MsgGrabFailAddTime;
      break;
    case "grb_add_fail_exist":
      $txtmsg = "<p class=\"warning\">"._MsgGrabFailAddExist."</p>";
      $almsg=_MsgGrabFailAddExist;
      break;
    case "grb_add_fail_tel":
      $txtmsg = "<p class=\"warning\">"._MsgGrabFailAddTel."</p>";
      $almsg=_MsgGrabFailAddTel;
      break;
    case "grb_del_fail_time":
      $txtmsg = "<p class=\"warning\">"._MsgGrabFailDelTime."</p>";
      $almsg=_MsgGrabFailDelTime;
      break;
    case "grb_del_fail_exist":
      $txtmsg = "<p class=\"warning\">"._MsgGrabFailDelExist."</p>";
      $almsg=_MsgGrabFailDelExist;
      break;
/*
    case "grb_add_ok":
      $txtmsg = "<p class=\"warning\">"._MsgGrabAddOk."</p>";
      $almsg=_MsgGrabAddOk;
      break;
    case "grb_del_ok":
      $txtmsg = "<p class=\"warning\">"._MsgGrabDelOk."</p>";
      $almsg=_MsgGrabDelOk;
      break;
*/
    default:
      return;
      break;
  }
  echo "<script type=\"text/javascript\">\n";
  echo "<!--\n";
  echo "alert(\"$almsg\");\n";
  echo "//-->\n";
  echo "</script>\n";
  echo "<noscript>\n";
  echo "$txtmsg\n";
  echo "</noscript>\n";
}
?>
