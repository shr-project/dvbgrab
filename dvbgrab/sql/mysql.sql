-- phpMyAdmin SQL Dump
-- version 2.6.1-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 11, 2005 at 02:40 AM
-- Server version: 4.0.22
-- PHP Version: 4.3.10
-- 
-- Database: `tvgrab`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `channel`
-- 

CREATE TABLE IF NOT EXISTS `channel` (
  `chn_id` int(11) NOT NULL auto_increment,
  `chn_name` varchar(20) NOT NULL default '',
  `chn_order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`chn_id`),
  UNIQUE KEY `chn_order` (`chn_order`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `grab`
-- 

CREATE TABLE IF NOT EXISTS `grab` (
  `grb_id` int(11) NOT NULL auto_increment,
  `tel_id` int(11) NOT NULL default '0',
  `grb_date_start` datetime NOT NULL default '0000-00-00 00:00:00',
  `grb_date_end` datetime NOT NULL default '0000-00-00 00:00:00',
  `grb_vote` int(11) NOT NULL default '0',
  `grb_status` enum('undefined','scheduled','done','missed','processing','deleted','error') NOT NULL default 'scheduled',
  PRIMARY KEY  (`grb_id`),
  UNIQUE KEY `idx_tel_id` (`tel_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- COMMENTS FOR TABLE `grab`:
--   `grb_enc`
--       `Should we encode it?`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `request`
-- 

CREATE TABLE IF NOT EXISTS `request` (
	`req_id` int(11) NOT NULL auto_increment,
  `grb_id` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `grb_enc` tinyint(4) NOT NULL default '1',
  `req_output` varchar(255) default '',
  PRIMARY KEY  (`req_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `television`
-- 

CREATE TABLE IF NOT EXISTS `television` (
  `tel_id` int(11) NOT NULL auto_increment,
  `chn_id` int(11) NOT NULL default '0',
  `tel_date_start` datetime NOT NULL default '0000-00-00 00:00:00',
  `tel_name` varchar(255) NOT NULL default '',
  `tel_desc` text NOT NULL,
  PRIMARY KEY  (`tel_id`),
  UNIQUE KEY `idx_tel_chn` (`chn_id`,`tel_date_start`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

CREATE TABLE IF NOT EXISTS `user` (
  `usr_id` int(11) NOT NULL auto_increment,
  `usr_name` varchar(30) binary NOT NULL default '',
  `usr_pass` varchar(16) NOT NULL default '',
  `usr_email` varchar(60) NOT NULL default '',
  `usr_icq` int(11) default NULL,
  `usr_jabber` varchar(40) NOT NULL default '',
  `usr_ip` varchar(15) NOT NULL default '0.0.0.0',
  `usr_priority` tinyint(4) NOT NULL default '2',
  PRIMARY KEY  (`usr_id`),
  UNIQUE KEY `idx_usr_ip` (`usr_ip`),
  UNIQUE KEY `idx_usr_name` (`usr_name`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `encode`
-- 

CREATE TABLE IF NOT EXISTS `encode` (
  `enc_id`  int(11) NOT NULL auto_increment,
  `grb_id`  int(11) NOT NULL default '0',
  `grb_date_start` datetime NOT NULL default '0000-00-00 00:00:00',
  `grb_name` text NOT NULL,
  PRIMARY KEY  (`enc_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
