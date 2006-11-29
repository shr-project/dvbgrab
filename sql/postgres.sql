--
-- PostgreSQL database dump
-- CreateUser 
-- postgres@host ~ $ createuser dvbgrab
-- Shall the new role be a superuser? (y/n) n
-- Shall the new role be allowed to create databases? (y/n) n
-- Shall the new role be allowed to create more new roles? (y/n) n
-- CREATE ROLE
--
-- CreateDb
-- postgres@jama ~ $ createdb -O dvbgrab -E utf8 dvbgrab
-- CREATE DATABASE
--

SET client_encoding = 'UTF8';

DROP SEQUENCE seq_chn_id;
DROP SEQUENCE seq_enc_id;
DROP SEQUENCE seq_grb_id;
DROP SEQUENCE seq_req_id;
DROP SEQUENCE seq_tel_id;
DROP SEQUENCE seq_tvg_id;
DROP SEQUENCE seq_usr_id;
DROP SEQUENCE seq_urq_id;
DROP TABLE "channel" CASCADE;
DROP TABLE "tvgrabber" CASCADE;
DROP TABLE "encoder" CASCADE;
DROP TABLE "grab" CASCADE;
DROP TABLE "request" CASCADE;
DROP TABLE "userreq" CASCADE;
DROP TABLE "television" CASCADE;
DROP TABLE "userinfo" CASCADE;
DROP TABLE "param" CASCADE;
CREATE SEQUENCE seq_chn_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
CREATE SEQUENCE seq_enc_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
CREATE SEQUENCE seq_grb_id
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
CREATE SEQUENCE seq_req_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
CREATE SEQUENCE seq_tel_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
CREATE SEQUENCE seq_tvg_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
CREATE SEQUENCE seq_usr_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
CREATE SEQUENCE seq_urq_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE TABLE "channel" (
  chn_id          integer        DEFAULT nextval('"seq_chn_id"'::text)  NOT NULL,
  chn_name        varchar(20)    DEFAULT ''                               NOT NULL,
  chn_xmltv_name  varchar(80)    DEFAULT ''                               NOT NULL,
  chn_logo        varchar(20)    DEFAULT ''                               NOT NULL,
  chn_order       smallint       DEFAULT 0                                NOT NULL,
  chn_ip          varchar(15)    DEFAULT '239.194.1.1'                    NOT NULL,
  chn_port        integer        DEFAULT 1234                             NOT NULL,
  chn_enabled     integer        DEFAULT 1                                NOT NULL,
  tvg_id          integer                                                 NOT NULL,
  PRIMARY KEY (chn_id)
);

CREATE TABLE "tvgrabber" (
  tvg_id          integer         DEFAULT nextval('"seq_tvg_id"'::text) NOT NULL,
  tvg_name        varchar(100)    DEFAULT ''                              NOT NULL,
  tvg_enabled     integer         DEFAULT 1                               NOT NULL,
  tvg_cron_time   varchar(50)     DEFAULT '0 23 * * 6'                    NOT NULL,
  tvg_cron_cmd    varchar(150)    DEFAULT 'xmltv grabber'                 NOT NULL,
  PRIMARY KEY     (tvg_id)
);

CREATE TABLE "encoder" (
  enc_id          integer         DEFAULT nextval('"seq_enc_id"'::text) NOT NULL,
  enc_codec       varchar(100)    DEFAULT ''                              NOT NULL,
  enc_suffix      varchar(20)     DEFAULT ''                              NOT NULL,  
  enc_script      varchar(255)    DEFAULT ''                              NOT NULL,
  enc_pid         integer         DEFAULT NULL,
  PRIMARY KEY     (enc_id)
);

CREATE TABLE "grab" (
  grb_id          integer         DEFAULT nextval('"seq_grb_id"'::text) NOT NULL,
  tel_id          integer         DEFAULT 0                               NOT NULL,
  grb_date_start  timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  grb_date_end    timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  grb_name        varchar(255)    DEFAULT ''                              NOT NULL,
  PRIMARY KEY     (grb_id)
);

CREATE TABLE "request" (
  req_id          integer         DEFAULT nextval('"seq_req_id"'::text) NOT NULL,
  grb_id          integer         DEFAULT 0                               NOT NULL,
  enc_id          integer         DEFAULT 1                               NOT NULL,
  req_output      varchar(255)    DEFAULT ''                              NOT NULL,
  req_output_md5  varchar(80)     DEFAULT ''                              NOT NULL,
  req_output_size integer         DEFAULT 0                               NOT NULL,
  req_status      varchar(20)     DEFAULT ''                              NOT NULL,
  PRIMARY KEY     (req_id),
  UNIQUE_KEY      (grb_id,enc_id)
);

CREATE TABLE "userreq" (
  urq_id          integer         DEFAULT nextval('"seq_urq_id"'::text) NOT NULL,
  req_id          integer         DEFAULT 0                             NOT NULL,
  usr_id          integer         DEFAULT 0                             NOT NULL,
  urq_output      varchar(255)    DEFAULT ''                            NOT NULL,
  PRIMARY KEY     (usr_id,req_id)
);


CREATE TABLE "television" (
  tel_id          integer         DEFAULT nextval('"seq_tel_id"'::text) NOT NULL,
  chn_id          integer         DEFAULT 0                               NOT NULL,
  tel_date_start  timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  tel_date_end    timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  tel_name        varchar(255)    DEFAULT ''                              NOT NULL,
  tel_desc        text                                                    NOT NULL,
  tel_typ         varchar(50)     DEFAULT '',
  tel_category    varchar(50)     DEFAULT '',
  tel_series      varchar(15),
  tel_episode     varchar(15),
  tel_part        varchar(15),
  PRIMARY KEY     (tel_id)
);

CREATE TABLE "userinfo" (
  usr_id          integer         DEFAULT nextval('"seq_usr_id"'::text) NOT NULL,
  usr_name        varchar(30)                                             NOT NULL,
  usr_pass        varchar(52)                                             NOT NULL,
  usr_email       varchar(60)                                             NOT NULL,
  usr_icq         integer,
  usr_jabber      varchar(40),
  usr_ip          varchar(40)     DEFAULT '0.0.0.0'                       NOT NULL,
  usr_priority    smallint        DEFAULT 2                               NOT NULL,
  enc_id          integer         DEFAULT 2                               NOT NULL,
  usr_last_activity   timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  usr_last_update     timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  PRIMARY KEY     (usr_id)
);

CREATE TABLE IF NOT EXISTS "param" (
  par_key          varchar(40)    DEFAULT '' NOT NULL,
  par_val          varchar(255)   DEFAULT '' NOT NULL,
  PRIMARY KEY     (par_key)
);

CREATE TABLE "news" (
  news_date timestamp with time zone DEFAULT '2000-01-01 00:00:00+01'::timestamp with time zone NOT NULL,
  news_text text NOT NULL
);

ALTER TABLE "channel"
    ADD CONSTRAINT channel_fkey_tvgrabber FOREIGN KEY (tvg_id) REFERENCES tvgrabber(tvg_id);
ALTER TABLE ONLY "grab"
    ADD CONSTRAINT grab_fkey_television FOREIGN KEY (tel_id) REFERENCES television(tel_id);
ALTER TABLE ONLY "request"
    ADD CONSTRAINT request_fkey_grab FOREIGN KEY (grb_id) REFERENCES grab(grb_id);
ALTER TABLE ONLY "request"
    ADD CONSTRAINT request_fkey_user FOREIGN KEY (usr_id) REFERENCES userinfo(usr_id);
ALTER TABLE ONLY "request"
    ADD CONSTRAINT request_fkey_encoder FOREIGN KEY (enc_id) REFERENCES encoder(enc_id);
ALTER TABLE ONLY "television"
    ADD CONSTRAINT television_fkey_television FOREIGN KEY (chn_id) REFERENCES channel(chn_id);
ALTER TABLE ONLY "userinfo"
    ADD CONSTRAINT userinfo_fkey_television FOREIGN KEY (enc_id) REFERENCES encoder(enc_id);
ALTER TABLE ONLY "userreq"
    ADD CONSTRAINT userreq_fkey_request FOREIGN KEY (req_id) REFERENCES request(req_id);
ALTER TABLE ONLY "userreq"
    ADD CONSTRAINT userreq_fkey_user FOREIGN KEY (usr_id) REFERENCES userinfo(usr_id);

/* 
  INSERT DATA
  ./convert.sh zaloha.sql zaloha.new
  cat postgres.sql | psql -U dvbgrab dvbgrab > convert.log 2>&1                  
  cat test.data.sql zaloha.new.userinfo.sql zaloha.new.television.sql zaloha.new.grab.sql zaloha.new.request.sql zaloha.new.userreq.sql | psql -U dvbgrab dvbgrab >> convert.log 2>&1
echo "
delete from television where tel_id NOT IN (select tel_id from grab);
SELECT pg_catalog.setval('seq_chn_id', (select max(chn_id) from channel)+1, true);
SELECT pg_catalog.setval('seq_enc_id', (select max(enc_id) from encoder)+1, true);
SELECT pg_catalog.setval('seq_grb_id', (select max(grb_id) from grab)+1, true);
SELECT pg_catalog.setval('seq_req_id', (select max(req_id) from request)+1, true);
SELECT pg_catalog.setval('seq_tel_id', (select max(tel_id) from television)+1, true);
SELECT pg_catalog.setval('seq_tvg_id', (select max(tvg_id) from tvgrabber)+1, true);
SELECT pg_catalog.setval('seq_usr_id', (select max(usr_id) from userinfo)+1, true);
SELECT pg_catalog.setval('seq_urq_id', (select max(urq_id) from userreq)+1, true);
" | psql -U dvbgrab dvbgrab  >> convert.log 2>&1

  OR insert new
*/
SELECT pg_catalog.setval('seq_chn_id', 1, true);
SELECT pg_catalog.setval('seq_enc_id', 1, true);
SELECT pg_catalog.setval('seq_grb_id', 1, true);
SELECT pg_catalog.setval('seq_req_id', 1, true);
SELECT pg_catalog.setval('seq_tel_id', 1, true);
SELECT pg_catalog.setval('seq_tvg_id', 1, true);
SELECT pg_catalog.setval('seq_usr_id', 1, true);
SELECT pg_catalog.setval('seq_urq_id', 1, true);
