<?php
//--------------------------------------------------------------------
/**
 * Vraci pole poradu vysilanych na stanici v zadany den.
 * Format: pole[row] = {cas, nazev, popis}
 * @param id cislo stanice
 * @param offset pocet dnu od dneska
 * @return pole s televiznim programem nebo false
 */
function uget($id, $offset) {
    $uget_row_cas = 0;
    $uget_row_nazev = 1;
    $uget_row_popis = 2;

    //-----------------------------------------------------------------
    /*
    // tvtip.tiscali.cz
    $path = "http://tvtip.tiscali.cz/print.asp?stanice=$id&dny=$offset";
    $reg_exp = "<b>([0-9]+:[0-9]+)</b>.*<b>(.*)</b><br>"
    ."<span class='popis'>(.*)</span>";
     */
    //-----------------------------------------------------------------
    // http://www.ceskenoviny.cz/kultura/tvpr
    //FIXME: ud¾et konzistenci s databází
    switch ($id) {
        case 1: $stanice_name = "ÈT1"; break;
        case 2: $stanice_name = "ÈT2"; break;
        case 3: $stanice_name = "Nova"; break;
        case 4: $stanice_name = "Prima"; break;
//	case 5: $stanice_name = "Ocko"; break;
        default: return false;
    }
    $url_den = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + $offset));
    $path = "http://www.ceskenoviny.cz/kultura/tvpr/index.php?stanicex%5B1%5D=stanice1&stanice1=$stanice_name&tv=6&dnygrp=5&datumod=$url_den&datumdo=$url_den&hodinygrp=1&casod=5&casdo=5&zobraz=1&findStr=&hledejV=1";
    $reg_exp = "<td class=\"tvp_cas[0-9]*\"[^>]*>([0-9]+:[0-9]+)</td>\n".
        " *<td class=\"tvp_popis[0-9]*\"><b>(.*)</b>(.*)</td>";
    //-----------------------------------------------------------------

    if ($proxy_server != "")
      $fp = fsockopen($proxy_server, 3128, &$errno, &$errstr, 5);
    else
      $fp = fsockopen('www.ceskenoviny.cz', 80, &$errno, &$errstr, 5);
    if (!$fp) {
        echo '$errstr ($errno)<br>';
        if ($proxy_server != "")
          echo '<br>!nejede proxy!<br>';
        else
          echo '<br>!nejede www!<br>';
        return false;
    } else {
        fputs($fp, 'GET '.$path." HTTP/1.0\n\n");
        if ($fp > 0) {

            //NOTE: podle poctu "\n" v reg_vyrazu pozname, kolik radku nacist
            $ln_count = substr_count($reg_exp, "\n");
            $output = '';
            // prednacteme $ln_count radek
            for ($i = 0; $i < $ln_count; $i++) {
                $output .= fgets($fp, 4096);
            }

            $result = array();
            $row = 0;
            while (!feof($fp)) {
                $output .= fgets($fp, 4096);

                if (ereg($reg_exp, $output, $groups)) {
                    $result[$row]['cas'] = $groups[1];
                    $result[$row]['nazev'] = $groups[2];
                    $result[$row]['popis'] = $groups[3];
                    $row++;
                }

                // oriznuti prvni radky v output
                $first = strchr($output, "\n");
                if ($first != false) {
                    $output = substr($first, 1);
                }
            }
            fclose($fp);
            return $result;
        }
        else {
            fclose($fp);
            echo "<br>!nejede $path!<br>";
            return false;
        }
    }
}

?>

