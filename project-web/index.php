<?php
require_once("header.php");

echo "<h2>Welcome on DVBgrab pages.</h2>\n";
echo "<p>DVBgrab is a web-based application allowing registered users to request any tv show to record. When it's done download link is sent to user's e-mail. Tv schedule is from XMLTV, tv stream is saved with dumprtp and encoding is provided with mencoder.</p>\n";

?>
Download:

<a href="http://downloads.sourceforge.net/dvbgrab/dvbgrab-1.0.tar.gz?modtime=1134057187&big_mirror=0">dvbgrab-1.0.tar.gz (old version)</a>
<br />
<a href="http://downloads.sourceforge.net/dvbgrab/dvbgrab-2.0.tar.gz?modtime=1134057187&big_mirror=0">dvbgrab-1.0.tar.gz (recommended version)</a>
<br />

Or export from sourceforge.net subversion:

dvbgrab-1.0<br />
<code class="left">
svn export --username anonymous https://dvbgrab.svn.sourceforge.net/svnroot/dvbgrab/tags/dvbgrab-1.0 dvbgrab
</code>
<br />
dvbgrab-2.0<br />
<code class="left">
svn export --username anonymous https://dvbgrab.svn.sourceforge.net/svnroot/dvbgrab/tags/dvbgrab-2.0 dvbgrab
</code>
<br />
development trunk<br />
<code class="left">
svn export --username anonymous https://dvbgrab.svn.sourceforge.net/svnroot/dvbgrab/trunk dvbgrab
</code>
<br />
<a href="http://dvbgrab.svn.sourceforge.net/viewvc/dvbgrab/">Browse Subversion Repository</a><br />
<?
require_once("footer.php");
?>
