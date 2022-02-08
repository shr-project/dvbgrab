<?php
require_once("config.php");

//--------------------------------------------------------------------
/**
 * Vraci pole poradu vysilanych na stanici v zadany den.
 * Format: pole[row] = {cas, nazev, popis}
 * @param id cislo stanice
 * @param offset pocet dnu od dneska
 * @return pole s televiznim programem nebo false
 */
function uget($id, $offset) {
    global $proxy_server;
    global $proxy_port;

    //-----------------------------------------------------------------
    // seznam.cz
    $url_date = date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $offset));
    $path = "http://www.novinky.cz/tv_program/tvindex.fcgi?akce=tv&subakce=nastav&tv_$id=on&tv_odkdy=0&datum=$url_date";

    $reg_exp = "@<dt>([0-9]+:[0-9]+)</dt>".
        "\s*<dd>".
        "(?:\s*<img .*/>)*".
        "\s*<span class=\"titulek\"\s*>(?:<a [^>]*>)*([^<]*)(?:</a>)*</span>".
        "\s*<span class=\"popisek\"\s*>(?: - )?([^<]*)</span>@";

    //-----------------------------------------------------------------

    if ($proxy_server != "") {
      $fp = fsockopen($proxy_server, $proxy_port, &$errno, &$errstr, 5);
      fputs($fp, "GET $path HTTP/1.0\n\n");
    } else {
      $fp = fopen($path, "r");
    }
    if (!$fp) {
        echo "$errstr ($errno)<br>";
        if ($proxy_server != "")
          echo '<br>!nejede proxy!<br>';
        else
          echo '<br>!nejede www!<br>';
        return false;
    } else {
        $output = '';
        while (!feof($fp)) {
            $output .= fread($fp, 8192);
        }
        fclose($fp);

        $result = array();
        $row = 0;
        while (preg_match($reg_exp, $output, $groups, PREG_OFFSET_CAPTURE)) {
            $result[$row]['cas'] = $groups[1][0];
            $result[$row]['nazev'] = $groups[2][0];
            $result[$row]['popis'] = $groups[3][0];
            $row++;

            $output = substr($output, $groups[3][1] + strlen($groups[3][0]));
        }

        return $result;
    }
}

?>

