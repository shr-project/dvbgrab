<?php
require("dblib.php");
require("authenticate.php");
require("const.php");
require("config.php");
require_once("status.inc.php");
require("header.php");
require("menu.php");


$MAX_SEARCH_RESULTS = 50;

global $DB;  // pripojeni do databaze



function str_ascii($str) {
	return strtr($str, 'áèïéìíòóø¹»úùý¾ÁÈÏÉÌÍÒÓØ©«ÚÙÝ®', 'acdeeinorstuuyzACDEEINORSTUUYZ');
}

function strtolower_czech($str) {
	return strtolower(strtr($str, 'ÁÈÏÉÌÍÒÓØ©«ÚÙÝ®', 'áèïéìíòóø¹»úùý¾'));
}

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
			while (($pos = strpos(strtolower_czech($str), strtolower_czech($query), $offset)) !== false) {
				if (!isset($res_array[$pos]) || $res_array[$pos] < strlen($query)) $res_array[$pos] = strlen($query);
				$offset = $pos+1;
			}
		} else {
			while (($pos = strpos(strtolower_czech(str_ascii($str)), strtolower_czech(str_ascii($query)), $offset)) !== false) {
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
	} else $res2_array = $res_array;

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

$query = $_GET["query"];
$query_array = explode(" ", $query);

?>
<td valign="top">
<form method="get" action="<?=$PHP_SELF?>">
Hledej v tv programu: 
<input type="text" name="query" value="<?=$query?>">
<input type="submit" value="Hledej">
</form>
<?
// TODO nevyhledavat v html tazich

if (sizeof($query_array) == 0) {
	echo "Chyba: nebyl zadán vyhledávaný øetìzec.";
	include("footer.php");
	exit;
}
if (sizeof($query_array) > 10) {
	echo "Chyba: bylo zadáno pøíli¹ mnoho slov k vyhledání.";
	include("footer.php");
	exit;
}

// kdyz $query obsahuje diakritiku, tak budeme hledat s diakritikou
$use_diacritics = 0;
for ($i=0; $i<sizeof($query_array); $i++) {
	if (ereg("[áèïéìíòóø¹»úùý¾ÁÈÏÉÌÍÒÓØ©«ÚÙÝ®]", $query_array[$i])) $use_diacritics = 1;
}

// vytvorime dotaz na vyhledani retezcu
$query_sql = "";
reset($query_array);
for ($i=0; $i<sizeof($query_array); $i++) {
	if ($use_diacritics) {
		$query_sql .= "(t.tel_name like '%".$query_array[$i]."%' or t.tel_desc like '%".$query_array[$i]."%') and ";
	} else {
		$query_sql .= "(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(lower(t.tel_name), 'á', 'a'), 'è', 'c'), 'ï', 'd'), 'é', 'e'), 'ì', 'e'), 'í', 'i'), 'ò', 'n'), 'ó', 'o'), 'ø', 'r'), '¹', 's'), '»', 't'), 'ú', 'u'), 'ù', 'u'), 'ý', 'y'), '¾', 'z'), 'Á', 'A'), 'È', 'C'), 'Ï', 'D'), 'É', 'E'), 'Ì', 'E'), 'Í', 'I'), 'Ò', 'N'), 'Ó', 'O'), 'Ø', 'R'), '©', 'S'), '«', 'T'), 'Ú', 'U'), 'Ù', 'U'), 'Ý', 'Y'), '®', 'Z') like '%".str_ascii($query_array[$i])."%' or
				replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(lower(t.tel_desc), 'á', 'a'), 'è', 'c'), 'ï', 'd'), 'é', 'e'), 'ì', 'e'), 'í', 'i'), 'ò', 'n'), 'ó', 'o'), 'ø', 'r'), '¹', 's'), '»', 't'), 'ú', 'u'), 'ù', 'u'), 'ý', 'y'), '¾', 'z'), 'Á', 'A'), 'È', 'C'), 'Ï', 'D'), 'É', 'E'), 'Ì', 'E'), 'Í', 'I'), 'Ò', 'N'), 'Ó', 'O'), 'Ø', 'R'), '©', 'S'), '«', 'T'), 'Ú', 'U'), 'Ù', 'U'), 'Ý', 'Y'), '®', 'Z') like '%".str_ascii($query_array[$i])."%') and ";
	}
}

$SQL = "select c.chn_name, t.tel_id, t.tel_name, t.tel_desc, unix_timestamp(t.tel_date_start) as tel_date_start_unx,
				g.grb_id, g.grb_status, not isnull(r.usr_id) as my_grab	
		from channel c inner join television t on (c.chn_id=t.chn_id)
			left join grab g on (t.tel_id=g.tel_id)
			left join request r on (g.grb_id=r.grb_id and r.usr_id=$usr_id)
		where
			$query_sql
			tel_date_start>=now()
		order by tel_date_start_unx";
//echo $SQL;
$rs = db_sql($SQL);
$res_count = $rs->RecordCount();
echo "Nalezených záznamù: $res_count<br>";
if ($res_count > $MAX_SEARCH_RESULTS) {
	echo "Nalezeno pøíli¹ mnoho záznamù, zobrazuji prvních $MAX_SEARCH_RESULTS";
}
?>
<br><br><br>

<?
if ($res_count > 0) {
?>
<table>
<?
	$cur_res = 1;
  while ($row = $rs->FetchRow()) {
?>
        <tr<?
                // pokud se jedna o grab, tak ho barevne oznac
                if ($row["grb_id"]) {
                    if ($row["grb_status"] <> 'scheduled') {
                      echo " class=\"status-".$row["grb_status"]."\"";
                    } else {
              // oznacime graby, ktere maji oznaceny ostatni a ja ne
            $SQL = "select grb_id, grb_enc from request where grb_id=".$row["grb_id"]." and usr_id=$usr_id ";
              $rs3 = db_sql($SQL);
            if ($row3 = $rs3->FetchRow()) {
                        // muj request
              if ($row3[1])
                            echo " class=\"status-myscheduled\"";
                            else
                            echo " class=\"status-mynocomprim\"";
                        } else {
                        echo " class=\"status-scheduled\"";
                        }
                    }
                }?>>
			<td class="datum" valign="top" align="center">
				<?=date("G:i",$row["tel_date_start_unx"])?>
				<?php

				// pokud se jedna o muj grab
				if ($row["my_grab"]) echo "<br><img alt=\"moje\" src=\"images/dot.gif\">";
				?>
			</td>
			<td>
				<table width="100%">
				<tr>
					<td align="left">
						<img align="middle" alt="<?=$row["chn_name"]?>" title="<?=$row["chn_name"]?>" src="images/<?=$channel_logo[$row["chn_name"]]?>">
						<b><?php
						echo "<a href=\"tvprog.php?tv_date=".date("Ymd", $row["tel_date_start_unx"]-((date("G", $row["tel_date_start_unx"])<$midnight)?1:0)*24*3600).
							"#".$row["tel_id"]."\">".str_match_array_ascii(htmlspecialchars($row["tel_name"]), $query_array, "<b><font color=\"red\">$1</font></b>", $use_diacritics)."</a>";
						?>
						</b>
					</td>
					<td align="right">
						<?
						$tel_date_start_unx_shifted = $row["tel_date_start_unx"]-((date("G", $row["tel_date_start_unx"])<$midnight)?1:0)*24*3600;
						echo $dow[date("l", $tel_date_start_unx_shifted)].date(", j. n. Y", $tel_date_start_unx_shifted);
						?>
					</td>
				</tr>
				</table>
				<font size="1"><i><?=str_match_array_ascii($row["tel_desc"], $query_array, "<b><font color=\"red\">$1</font></b>", $use_diacritics)?></i></font>
				<br><br>
			</td>
		</tr>
	<? 
	$cur_res++;	
	if ($cur_res > $MAX_SEARCH_RESULTS) break;
	} ?>
</td></tr></table>
<? } ?>

<?php
require("footer.php");

// vim: noexpandtab tabstop=4
?>
