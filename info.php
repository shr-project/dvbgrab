<?php
require("authentication.php");
require_once("dblib.php");
require_once("language.inc.php");
require("header.php");

$menuitem = 7;
require("menu.php");
?>

<td valign="top">
<h2>Tvgrab</h2>


<h3>Co to je</h3>

<p>Projekt Tvgrab si klade za cíl vytvoøit a následnì poskytnout u¾ivateli graby televizních poøadù podle jeho po¾adavkù.</p>

<p>U¾ivatel se pøihlásí do aplikace pøes webové rozhraní, vybere si televizní poøady, o které má zájem a zadá po¾adavek na grabnutí. Pøed zaèátkem poøadu se spustí grabovací proces, který poøad ulo¾í do souboru. Výsledný soubor se volitelnì dále zpracuje (zmìna rozli¹ení, kodeku). Pak je soubor ulo¾en na ftp server a u¾ivateli je zaslán email o dokonèení grabu s informací, jak lze grab stáhnout.</p>

<h3>Lze stáhnout ostatní hotové graby?</h3>
<p>Nelze. Ka¾dý si mù¾e stáhnout pouze vlastnoruènì naklikané graby. Autorský zákon neumo¾nuje ¹íøit rozmno¾eniny kromì zhotovení si záznamu pro osobní potøebu.
</p>
<!--
Autorský zákon:
http://portal.gov.cz/wps/WPS_PA_2001/jsp/download.jsp?s=1&l=398%2F2006

§ 20: Provozování ze záznamu a jeho pøenos
- Vysílání televize je vyjímka.

§ 21: Vysílání rozhlasem nebo televizí

§ 30: Volná pou¾ití
- Odst. 2:
Dovoluje zhotovení záznamu pro osobní potøebu.
- Odst. 3:
Nelze poøídit záznam díla pøi jeho provozování ze záznamu nebo jeho pøenosu.
Vysílání televize v¹ak patøí do jiné skupiny.

-->

<h3>Proè mi nelze stáhnout grab v Internet Exploreru?</h3>
<p>IE se ptá na FTP heslo pouze, kdy¾ je za¹krtnuta volba <i>Tools/Internet Options/Advanced/Enable Folder View For FTP</i>. Zde je mo¾no také za¹ktnout <i>Use Passive FTP</i>.
</p>
<p>Nebo si udìlejte radost a pou¾ijte <a href="http://firefox.czilla.cz/">Firefox</a>.</p>

<h3>Jak to funguje</h3>

<p>Tvgrab bì¾í na poèítaèi s Athlonem 1.8GHz, 1GB RAM a 1,1TB prostorem na graby. Pro pøíjem televizního signálu je pou¾ito multicast vysílání ze stroje <a href="http://televize.sh.cvut.cz">dvb.sh.cvut.cz</a>. Díky tomu je mo¾né zároveò grabovat nìkolik poøadù z rùzných televizních stanic. Grabování poøadu je spu¹tìno 3 minuty pøed jeho zaèátkem v tv programu a ukonèeno 10 a¾ 30 minut po jeho konci. Tyto pøesahy jsou zde, proto¾e vysílání poøadù bývá èasto èasovì posunuté oproti tv programu. U¾ivatel si v nabídce Nastavení mù¾e zvolit v jak vysoké kvalitì (rozli¹ení) bude chtít výsledný grab. K hotovému grabu mají pøes ftp pøístup pouze ti u¾ivatelé, kteøí si grab zadali.</p>

<p>Pou¾ité technologie: Debian GNU/Linux, Apache, PHP, Python, Java, MySQL, mencoder, vsftpd</p>

<br>
<br>
<br>

<p>Mnoho pøíjemných zá¾itkù s Tvgrabem pøeje <a href="mailto:<?=$admin_email ?>">realizaèní tým</a>:</p>

<p>
Ing. Pavel Danihelka - vedoucí projektu, PHP, backend skripty<br>
Ing. Ivo Danihelka - hlavní programátor, PHP, python<br>
Martin Jansa - externí programátor, grabování DVB<br>
Ing. Statopluk Fronk - programátor, prvotní verze<br>
Ing. Petr Krajcr - webmaster, dokumentarista, QA
</p>

</td>
<?php
require("footer.php");

// vim: noexpandtab tabstop=4
?>
