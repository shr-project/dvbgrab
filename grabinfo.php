<?php

require_once("dolib.inc.php");

function toDate($dbVal) {
  global $DB;
  return date('Y-m-d H:i',$DB->UnixTimeStamp($dbVal));
}

$grab_id = $_GET["grabId"];

if (empty($grab_id)) {
  return "";
}

$SQL = "select distinct(enc_codec), req_output, req_output_md5, req_output_size, req_status from request,encoder where grb_id=$grab_id and request.enc_id=encoder.enc_id order by encoder.enc_codec";
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
        from grab g,television t,channel c
        where g.tel_id=t.tel_id
          AND t.chn_id=c.chn_id
          AND g.grb_id=$grab_id";
$rs = do_sql($SQL);
$row = $rs->FetchRow();

$val = array("grb_id" => $grab_id,
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
