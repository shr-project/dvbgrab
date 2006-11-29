INSERT INTO param(par_key, par_val) VALUES ('last_account_update', '0000-00-00 00:00:00');

INSERT INTO tvgrabber(tvg_id,tvg_name,tvg_cron_time,tvg_cron_cmd) VALUES(1,'PHP poller CT1,CT2,Nova,Prima','0 23 * * 6','cd /var/www/dvbgrab/tvgrabbers; ./zaznam.php 10');
INSERT INTO tvgrabber(tvg_id,tvg_name,tvg_cron_time,tvg_cron_cmd) VALUES(2,'tv_grab_cz','0 23 * * 6','cd /var/www/dvbgrab/tvgrabbers/; tv_grab_cz/tv_grab_cz --days 10 | ./xmltv_to_db.php');

INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (1,'ČT1','ct1.365dni.cz','ct1p.gif', 1, '239.194.12.1', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (2,'ČT2','ct2.365dni.cz','ct2p.gif', 2, '239.194.12.2', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (3,'NOVA','nova.365dni.cz','novap.gif', 3, '239.194.12.5', 1234, 1);
INSERT INTO channel(chn_id,chn_name,chn_xmltv_name,chn_logo,chn_order,chn_ip,chn_port,tvg_id) VALUES (4,'PRIMA','prima.365dni.cz','primap.gif', 4, '239.194.13.1',1234, 1);

INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(1,'MPEG 4','avi','mpeg4.sh',2);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(2,'MPEG 2','mpg','mpeg2.sh',2);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(3,'MPEG 4 scale 0,250','medium.avi','mpeg4-medium.sh',4);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(4,'MPEG 4 scale 0,125','small.avi','mpeg4-small.sh',4);
INSERT INTO encoder(enc_id,enc_codec,enc_suffix,enc_script,enc_pid) VALUES(5,'MPEG 4 full','full.avi','mpeg4-full.sh',4);

INSERT INTO news(news_date,news_text) VALUES('2005-09-04 22:00','Opraveno/Přidáno vyhledávání v plánovaných grabech (Velmi užitecné pro grabovače seriálů ;-))<br />Repair/Add search possibility in tv program (Mostly used for series requests ;-))');
INSERT INTO news(news_date,news_text) VALUES('2005-09-04 23:00','Omezeno zobrazovaní hotových grabů na posledních 100 zaznamů, to samé pro zobrazení mých grabů.<br />Number of showed records limited to 100.');
INSERT INTO news(news_date,news_text) VALUES('2005-09-04 01:00','Přidána možnost nechat si poslat nové vygenerované heslo.<br />New option for sending new random password.');
INSERT INTO news(news_date,news_text) VALUES('2005-09-04 02:30','Přidána volba \"Nastavení\", pro úpravy uživatelských účtů.<br />New option \"Account\", for user account settings.');
INSERT INTO news(news_date,news_text) VALUES('2005-09-27 21:10','Registrován jubilejní 100. uživatel. Bohužel vůbec nic nevyhrává protože má plnou mailovou schránku.:-P<br />Anniversery 100. user registred. No price because she has full mailbox.:-P"');
INSERT INTO news(news_date,news_text) VALUES('2005-05-05 22:00','Přidána možnost zadávat graby rovnou z vyhledávací stránky.<br />New option to request record directly from search page.');
INSERT INTO news(news_date,news_text) VALUES('2005-11-26 22:00','Opravy několika chyb, přidání nových funkcí, změny textů a anketa.<br />Bug hunting day!, few new function, new texts and inquiry');
INSERT INTO news(news_date,news_text) VALUES('2006-11-22 02:00','dvbgrab-2.0 téměř připraven k instalaci<br />dvbgrab-2.0 is almost ready for installation');
