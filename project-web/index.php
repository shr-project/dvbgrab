<?php
require_once("header.php");

echo "<h2>Welcome on DVBgrab pages.</h2>\n";
echo "<p>DVBgrab is a web-based application allowing registered users to request any tv show to record. When it's done download link is sent to user's e-mail. Tv schedule is from XMLTV, tv stream is saved with dumprtp and encoding is provided with mencoder.</p>\n";

?>
Stable version is dvbgrab-1.0<br />
<code class="left">
svn export --username anonymous https://dvbgrab.svn.sourceforge.net/svnroot/dvbgrab/tags/dvbgrab-1.0 dvbgrab
</code>
<br />
Recommended version dvbgrab-2.0<br />
<code class="left">
svn export --username anonymous https://dvbgrab.svn.sourceforge.net/svnroot/dvbgrab/tags/dvbgrab-2.0 dvbgrab
</code>
<br />
Development trunk<br />
<code class="left">
svn export --username anonymous https://dvbgrab.svn.sourceforge.net/svnroot/dvbgrab/trunk dvbgrab
</code>
<br />
<a href="http://dvbgrab.svn.sourceforge.net/viewvc/dvbgrab/">Browse Subversion Repository</a><br />
<?
require_once("footer.php");
?>
