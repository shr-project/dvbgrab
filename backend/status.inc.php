<?php
/** nastavi status vsech grabu:
*   0: undefined      - nemel by se nikdy vyskytnout
*   1: scheduled      - bude se grabovat
*   2: done           - hotove, pripravene ke stazeni
*   3: missed         - uz je to pryc, ale negrablo se
*   4: deleted        - smazano 
*   5: error-saving   - chyba pri ukladani
*   6: error-encoding - chyba pri komprimaci
*   7: saving         - prave se uklada
*   8: saved          - ulozeno
*   9: encoding       - prave se komprimuje
*   10:encoded        - zkomprimovano
*/
function status_update() {
  global $DB;
  
  $grab_stop_limit = $DB->DBTimeStamp(time()-(10+_Config_grab_date_stop_shift)*60);
	
  // graby ktere zacaly a nedokoncily se oznac jako 'error'
  $SQL = "update request
          set req_status='error'
          where req_status='processing' and
            grb_id IN ( select g.grb_id 
                          from grab g 
                          where g.grb_date_end < $grab_stop_limit)";
  do_sql($SQL);

  // graby ktere se ani nezacaly oznac jako 'missed'
  $SQL = "update request 
          set req_status='missed'
          where req_status='scheduled' and
            grb_id IN ( select g.grb_id
                          from grab g
                          where g.grb_date_end < $grab_stop_limit)";
  do_sql($SQL);
}

// zjisti kolik ma uzivatel $usr_id tento tyden hotovych a naplanovanych grabu
function get_user_grab($usr_id, $week) {
  global $DB;
//  echo "U$usr_id,W$week";
  $SQL = "select count(*) 
          from grab g, request r 
          where g.grb_id=r.grb_id and
            r.usr_id=$usr_id and
            (r.req_status='scheduled' or r.req_status='done') and "
            .$DB->SQLDate('W',"g.grb_date_start")."=$week";
  $rs = do_sql($SQL);
  $row = $rs->FetchRow();
  return $row[0];
}

?>
