--
-- PostgreSQL database dump
--
DROP TABLE "television";
DROP TABLE "channel";
DROP TABLE "grab";
DROP TABLE "request";
DROP TABLE "user";
DROP TABLE "encode";

CREATE TABLE "television" (
  tel_id integer NOT NULL,
  chn_id integer DEFAULT 0 NOT NULL,
  tel_date_start timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  tel_name character varying(255) DEFAULT ''::character varying NOT NULL,
  tel_desc text NOT NULL,
  PRIMARY KEY  (tel_id)
);

CREATE TABLE "channel" (
  chn_id integer NOT NULL,
  chn_name varchar(20) DEFAULT ''::character varying NOT NULL,
  chn_order smallint DEFAULT 0 NOT NULL,
  PRIMARY KEY  (chn_id)
);

CREATE TABLE "grab" (
  grb_id integer NOT NULL,
  tel_id integer DEFAULT 0 NOT NULL,
  grb_date_start timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  grb_date_end timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  grb_vote integer DEFAULT 0 NOT NULL,
  grb_status smallint DEFAULT 1 NOT NULL,
-- ENUM ('undefined','scheduled','collision','done','missed','processing','deleted','error') NOT NULL,
-- means  0           1           2           3      4        5            6         7
  grb_enc smallint DEFAULT 1 NOT NULL,
  PRIMARY KEY  (grb_id)
);

CREATE TABLE "request" (
  grb_id integer DEFAULT 0 NOT NULL,
  usr_id integer DEFAULT 0 NOT NULL,
  PRIMARY KEY  (grb_id,usr_id)
);

CREATE TABLE "user" (
  usr_id integer NOT NULL,
  usr_name varchar(30) NOT NULL,
  usr_pass varchar(16) NOT NULL,
  usr_email varchar(60) NOT NULL,
  usr_icq integer,
  usr_jabber varchar(40),
  usr_ip varchar(15) DEFAULT '0.0.0.0' NOT NULL,
  usr_priority smallint DEFAULT 2 NOT NULL,
  PRIMARY KEY  (usr_id)
);

CREATE TABLE "vote" (
  grb_id integer DEFAULT 0 NOT NULL,
  usr_id integer DEFAULT 0 NOT NULL,
  PRIMARY KEY  (grb_id,usr_id)
);

CREATE TABLE "encode" (
  enc_id integer DEFAULT 0 NOT NULL,
  grb_id  integer DEFAULT 0 NOT NULL,
  grb_date_start timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  grb_name text NOT NULL,
  PRIMARY KEY  (enc_id)
);

