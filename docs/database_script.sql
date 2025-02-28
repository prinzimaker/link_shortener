/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024-2025 - Aldo Prinzi
=====================================================================
 This web app needs just Apache, PHP (7.4->8.3) and MariaDB to work.
---------------------------------------------------------------------
 This SQL SCRIPT contains the complete database creation script.
=====================================================================
 Copy the following sql commands and paste them in your MySQL client
 to create the database and the tables.
 Take care of "CREATE USER" at the start of the script and modify by
 yourself using username and password you prefer.
---------------------------------------------------------------------
*/

create database shortlinks;
CREATE USER 'shlnkusr'@'localhost' IDENTIFIED BY 'chooseAPassword';
GRANT ALL PRIVILEGES ON shortlinks.* TO 'shlnkusr'@'localhost';
FLUSH PRIVILEGES;
use shortlinks;
create table link (
    short_id    varchar(10) not null,
    full_uri    longtext,
    sha_uri     varchar(128),
    cust_id     int(10) unsigned not null,
    created     timestamp not null default current_timestamp(),
    calls       int(10) unsigned not null default 0,
    last_call   datetime default '1999-12-31 23:59:59',
    PRIMARY KEY (short_id),
    UNIQUE KEY uri_sha_uniq (sha_uri)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
create table calls(
    short_id varchar(10) not null,
    call_log longtext,
    PRIMARY KEY (short_id),
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
create table customers (
	cust_id int(10) unsigned NOT NULL AUTO_INCREMENT,
	descr    varchar(100),
	email    varchar(50),
	email_verif_code varchar(64),
    email_verified BOOLEAN DEFAULT FALSE,
	pass     varchar(128),
	active   BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE,
	apikey   varchar(64),
	max_links int(3) unsigned,
	PRIMARY KEY (cust_id),
	UNIQUE (email)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
insert into customers (descr, email, pass, active, apikey,is_admin, max_links) values ('the administrator', 'Admin', '', true, 'APYKEY123456',true, 9999);
insert into link (short_id,full_uri,cust_id,sha_uri) values ('pls_about','https://github.com/prinzimaker/link_shortener',1,'AAA1');
insert into link (short_id,full_uri,cust_id,sha_uri) values ('pls_redoc','https://redocly.github.io/redoc/?url=https://prinzimaker.github.io/link_shortener/openapi.yaml',1,'AAA2');
insert into link (short_id,full_uri,cust_id,sha_uri) values ('pls_swagger','https://prinzimaker.github.io/link_shortener/',1,'AAA3');
create table ip2location (
    ip_from   int(10) unsigned NOT NULL,
    ip_to     int(10) unsigned NOT NULL,
    state_id  char(2) NOT NULL default 'XX',
    state_txt varchar(64) NOT NULL default 'Unknown',
    region    varchar(64) NOT NULL default 'Unknown',
    city      varchar(64) NOT NULL default 'Unknown',
    geo_lat   decimal(10,6),
    geo_long  decimal(10,6),
    pobox     varchar(15) NOT NULL default 'XXXXXX',
    timezone  varchar(6) NOT NULL default '00:00',
    PRIMARY KEY (ip_from, ip_to)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

