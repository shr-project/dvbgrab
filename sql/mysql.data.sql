-- phpMyAdmin SQL Dump
-- version 2.6.4-pl4-Debian-1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 15, 2006 at 08:07 PM
-- Server version: 4.1.15
-- PHP Version: 4.4.0-4
-- 
-- Database: `dvbgrab`
-- 

-- 
-- Dumping data for table `channel`
-- 

INSERT INTO `channel` VALUES (1, 'ÈT1', 1);
INSERT INTO `channel` VALUES (2, 'ÈT2', 2);
INSERT INTO `channel` VALUES (3, 'NOVA', 3);
INSERT INTO `channel` VALUES (4, 'PRIMA', 4);

-- 
-- Dumping data for table `encoder`
-- 

INSERT INTO encoder VALUES (1, 'MPEG2', '.mpg', 'encoders/mpeg2.sh', NULL);
INSERT INTO encoder VALUES (2, 'MPEG4', '.avi', 'encoders/mpeg4.sh', NULL);
INSERT INTO encoder VALUES (3, 'MPEG4 - small', '.small.avi', 'encoders/mpeg4-small.sh', NULL);
INSERT INTO encoder VALUES (4, 'MPEG4 - medium', '.medium.avi', 'encoders/mpeg4-medium.sh', NULL);

-- 
-- Dumping data for table `user`
-- 

INSERT INTO `user` VALUES (1, 'ivo', 'test', 'ivo@localhost', 0, 'fidlej@jabber.sh.cvut.cz', '127.0.0.1', 2);
INSERT INTO `user` VALUES (2, 'testa', 'testa', 'ivo@localhost', NULL, '', '0.0.0.0', 1);
