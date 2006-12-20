INSERT INTO param(par_key, par_val) VALUES ('last_account_update', '0000-00-00 00:00:00');

INSERT INTO tvgrabber(tvg_id,tvg_name,tvg_cron_time,tvg_cron_cmd,tvg_enabled) VALUES(1,'PHP tv_grab_novinky_cz (poller ČT1,ČT2,Nova,Prima)','0 23 * * 6','cd /var/www/dvbgrab/tvgrabbers/tv_grab_novinky_cz; ./tv_grab_novinky_cz.php',0);
INSERT INTO tvgrabber(tvg_id,tvg_name,tvg_cron_time,tvg_cron_cmd,tvg_enabled) VALUES(2,'tv_grab_cz','0 23 * * 6','cd /var/www/dvbgrab/tvgrabbers/; tv_grab_cz/tv_grab_cz --days 10 | ./xmltv_to_db.php',0);

INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (1,'ČT1','ct1.365dni.cz','ct1p.gif', 1, '239.194.12.1', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (2,'ČT2','ct2.365dni.cz','ct2p.gif', 2, '239.194.12.2', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (3,'NOVA','nova.365dni.cz','novap.gif', 3, '239.194.12.5', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (4,'PRIMA','prima.365dni.cz','primap.gif', 4, '239.194.13.1',1234, 1);

INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(1,'MPEG 4 scale 0,500','avi','mpeg4.sh',null);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(2,'MPEG 4 scale 0,250','medium.avi','mpeg4-medium.sh',null);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(3,'MPEG 4 scale 0,125','small.avi','mpeg4-small.sh',null);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(4,'MPEG 4 full','full.avi','mpeg4-full.sh',null);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(5,'MPEG 2','mpg','mpeg2.sh',null);

INSERT INTO news(news_text) VALUES('Nainstalováno ;-))');
