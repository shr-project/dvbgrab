-- phpMyAdmin SQL Dump
-- version 2.8.0.3-Debian-1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 19, 2006 at 11:25 PM
-- Server version: 4.1.15
-- PHP Version: 4.4.0-4
-- 
-- Database: 'dvbgrab'
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table 'channel'
-- 

CREATE TABLE channel (
  chn_id int(11) NOT NULL auto_increment,
  chn_name varchar(20) NOT NULL default '',
  chn_order tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (chn_id),
  UNIQUE KEY chn_order (chn_order)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table 'encoder'
-- 

CREATE TABLE encoder (
  enc_id int(11) NOT NULL auto_increment,
  enc_codec varchar(100) NOT NULL default '',
  enc_suffix varchar(20) NOT NULL default '',
  enc_script varchar(255) NOT NULL default '',
  enc_pid int(11) default NULL,
  PRIMARY KEY  (enc_id)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table 'grab'
-- 

CREATE TABLE grab (
  grb_id int(11) NOT NULL auto_increment,
  tel_id int(11) NOT NULL default '0',
  grb_date_start datetime NOT NULL default '0000-00-00 00:00:00',
  grb_date_end datetime NOT NULL default '0000-00-00 00:00:00',
  grb_status enum('undefined','scheduled','done','missed','processing','deleted','error') NOT NULL default 'scheduled',
  grb_basename varchar(255) default NULL,
  PRIMARY KEY  (grb_id),
  UNIQUE KEY idx_tel_id (tel_id)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table 'request'
-- 

CREATE TABLE request (
  req_id int(11) NOT NULL auto_increment,
  grb_id int(11) NOT NULL default '0',
  usr_id int(11) NOT NULL default '0',
  req_output varchar(255) NOT NULL default '',
  req_operation_id varchar(30) default NULL,
  req_date_start datetime default NULL,
  PRIMARY KEY  (req_id),
  UNIQUE KEY idx_grb_usr (grb_id,usr_id)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table 'television'
-- 

CREATE TABLE television (
  tel_id int(11) NOT NULL auto_increment,
  chn_id int(11) NOT NULL default '0',
  tel_date_start datetime NOT NULL default '0000-00-00 00:00:00',
  tel_name varchar(255) NOT NULL default '',
  tel_desc text NOT NULL,
  PRIMARY KEY  (tel_id),
  UNIQUE KEY idx_tel_chn (chn_id,tel_date_start)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table 'user'
-- 

CREATE TABLE `user` (
  usr_id int(11) NOT NULL auto_increment,
  usr_name varchar(30) NOT NULL default '',
  usr_pass varchar(16) NOT NULL default '',
  usr_email varchar(60) NOT NULL default '',
  usr_icq int(11) default NULL,
  usr_jabber varchar(40) NOT NULL default '',
  usr_ip varchar(15) NOT NULL default '0.0.0.0',
  enc_id int(11) NOT NULL default '2',
  PRIMARY KEY  (usr_id),
  UNIQUE KEY idx_usr_name (usr_name),
  KEY idx_enc_id (enc_id)
) ENGINE=MyISAM;

