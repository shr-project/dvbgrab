INSERT INTO tvgrabber(tvg_id,tvg_name,tvg_cron_time,tvg_cron_cmd) VALUES(1,'PHP poller CT1,CT2,Nova,Prima','0 23 * * 6','cd /var/www/dvbgrab/tvgrabbers; ./zaznam.php 10');
INSERT INTO tvgrabber(tvg_id,tvg_name,tvg_cron_time,tvg_cron_cmd) VALUES(2,'tv_grab_cz','0 23 * * 6','cd /var/www/dvbgrab/tvgrabbers/; tv_grab_cz/tv_grab_cz --days 10 | ./xmltv_to_db.php');

INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (1,'ČT1','ct1.365dni.cz','ct1p.gif', 1, '239.194.12.1', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (2,'ČT2','ct2.365dni.cz','ct2p.gif', 2, '239.194.12.2', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (3,'NOVA','nova.365dni.cz','novap.gif', 3, '239.194.12.5', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (4,'PRIMA','prima.365dni.cz','primap.gif', 4, '239.194.13.1',1234, 1);

INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(1,'MPEG 2','mpg','mpeg2.sh',2);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(2,'MPEG 4','avi','mpeg4.sh',4);
