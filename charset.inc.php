<?
// vyhleda retezce z pole $query_array v retezci $str a nahradi je retezcem $match
// pokud $match obsahovaje $1, tak tyto znaky v retezci $match se nahradi nalezenym retezcem z $query_array
// vyhledavane retezce se mohou prekryvaji
// pokud $use_diacritics neni true, tak se vyhledava bez diakritiky
function str_match_array_ascii($str, $query_array, $match, $use_diacritics) {
  $res_array = array();
  $res2_array = array();

  // napln pole $res_array (index -> delka), kde se nachazi vzorky v textu
  reset($query_array);
  while ($query = current($query_array)) {
    $offset = 0;
    if ($use_diacritics) {
      while (($pos = strpos($str, $query, $offset)) !== false) {
        if (!isset($res_array[$pos]) || $res_array[$pos] < strlen($query)) $res_array[$pos] = strlen($query);
        $offset = $pos+1;
      }
    } else {
      while (($pos = strpos(strip_diacritics($str), strip_diacritics($query), $offset)) !== false) {
        if (!isset($res_array[$pos]) || $res_array[$pos] < strlen($query)) $res_array[$pos] = strlen($query);
        $offset = $pos+1;
      }
    }
    next($query_array);
  }

  // preved pole $res_array na $res2_array tak, ze (index -> delka) se neprekryvaji
  ksort($res_array);
  reset($res_array);
  if (sizeof($res_array) > 1) {
    list($index, $length) = each($res_array);
    list($next_index, $next_length) = each($res_array);
    do {
      if ($next_index <= $index+$length) {
        $length = $next_length+$next_index-$index;
      } else {
        $res2_array[$index] = $length;
        $index = $next_index;
        $length = $next_length;
      }
    } while (list($next_index, $next_length) = each($res_array));
    $res2_array[$index] = $length;
  } else {
    $res2_array = $res_array;
  }

  // proved nahradu vzorku v $res2_array retezcem $match
  $offset = 0;
  $str2 = "";
  reset($res2_array);
  if (sizeof($res2_array) > 0) {
    while (list($index, $length) = each($res2_array)) {
      $replace_str = str_replace("$1", substr($str, $index, $length), $match);
      $str2 .= substr_replace(substr($str, $offset, $index-$offset+$length), $replace_str, $index-$offset, $length);
      $offset = $index+$length;
    }
  }
  $str2 .= substr($str, $offset);

  return $str2;
}

// zruseni ceske diakritiky
function strip_diacritics($str) {
      return strtr(iconv("UTF-8","ISO-8859-2",$str),"\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4A\x4B\x4C\x4D\x4E\x4F\x50\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5A\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6A\x6B\x6C\x6D\x6E\x6F\x70\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7A\xA1\xA3\xA5\xA6\xA9\xAA\xAB\xAC\xAE\xAF\xB1\xB3\xB5\xB6\xB9\xBA\xBB\xBC\xBE\xBF\xC0\xC1\xC2\xC3\xC4\xC5\xC6\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD6\xD8\xD9\xDA\xDB\xDC\xDD\xDE\xDF\xE0\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF8\xF9\xFA\xFB\xFC\xFD\xFE","ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzALLSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOORUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
};

function sql_strip_diacritics($column) {
  return "lower(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace($column, 'á', 'a'), 'č', 'c'), 'ď', 'd'), 'é', 'e'), 'ě', 'e'), 'í', 'i'), 'ň', 'n'), 'ó', 'o'), 'ř', 'r'), 'š', 's'), 'ť', 't'), 'ú', 'u'), 'ů', 'u'), 'ý', 'y'), 'ž', 'z'), 'Á', 'A'), 'Č', 'C'), 'Ď', 'D'), 'É', 'E'), 'Ě', 'E'), 'Í', 'I'), 'Ň', 'N'), 'Ó', 'O'), 'Ř', 'R'), 'Š', 'S'), 'Ť', 'T'), 'Ú', 'U'), 'Ů', 'U'), 'Ý', 'Y'), 'Ž', 'Z'))";
}

function is_diacritics_used($test) {
  return ereg("[áčďéěíňóřšťúůýžÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ]", $text);
}

?>
