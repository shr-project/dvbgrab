<?php
require_once("language.inc.php");
require_once("config.php");

	$menu[1]=$msgMenuTvProgram;
	$menu[] =$msgMenuPlanSched;
	$menu[] =$msgMenuPlanDone;
	$menu[] =$msgMenuPlanMygrab;
	$menu[] =$msgMenuPlanAccount;
	$menu[] =" ";
	$menu[] =$msgMenuInfo;;
//	$menu[] =$msgMenuEmailUs;
	$menu[] =$msgMenuNews;
//	$menu[] =$msgMenuDocs;
//	$menu[] =$msgMenuLogout;
	
	$link[1]="tvprog.php";
	$link[] ="plan.php?type=sched";
	$link[] ="plan.php?type=done";
	$link[] ="plan.php?type=mygrab";
	$link[] ="account.php?action=edit";
	$link[] ="";
	$link[] ="info.php";
//	$link[] ="mailto:$admin_email";
	$link[] ="news.php";
//	$link[] ="http://martinja.mk.cvut.cz/tvgrab";
//	$link[] ="index.php?action=logout";
?>

<!-- menu -->
<table width="100%" cellpadding="10">
<tr>
	<td width="150" valign="top" align="center">
<?php
// hodnotu $menuitem predava skript, ktery tento soubor includuje
	for ($i=1; $i<=count($menu); $i++) {
        if (authenticated($_COOKIE["usr_id"], $_COOKIE["usr_pass"])) {
            if ($i == $menuitem) {
                echo "<div class=\"actual\"><a href=\"$link[$i]\">::&nbsp;$menu[$i]&nbsp;::</a></div><br>";       
            } else {
                echo ($menu[$i] == " ")?"<br><br>":"<a href=\"$link[$i]\">::&nbsp;$menu[$i]&nbsp;::</a><br><br>";
            }
        } else {
            echo ($menu[$i]==" ")?"<br><br>":"<i>::&nbsp;$menu[$i]&nbsp;::</i><br><br>";
        }
	}
?>
  </td>
<!-- konec menu -->
