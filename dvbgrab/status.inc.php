<?php
/** nastavi status vsech grabu:
*   0: undefined  - nedefinovany - nemel by se nikdy vyskytnout
*   1: scheduled  - bude se grabovat
*   2: collision  - v kolizi - nebude se grabovat
*   3: done       - hotove
*   4: missed     - uz je to pryc, ale negrablo se
*	5: processing - prave se grabuje
*/
function status_update() {

	// TODO nastavovani stavu processing a done by mel delat pouze grabovaci skript

	// nastavi se jiz hotove
	/*
	db_sql("UPDATE grab SET grb_status='done' where (grb_date_end<FROM_UNIXTIME(".Time()."))".
		"and ((grb_status='scheduled') or (grb_status='processing'));");
	*/

        global $DB;
        $grab_stop_limit = $DB->DBTimeStamp(time()+(10+$grab_date_stop_shift)*60);

        // graby ktere zacaly a nedokoncily se oznac jako 'error'
        $SQL = "update grab set grb_status='error'
                                where grb_date_end < $grab_stop_limit and
                                      grb_status='processing'";
        db_sql($SQL);

        // graby ktere se ani nezacaly se oznac jako 'missed'
        $SQL = "update grab set grb_status='missed'
                                where grb_date_end < $grab_stop_limit and
					grb_status='scheduled'";
	db_sql($SQL);

}

// zjisti kolik ma uzivatel $usr_id tento tyden hotovych a naplanovanych grabu
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

// vim: noexpandtab tabstop=4
?>
