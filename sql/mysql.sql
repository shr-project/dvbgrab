-- Database: `dvbgrab`

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
  `grb_name`       datetime      NOT NULL default '',
  PRIMARY KEY      (`grb_id`),
  UNIQUE KEY `idx_tel_id` (`tel_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `request` (
  `req_id`         int(11)       NOT NULL auto_increment,
  `grb_id`         int(11)       NOT NULL default '0',
  `usr_id`         int(11)       NOT NULL default '0',
  `enc_id`         int(11)       NOT NULL default '1',
  `req_output`     varchar(255)           default '',
  `req_output_md5` varchar(80)            default '',
  `req_output_size`int(20)                default '0',
  `req_status`     enum('undefined','scheduled','done','missed','processing','deleted','error') NOT NULL default 'scheduled',
  PRIMARY KEY      (`req_id`),
  UNIQUE KEY idx_grb_usr (grb_id,usr_id)
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

CREATE TABLE IF NOT EXISTS `usergrb` (
  `usr_id`         int(11)       NOT NULL auto_increment,
  `usr_name`       varchar(30)   binary NOT NULL default '',
  `usr_pass`       varchar(52)   NOT NULL default '',
  `usr_email`      varchar(60)   NOT NULL default '',
  `usr_icq`        int(11)                default NULL,
  `usr_jabber`     varchar(40)   NOT NULL default '',
  `usr_ip`         varchar(15)   NOT NULL default '0.0.0.0',
  `usr_priority`   tinyint(4)    NOT NULL default '2',
  `enc_id`         int(11)       NOT NULL default '2',
  `usr_last_activity` datetime   NOT NULL default '0000-00-00 00:00:00',
  `usr_last_update`   datetime   NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY      (`usr_id`),
  UNIQUE KEY `idx_usr_name` (`usr_name`),
  KEY idx_enc_id (enc_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `params` (
  `last_account_update`   datetime      NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM;

TRUNCATE channel;
TRUNCATE encoder;
TRUNCATE grab;
TRUNCATE request;
TRUNCATE television;
TRUNCATE tvgrabber;
TRUNCATE usergrb;
TRUNCATE params;
