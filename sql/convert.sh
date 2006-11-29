cat $1 | grep "INSERT INTO \`grab\`" | sed "s/INSERT INTO \`grab\`\(.*\),0,'[^']*');/INSERT INTO \"grab\"(grb_id,tel_id,grb_date_start,grb_date_end) \1);/g"  >> $2.grab.sql
cat $1 | grep "INSERT INTO \`request\`" | sed "s/INSERT INTO \`request\`\(.*\),.\(,'[^']*'\));/INSERT INTO \"request\"(req_id,grb_id,usr_id,req_output,req_status) \1\2,'undefined');/g" | sed "s/\`/\"/g" >> $2.req.sql
cat $2.req.sql | sed "s/\"request\"/\"usrreq\"(req_id,usr_id,urq_output)/g; s/\(VALUES ([0123456789]*\),[0123456789]*\(,[0123456789]*,'\).*\/\([^\/']*'\),.*/\1\2\3);/g;" >> $2.userreq.sql
#cat $2.req.sql | sed "s/\(VALUES ([0123456789]*,[0123456789]*\),[0123456789]*/; 
cat $1 | grep "INSERT INTO \`user\`" | sed "s/\`user\`/\"userinfo\"/g" | iconv -f iso8859-2 -t utf8 >> $2.userinfo.sql
cat $1 | grep "INSERT INTO \`television\`" | sed "s/\`television\`/\"television\"(tel_id,chn_id,tel_date_start,tel_name,tel_desc) /g" | iconv -f iso8859-2 -t utf8 >> $2.television.sql
