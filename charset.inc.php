<?
// vyhleda retezce z pole $query_array v retezci $str a nahradi je retezcem $before$string$after
// pokud $use_diacritics neni true, tak se stringy bez diakritiky vyhledavaji bez diakritiky a stringy s diakritikou se hledaji v textu diakritikou
function str_match_array_ascii($str, $query_array, $before, $after, $use_diacritics) {
  $start_array = array();
  $stop_array = array();
  
  $text = mb_strtolower($str, "utf-8");
  $textS = strip_diacritics($text);

  for ( $i=0; $i < mb_strlen($str, "utf-8"); $i++) {
    $start_array[$i] = 0;
    $stop_array[$i] = 0;
  }

  // naplni pole zacatku a koncu casti textu kde zacina/konci nejaky element $query_array
  foreach ($query_array as $query) {
    $offset = 0;
    if ($use_diacritics || is_diacritics_used_utf($query)) {
      $use = $text;
    } else {
      $use = $textS;
    }
    while (($pos = mb_strpos($use, strtolower($query), $offset, "utf-8")) !== false) {
      $start_array[$pos]++;
      $stop_array[$pos-1+mb_strlen($query, "utf-8")]++;
      $offset = $pos+1;
    }
  }
  // pocet otevrenych tagu
  $open = 0;
  for ( $i=0; $i < mb_strlen($str, "utf-8"); $i++) {
    // aktualni znak
    $letter = mb_substr($str, $i, 1, "utf-8");
    // nejaky element tu zacina?
    if ($start_array[$i] > 0) {
      if ($open == 0) {
        $output .= $before;
      }
      $open += $start_array[$i];
    }
    // pridame znak
    $output .= $letter;
    // nejaky element tu konci?
    if ($stop_array[$i] > 0) {
      $open -= $stop_array[$i];
      if ($open == 0) {
        $output .= $after;
      }
    }
  }
  return $output;
}

function strip_diacritics($str) {
      return strtr(iconv("UTF-8","ISO-8859-2",$str),"\xA0\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4A\x4B\x4C\x4D\x4E\x4F\x50\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5A\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6A\x6B\x6C\x6D\x6E\x6F\x70\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7A\xA1\xA3\xA5\xA6\xA9\xAA\xAB\xAC\xAE\xAF\xB1\xB3\xB5\xB6\xB9\xBA\xBB\xBC\xBE\xBF\xC0\xC1\xC2\xC3\xC4\xC5\xC6\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD6\xD8\xD9\xDA\xDB\xDC\xDD\xDE\xDF\xE0\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF8\xF9\xFA\xFB\xFC\xFD\xFE","_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzALLSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOORUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
};

function strip_diacritics_iso($str) {
      return strtr($str,"\xA0\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4A\x4B\x4C\x4D\x4E\x4F\x50\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5A\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6A\x6B\x6C\x6D\x6E\x6F\x70\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7A\xA1\xA3\xA5\xA6\xA9\xAA\xAB\xAC\xAE\xAF\xB1\xB3\xB5\xB6\xB9\xBA\xBB\xBC\xBE\xBF\xC0\xC1\xC2\xC3\xC4\xC5\xC6\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD6\xD8\xD9\xDA\xDB\xDC\xDD\xDE\xDF\xE0\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF8\xF9\xFA\xFB\xFC\xFD\xFE","_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzALLSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOORUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
};

function sql_strip_diacritics($column) {
  return "lower(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace($column, 'á', 'a'), 'č', 'c'), 'ď', 'd'), 'é', 'e'), 'ě', 'e'), 'í', 'i'), 'ň', 'n'), 'ó', 'o'), 'ř', 'r'), 'š', 's'), 'ť', 't'), 'ú', 'u'), 'ů', 'u'), 'ý', 'y'), 'ž', 'z'), 'Á', 'A'), 'Č', 'C'), 'Ď', 'D'), 'É', 'E'), 'Ě', 'E'), 'Í', 'I'), 'Ň', 'N'), 'Ó', 'O'), 'Ř', 'R'), 'Š', 'S'), 'Ť', 'T'), 'Ú', 'U'), 'Ů', 'U'), 'Ý', 'Y'), 'Ž', 'Z'))";
}

function is_diacritics_used_utf($text) {
  return ereg("[áčďéěíňóřšťúůýžÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ]", $text);
}
function is_diacritics_used($text) {
  return ereg("[áčďéěíňóřšťúůýžÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ]", $text);
}


?>
