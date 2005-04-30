<?php

//----------------------------------------------------------------------------
function sql_addslashes($a_string = '') {
    $a_string = str_replace('\\', '\\\\', $a_string);
    $a_string = str_replace('\'', '\\\'', $a_string);
    return $a_string;
}
//----------------------------------------------------------------------------
/**
 * Pripravi hodnotu na vlozeni do mysql
 * Hodnota pochazi z POST/GET/COOKIES
 */
function readymysql($val) {
    if (get_magic_quotes_gpc()) {
        return "'".str_replace('\\"', '"', $val)."'";
    }
    else {
        return "'".sql_addslashes($val)."'";
    }
}
//----------------------------------------------------------------------------
/**
 * Pripravi hodnotu na vlozeni do mysql
 * Hodnota pochazi z nacteneho souboru/socketu.
 */
function readymysql_fp($val) {
    if (get_magic_quotes_runtime()) {
        return "'".str_replace('\\"', '"', $val)."'";
    }
    else {
        return "'".sql_addslashes($val)."'";
    }
}
//-----------------------------------------------------------------
/**
 * Prevod zmrsene cestiny do latin 2
 */
function cp1250_to_lat2($text) {
    //FIXME: lepsi by byl iconv, ale ten nezvlada paznaky
    return strtr($text, "šŠžŽ–„“", "¹©»«¾®-\"\"");
}

?>

