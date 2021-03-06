-- Database: `dvbgrab`
DROP TABLE IF EXISTS `channel` CASCADE;
DROP TABLE IF EXISTS `tvgrabber` CASCADE;
DROP TABLE IF EXISTS `encoder` CASCADE;
DROP TABLE IF EXISTS `grab` CASCADE;
DROP TABLE IF EXISTS `request` CASCADE;
DROP TABLE IF EXISTS `userreq` CASCADE;
DROP TABLE IF EXISTS `television` CASCADE;
DROP TABLE IF EXISTS `userinfo` CASCADE;
DROP TABLE IF EXISTS `param` CASCADE;

CREATE TABLE IF NOT EXISTS `channel` (
  `chn_id`        int(11)        NOT NULL auto_increment,
  `chn_name`      varchar(20)    NOT NULL default '',
  `chn_xmltv_name`varchar(80)    NOT NULL default '',
  `chn_logo`      varchar(20)    NOT NULL default '',
  `chn_order`     tinyint(4)     NOT NULL default '0',
  `chn_ip`        varchar(15)    NOT NULL default '239.194.1.1',
  `chn_port`      int(4)         NOT NULL default '1234',
  `chn_enabled`   int(1)         NOT NULL default 1,
  `tvg_id`        int(11)        NOT NULL,
  PRIMARY KEY (`chn_id`),
  UNIQUE KEY `chn_order` (`chn_order`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `tvgrabber` (
  `tvg_id`        int(11)        NOT NULL auto_increment,
  `tvg_name`      varchar(100)   NOT NULL default '',
  `tvg_enabled`   int(1)         NOT NULL default 1,
  `tvg_cron_time` varchar(50)    NOT NULL default '0 23 * * 6',
  `tvg_cron_cmd`  varchar(150)   NOT NULL default 'xmltv grabber',
  PRIMARY KEY     (`tvg_id`)
) ENGINE=MyISAM;
  
CREATE TABLE IF NOT EXISTS `encoder` (
  `enc_id`        int(11)        NOT NULL auto_increment,
  `enc_codec`     varchar(100)   NOT NULL default '',
  `enc_suffix`    varchar(20)    NOT NULL default '',
  `enc_script`    varchar(255)   NOT NULL default '',
  `enc_pid`       int(11)                 default NULL,
  PRIMARY KEY     (`enc_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `grab` (
  `grb_id`         int(11)       NOT NULL auto_increment,
  `tel_id`         int(11)       NOT NULL default '0',
  `grb_date_start` datetime      NOT NULL default '0000-00-00 00:00:00',
  `grb_date_end`   datetime      NOT NULL default '0000-00-00 00:00:00',
  `grb_name`       varchar(255)  NOT NULL default '',
  PRIMARY KEY      (`grb_id`),
  UNIQUE KEY `idx_tel_id` (`tel_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `request` (
  `req_id`         int(11)       NOT NULL auto_increment,
  `grb_id`         int(11)       NOT NULL default '0',
  `enc_id`         int(11)       NOT NULL default '1',
  `req_output`     varchar(255)           default '',
  `req_output_md5` varchar(80)            default '',
  `req_output_size`int(20)                default '0',
  `req_status`     enum('undefined','scheduled','done','missed','saving','saved','encoding','encoded','deleted','error') NOT NULL default 'scheduled',
  PRIMARY KEY      (`req_id`),
  UNIQUE KEY idx_grb_usr (grb_id,enc_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `userreq` (
  `urq_id`         int(11)       NOT NULL auto_increment,
  `req_id`         int(11)       NOT NULL default '0',
  `usr_id`         int(11)       NOT NULL default '0',
  `urq_output`     varchar(255)           default '',
  PRIMARY KEY      (`urq_id`),
  UNIQUE KEY idx_grb_usr (req_id,usr_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `television` (
  `tel_id`         int(11)       NOT NULL auto_increment,
  `chn_id`         int(11)       NOT NULL default '0',
  `tel_date_start` datetime      NOT NULL default '0000-00-00 00:00:00',
  `tel_date_end`   datetime      NOT NULL default '0000-00-00 00:00:00',
  `tel_name`       varchar(255)  NOT NULL default '',
  `tel_desc`       text          NOT NULL,
  `tel_typ`        varchar(50)            default '',
  `tel_category`   varchar(50)            default '',
  `tel_series`     varchar(15),
  `tel_episode`    varchar(15),
  `tel_part`       varchar(15),
  PRIMARY KEY      (`tel_id`),
  UNIQUE KEY `idx_tel_chn` (`chn_id`,`tel_date_start`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `userinfo` (
  `usr_id`         int(11)       NOT NULL auto_increment,
  `usr_name`       varchar(30)   binary NOT NULL default '',
  `usr_pass`       varchar(52)   NOT NULL default '',
  `usr_email`      varchar(60)   NOT NULL default '',
  `usr_icq`        int(11)                default NULL,
  `usr_jabber`     varchar(40)   NOT NULL default '',
  `usr_lang`       varchar(4)             default '',
  `usr_ip`         varchar(40)   NOT NULL default '0.0.0.0',
  `usr_priority`   tinyint(4)    NOT NULL default '2',
  `enc_id`         int(11)       NOT NULL default '2',
  `usr_last_activity` datetime   NOT NULL default '0000-00-00 00:00:00',
  `usr_last_update`   datetime   NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY      (`usr_id`),
  UNIQUE KEY `idx_usr_name` (`usr_name`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `param` (
  `par_key`        varchar(40)   NOT NULL default '',
  `par_val`        varchar(255)  NOT NULL default ''  
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `news` (
  `news_date`   datetime      NOT NULL default '0000-00-00 00:00:00',
  `news_text`   text      NOT NULL
) ENGINE=MyISAM;

TRUNCATE channel;
TRUNCATE encoder;
TRUNCATE grab;
TRUNCATE request;
TRUNCATE television;
TRUNCATE tvgrabber;
TRUNCATE userinfo;
TRUNCATE userreq;
TRUNCATE param;

ALTER TABLE `channel`
    ADD CONSTRAINT channel_fkey_tvgrabber FOREIGN KEY (tvg_id) REFERENCES tvgrabber(tvg_id);
ALTER TABLE `grab`
    ADD CONSTRAINT grab_fkey_television FOREIGN KEY (tel_id) REFERENCES television(tel_id);
ALTER TABLE `request`
    ADD CONSTRAINT request_fkey_grab FOREIGN KEY (grb_id) REFERENCES grab(grb_id);
ALTER TABLE `request`
    ADD CONSTRAINT request_fkey_encoder FOREIGN KEY (enc_id) REFERENCES encoder(enc_id);
ALTER TABLE `television`
    ADD CONSTRAINT television_fkey_television FOREIGN KEY (chn_id) REFERENCES channel(chn_id);
ALTER TABLE `userinfo`
    ADD CONSTRAINT userinfo_fkey_television FOREIGN KEY (enc_id) REFERENCES encoder(enc_id);
ALTER TABLE `userreq`
    ADD CONSTRAINT userreq_fkey_request FOREIGN KEY (req_id) REFERENCES request(req_id);
ALTER TABLE `userreq`
    ADD CONSTRAINT userreq_fkey_user FOREIGN KEY (usr_id) REFERENCES userinfo(usr_id);

