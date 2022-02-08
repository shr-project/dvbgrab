<?php
  $menu[1]="Summary";
  $menu[] ="SourceForge Page";
  $menu[] ="Download";
  $menu[] =" ";
  $menu[] ="Contact";
	
  $link[1]="index.php";
  $link[] ="http://sourceforge.net/projects/dvbgrab/";
  $link[] ="http://sourceforge.net/project/showfiles.php?group_id=184176";
  $link[] ="";
  $link[] ="mailto:jamae@users.sourceforge.net";
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
      echo ($menu[$i] == " ")?"&nbsp;":"<a class=\"$class\" href=\"$link[$i]\">::&nbsp;$menu[$i]&nbsp;::</a>\n";
    echo '</p>';
  }
?>
<!-- konec menu -->
