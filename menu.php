<?php
require_once("language.inc.php");
require_once("config.php");
require_once("authentication.php");

  $menu[1]=_MsgMenuTvProgram;
  $menu[] =_MsgMenuPlanSched;
  $menu[] =_MsgMenuPlanDone;
  $menu[] =_MsgMenuPlanMygrab;
  $menu[] =_MsgMenuPlanAccount;
  $menu[] =" ";
  $menu[] =_MsgMenuEmailUs;
  $menu[] =_MsgMenuNews;
	
  $link[1]="tvprog.php";
  $link[] ="plan.php?type=sched";
  $link[] ="plan.php?type=done";
  $link[] ="plan.php?type=mygrab";
  $link[] ="account.php?action=edit";
  $link[] ="";
  $link[] ="mailto:"._Config_admin_email;
  $link[] ="news.php";
?>

<!-- menu -->
<?php
// hodnotu $menuitem predava skript, ktery tento soubor includuje
  for ($i=1; $i<=count($menu); $i++) {
    if ($i == $menuitem) {
      $class="menuitem_act";
    } else {
      $class="menuitem";
    }
    echo '<p class="'.$class.'">';
    if (authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"]) || $i>=7) {
      echo ($menu[$i] == " ")?"&nbsp;":"<a class=\"$class\" href=\"$link[$i]\">::&nbsp;$menu[$i]&nbsp;::</a>\n";
    } else {
      echo ($menu[$i] == " ")?"&nbsp;":"<i>::&nbsp;$menu[$i]&nbsp;::</i>\n";
    }
    echo '</p>';
  }
?>
<!-- konec menu -->
