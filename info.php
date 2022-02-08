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

<p>Projekt Tvgrab si klade za c�l vytvo�it a n�sledn� poskytnout u�ivateli graby televizn�ch po�ad� podle jeho po�adavk�.</p>

<p>U�ivatel se p�ihl�s� do aplikace p�es webov� rozhran�, vybere si televizn� po�ady, o kter� m� z�jem a zad� po�adavek na grabnut�. P�ed za��tkem po�adu se spust� grabovac� proces, kter� po�ad ulo�� do souboru. V�sledn� soubor se voliteln� d�le zpracuje (zm�na rozli�en�, kodeku). Pak je soubor ulo�en na ftp server a u�ivateli je zasl�n email o dokon�en� grabu s informac�, jak lze grab st�hnout.</p>

<h3>Lze st�hnout ostatn� hotov� graby?</h3>
<p>Nelze. Ka�d� si m��e st�hnout pouze vlastnoru�n� naklikan� graby. Autorsk� z�kon neumo�nuje ���it rozmno�eniny krom� zhotoven� si z�znamu pro osobn� pot�ebu.
</p>
<!--
Autorsk� z�kon:
http://portal.gov.cz/wps/WPS_PA_2001/jsp/download.jsp?s=1&l=398%2F2006

� 20: Provozov�n� ze z�znamu a jeho p�enos
- Vys�l�n� televize je vyj�mka.

� 21: Vys�l�n� rozhlasem nebo televiz�

� 30: Voln� pou�it�
- Odst. 2:
Dovoluje zhotoven� z�znamu pro osobn� pot�ebu.
- Odst. 3:
Nelze po��dit z�znam d�la p�i jeho provozov�n� ze z�znamu nebo jeho p�enosu.
Vys�l�n� televize v�ak pat�� do jin� skupiny.

-->

<h3>Pro� mi nelze st�hnout grab v Internet Exploreru?</h3>
<p>IE se pt� na FTP heslo pouze, kdy� je za�krtnuta volba <i>Tools/Internet Options/Advanced/Enable Folder View For FTP</i>. Zde je mo�no tak� za�ktnout <i>Use Passive FTP</i>.
</p>
<p>Nebo si ud�lejte radost a pou�ijte <a href="http://firefox.czilla.cz/">Firefox</a>.</p>

<h3>Jak to funguje</h3>

<p>Tvgrab b�� na po��ta�i s Athlonem 1.8GHz, 1GB RAM a 1,1TB prostorem na graby. Pro p��jem televizn�ho sign�lu je pou�ito multicast vys�l�n� ze stroje <a href="http://televize.sh.cvut.cz">dvb.sh.cvut.cz</a>. D�ky tomu je mo�n� z�rove� grabovat n�kolik po�ad� z r�zn�ch televizn�ch stanic. Grabov�n� po�adu je spu�t�no 3 minuty p�ed jeho za��tkem v tv programu a ukon�eno 10 a� 30 minut po jeho konci. Tyto p�esahy jsou zde, proto�e vys�l�n� po�ad� b�v� �asto �asov� posunut� oproti tv programu. U�ivatel si v nab�dce Nastaven� m��e zvolit v jak vysok� kvalit� (rozli�en�) bude cht�t v�sledn� grab. K hotov�mu grabu maj� p�es ftp p��stup pouze ti u�ivatel�, kte�� si grab zadali.</p>

<p>Pou�it� technologie: Debian GNU/Linux, Apache, PHP, Python, Java, MySQL, mencoder, vsftpd</p>

<br>
<br>
<br>

<p>Mnoho p��jemn�ch z�itk� s Tvgrabem p�eje <a href="mailto:<?=$admin_email ?>">realiza�n� t�m</a>:</p>

<p>
Ing. Pavel Danihelka - vedouc� projektu, PHP, backend skripty<br>
Ing. Ivo Danihelka - hlavn� program�tor, PHP, python<br>
Martin Jansa - extern� program�tor, grabov�n� DVB<br>
Ing. Statopluk Fronk - program�tor, prvotn� verze<br>
Ing. Petr Krajcr - webmaster, dokumentarista, QA
</p>

</td>
<?php
require("footer.php");

// vim: noexpandtab tabstop=4
?>
