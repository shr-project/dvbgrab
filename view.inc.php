<?php

require_once("dblib.php");
require_once("language.inc.php");
require_once("const.php");

function str_ascii($str) {
	return strtr($str, 'áèïéìíòóø¹»úùý¾ÁÈÏÉÌÍÒÓØ©«ÚÙÝ®', 'acdeeinorstuuyzACDEEINORSTUUYZ');
}

/**
* Returns how much grabs are scheduled for this week by given user.
*/
function get_user_grab($usr_id, $week) {
	$SQL = "select count(*) from grab g, request r where
					g.grb_id=r.grb_id and
					r.usr_id=$usr_id and
					(g.grb_status='scheduled' or g.grb_status='done' or g.grb_status='processing' or g.grb_status='deleted') and
					date_format(g.grb_date_start, '%v')=$week";
	$rs = db_sql($SQL);
	$row = $rs->FetchRow();
	return $row[0];
}

/**
* Shows HTML for the TV program.
* @param tel_id television id
* @param tel_date_start unix timestamp of the television start
* @param tel_name short television name
* @param tel_desc television description
* @param grb_id grab id or empty when this television is not grabbed yet
* @param grb_status grab status or empty
* @param my_grab true when it is grab also for my
* @param query addition to GET links (e.g., "tv_date=xxxx")
*/
function show_television($tel_id, $tel_date_start, $tel_name, $tel_desc, $grb_id, $grb_status, $my_grab, $query="") {
	global $msgGrabLinkStorno;
	global $msgGrabLinkGrab;

	echo "<tr";
	show_grab_class($grb_id, $grb_status, $my_grab);
	echo ">";
	show_television_date($tel_id, $tel_date_start);

	echo '<td valign="top"><b>';
	show_grab_add_link($tel_id, $tel_date_start, htmlspecialchars($tel_name), $my_grab, $query);
	echo '</b><br>';

	echo '<font size="1"><i>'.htmlspecialchars($tel_desc).'</i></font>';
	echo '<br>';
	show_grab_del_link($grb_id, $grb_status, $my_grab, $query);

	echo "</td>\n</tr>\n";
}

/**
* Shows CSS class for the grab status.
*/
function show_grab_class($grb_id, $grb_status, $my_grab) {
	if ($grb_id) {
		if ($grb_status != 'scheduled') {
		  echo " class=\"status-$grb_status\"";
		} else {
			if ($my_grab) {
				echo " class=\"status-myscheduled\"";
			} else {
				echo " class=\"status-scheduled\"";
			}
		}
	}
}

function show_television_date($tel_id, $tel_date_start) {
	echo "<td class=\"datum\" valign=\"top\" align=\"center\">";
	echo "<a name=\"$tel_id\"></a>".date("G:i", $tel_date_start)."</td>";
}

/**
* Shows link to grab the television
* when it is not already my grab.
* @param text text to display for the link body
*/
function show_grab_add_link($tel_id, $tel_date_start, $text, $my_grab, $query="") {
	global $msgGrabConfirmStart;
	global $msgGrabConfirmGrab;
	global $msgGrabLinkGrab;
	global $grab_date_stop_shift;

	$grab_time_limit = time() - $grab_date_stop_shift*60;
	if (!$my_grab && $tel_date_start >= $grab_time_limit) {
		echo "<a onclick=\"return confirm('$msgGrabConfirmStart ".strip_tags($text)." $msgGrabConfirmGrab')\" ".
			"href=\"$PHP_SELF?action=grab_add&amp;tel_id=$tel_id&amp;$query\"".
			" title=\"$msgGrabLinkGrab\" class=\"program\">".
			$text."</a>";
	}
	else {
		echo $text;
	}
}


/**
* Shows link to delete my grab.
* @return true whent the link was added
*/
function show_grab_del_link($grb_id, $grb_status, $my_grab, $query) {
	global $msgGrabLinkStorno;

	$result = false;
	if ($grb_status == 'scheduled' && $grb_id && $my_grab) {
		echo "<a class=\"program\" href=\"$PHP_SELF?action=grab_del".
			"&amp;grb_id=$grb_id&amp;$query\">$msgGrabLinkStorno</a>";
		$result = true;
	}
	return $result;
}

/**
 * Returns grab timestamp where midnight is considered.
 */
function get_grab_timestamp($grb_date_start) {
	global $midnight;

	$grb_timeStamp = $grb_date_start;
	if (date("G", $grb_timeStamp)<$midnight) {
		$grb_timeStamp-=24*3600;
	}
	return $grb_timeStamp;
}

/**
 * Shows new day heading when grab is from new day.
 * Returns used day.
 */
function show_grab_day($grb_date_start, $old_grb_day) {
	global $dow;

	$grb_timeStamp = get_grab_timestamp($grb_date_start);
	$grb_day = $dow[date("l", $grb_timeStamp)].date(", d. m. Y", $grb_timeStamp);
	if ($grb_day != $old_grb_day) {
		if ($old_grb_day != "") {
			echo "<tr><td colspan=\"7\">&nbsp;</td></tr>\n";
		}
		echo "<tr><th colspan=\"7\">&nbsp;&nbsp;&nbsp;$grb_day</th></tr>\n";
	}
	return $grb_day;
}

/**
 * Shows grab on list of planned grabs.
 * Planned requests contain:
 * - usr_id
 * - usr_name
 * - usr_email
 * - req_output
 */
function show_planned_grab($grb_date_start, $grb_date_end, $grb_id, $grb_status,
		$chn_name, $tel_id, $tel_name, $planned_requests) {
	global $usr_id;
	global $channel_logo;
	global $grab_user_url;
	global $grab_storage;

	$grb_timeStamp = get_grab_timestamp($grb_date_start);
	$grb_time = date("H:i", $grb_date_start)."-".date("H:i", $grb_date_end);

	$mygrab = false;
	$myoutput = "";
	$myname = "";
	foreach ($planned_requests as $request) {
		if ($request["usr_id"] == $usr_id) {
			$mygrab = true;
			$myname = $request["usr_name"];
			$myoutput = $request["req_output"];
			break;
		}
	}

	echo "	<tr>\n";
	echo "		<td width=\"30\">&nbsp;</td>\n";
	echo "		<td width=\"12\" align=\"center\"";
	show_grab_class($grb_id, $grb_status, $mygrab);
	echo ">&nbsp;</td>\n";
	echo "		<td width=\"60\">&nbsp;&nbsp;<img alt=\"".$chn_name."\"".
		" src=\"images/".$channel_logo[$chn_name]."\"></td>\n";

	echo "		<td width=\"110\"><b>";
	echo $grb_time;
	echo "</b></td>\n";

	echo "		<td><a href=\"tvprog.php?tv_date=".
		date("Ymd", $grb_timeStamp).
		"#".$tel_id."\">".htmlspecialchars($tel_name)."</a>".
		"</td>\n";

	echo "		<td width=\"20\">&nbsp;</td>\n";
	echo "		<td>";
	if ($myoutput != "" && file_exists("$grab_storage/$myoutput")) {
		$user_url = str_replace('<USER>', $myname, $grab_user_url);
		echo "[<a href=\"$user_url/$myoutput\">DOWNLOAD</a>]";
	}
	else {
		$first = true;
		foreach ($planned_requests as $request) {
			if ($first) {
				$first = false;
			}
			else {
				echo ", ";
			}
			echo "<a href=\"mailto:".str_replace("@", "@NOSPAM.", $request["usr_email"])."\">".$request["usr_name"]."</a>";
		}
	}
	echo "		</td>\n";
	echo "	</tr>\n";
}

// vim: noexpandtab tabstop=4
?>
