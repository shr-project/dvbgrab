#!/usr/bin/php4 -q
<?php
set_time_limit(18000);
require_once("dblib.php");
require_once("funkce.inc.php");
require_once("uget.inc.php");
require_once("config.php");
require_once("status.inc.php");
require_once("authentication.php");
require_once("header.php");

//-----------------------------------------------------------------
/**
 * Odstrani ze stringu html entity
 * a pripravi pro ulozeni do mysql.
 * @param nebezpecny string
 * @return priraveny string
 */
function ready_television($string)
{
    $result = $string;
    $result = cp1250_to_lat2($result);
    $result = ereg_replace ("<[^>]*>", "", $result);
    //NOTE: tvtip.tiscali.cz pisi misto otazniku "&#x4c;"
    $result = ereg_replace ("&#x4c;", "?", $result);
    $result = ereg_replace ("&amp;", "&", $result);
    $result = ereg_replace ("&quot;", "\"", $result);
    $result = ereg_replace ("&#039;", "'", $result);
    $result = ereg_replace ("&lt;", "<", $result);
    $result = ereg_replace ("&gt;", ">", $result);
    $result = ereg_replace ("…", "...", $result);
    //NOTE: hmlspecialchars je nutno aplikovat pri zobrazovani
    //$result = htmlspecialchars($result);
//    $result = readymysql_fp($result);
    return $result;
}
//-----------------------------------------------------------------
/**
 * Ulozi do databaze televizni program stanice na dany den.
 * Zaznamenay program take vypisuje informativne do stranky.
 * @param stanice_id channel.chn_id v databazi
 * @param day_offset relativni cislo dne ode dneska, tj. zitra = 1
 */
function zaznam_tv($stanice_id, $day_offset)
{
    global $DB;
    //NOTE: neni dobre spoustet daytv na prelomu dne, tj. v 11:59
    $time = time() + $day_offset * 24*3600;
    $program = uget($stanice_id, $day_offset);

    echo $stanice_id."<hr/>\n";
    $last_pdate = 0;
    foreach ($program as $row) {
        //NOTE: pdate musi byt (den(now()+offset) + row['cas'])
        $pdate = strtotime($row['cas'], $time);
        if ($pdate < $last_pdate && $row['cas'] == "5:00") {
            // osetreni bugu na tvtip.tiscali.cz,
            // 5:00 maji zapsano ve starsim dni kolem 9. hodiny
            $pdate = strtotime($row['cas'], $time + 24*3600);
        }
        else {
            if ($pdate < $last_pdate) {
                $time += 24*3600;
                $pdate = strtotime($row['cas'], $time);
            }
            $last_pdate = $pdate;
        }
        $nazev = ready_television($row['nazev']);
        $popis = ready_television($row['popis']);
        echo date("Y-m-d H:i:s", $pdate)." $nazev ... <small>$popis</small><br/>\n";
        //NOTE: replace misto insert, protoze tvtip.tiscali.cz neni jistota
        $query = 'replace into television (chn_id, tel_date_start, tel_name, tel_desc) '
            ."values ('$stanice_id', ".$DB->DBTimeStamp($pdate).", '".sql_addslashes($nazev)."', '".sql_addslashes($popis)."')";
        db_sql($query);
    }
}

//-----------------------------------------------------------------
echo "<h2>Záznam televizního programu do databáze</h2><br><br>";

// jisty zacatek a konec dne v teleznim poradu
$day_time_start = "08:00";
$day_time_end = "23:00";

// zaznam televizni poradu na 0 dni zpatky a $tv_days+1 dni dopredu
for ($day_offset = 0; $day_offset < $tv_days + 1; $day_offset++) {
    $time = time() + $day_offset * 24*3600;
    echo date("Y-m-d", $time)."<br/>\n";

    $offset_day_time_start = strtotime($day_time_start, $time);
    $offset_day_time_end = strtotime($day_time_end, $time);
    // vyber vsechny stanice, ktere na tento den nemaji zaznam
    $query = "select channel.chn_id as stanice_id from channel left join television "
        ."on channel.chn_id = television.chn_id "
        ."and UNIX_TIMESTAMP(tel_date_start) between $offset_day_time_start and $offset_day_time_end "
        ."where television.chn_id is NULL";
    $dotaz_stanice = db_sql($query);
    while ($stanice = $dotaz_stanice->FetchRow()) {
        zaznam_tv($stanice['stanice_id'], $day_offset);
    }
    $dotaz_stanice->close();
}

require("footer.php");
?>
