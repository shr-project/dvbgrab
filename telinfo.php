<?php

require_once("dolib.inc.php");

function toDate($dbVal) {
  global $DB;
  if ($dbVal == 0) {
    return "";
  }
  return date('Y-m-d H:i',$DB->UnixTimeStamp($dbVal));
}

$tel_id = $_GET["telId"];

if (empty($tel_id)) {
  return "";
}

$SQL = "select enc_codec, 
          req_output, 
          req_output_md5, 
          req_output_size, 
          req_status 
        from request r
          left join encoder e using (enc_id)
          left join grab g using (grb_id)
          left join television t using (tel_id)
        where t.tel_id=$tel_id";
$rs = do_sql($SQL);
$req_outputs = array();
while ($row = $rs->FetchRow()) {
  $filename = $row[1];
  if (!empty($filename)) {
    $pos = strrpos($filename, "/");
    if ($pos !== false) {
      $filename = substr($filename,$pos+1);
    }
  }
  $req_output = array("filename" => $filename,
                      "size" => $row[3]/(1024),
                      "md5" => $row[2],
                      "enc" => $row[0],
                      "status" => $row[4]);
  array_push($req_outputs,$req_output);
}

$SQL = "select t.tel_name,
          t.tel_series,
          t.tel_episode,
          t.tel_part,
          c.chn_name,
          g.grb_name,
          t.tel_date_start,
          t.tel_date_end,
          g.grb_date_start,
          g.grb_date_end
        from television t 
             left join grab g using (tel_id ) 
             left join channel c using (chn_id )
        where t.tel_id=$tel_id";
$rs = do_sql($SQL);
$row = $rs->FetchRow();

$val = array("tel_id" => $tel_id,
             "tel_name" => $row[0],
             "tel_series" => $row[1],
             "tel_episode" => $row[2],
             "tel_part" => $row[3],
             "chn_name" => $row[4],
             "grb_name" => $row[5],
             "tel_date_start" => toDate($row[6]),
             "tel_date_end" => toDate($row[7]),
             "grb_date_start" => toDate($row[8]),
             "grb_date_end" => toDate($row[9]),
             "req_outputs" => $req_outputs,
            );
$output = json_encode($val);
echo $output;

?>
