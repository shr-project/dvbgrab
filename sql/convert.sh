#WARNING best way is to restore only users and let users to migrate their grabs between old and new dvbgrab themself

cat $1 | grep "INSERT INTO \`grab\`" | sed "s/INSERT INTO \`grab\`\(.*\),0,'[^']*');/INSERT INTO \"grab\"(grb_id,tel_id,grb_date_start,grb_date_end) \1);/g"  >> $2.grab.sql
cat $1 | grep "INSERT INTO \`request\`" | sed "s/INSERT INTO \`request\`\(.*\),.\(,'[^']*'\));/INSERT INTO \"request\"(req_id,grb_id,usr_id,req_output,req_status) \1\2,'undefined');/g" | sed "s/\`/\"/g" >> $2.request.sql
cat $1 | grep "INSERT INTO \`user\`" | sed "s/\`user\`/\"userinfo\"/g" | iconv -f iso8859-2 -t utf8 >> $2.userinfo.sql
cat $1 | grep "INSERT INTO \`television\`" | sed "s/\`television\`/\"television\"(tel_id,chn_id,tel_date_start,tel_name,tel_desc) /g" | iconv -f iso8859-2 -t utf8 >> $2.television.sql

#Than in DB
echo << EOF

ALTER TABLE "request" rename to "request2";
CREATE TABLE "request" (
  req_id          integer         DEFAULT nextval('"seq_req_id"'::text) NOT NULL,
  grb_id          integer         DEFAULT 0                               NOT NULL,
  enc_id          integer         DEFAULT 1                               NOT NULL,
  req_output      varchar(255)    DEFAULT ''                              NOT NULL,
  req_output_md5  varchar(80)     DEFAULT ''                              NOT NULL,
  req_output_size integer         DEFAULT 0                               NOT NULL,
  req_status      varchar(20)     DEFAULT ''                              NOT NULL,
  PRIMARY KEY     (req_id),
  UNIQUE          (grb_id,enc_id)
);
INSERT INTO "request"(req_id,grb_id,enc_id,req_output,req_output_md5,req_output_size,req_status)
SELECT MIN(req_id),grb_id,enc_id,MIN(req_output)req_output_md5,req_output_md5,req_output_size,req_status FROM request2 GROUP BY grb_id,enc_id,req_output_md5,req_output_size,req_status;

INSERT INTO "userreq"(req_id,usr_id,urq_output)
SELECT (SELECT min(req_id) from request2 rr where rr.grb_id=r.grb_id and rr.enc_id=r.enc_id),usr_id,req_output FROM request2 r;

DROP TABLE request2;

SELECT pg_catalog.setval('seq_urq_id', (select max(urq_id) from userreq)+1, true);
EOF
