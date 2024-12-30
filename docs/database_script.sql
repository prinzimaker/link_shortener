/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024 - Aldo Prinzi
=====================================================================
 This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
 This class contains the Database Creator for the link shortener
 -
 v1.1.0 - Aldo Prinzi - 23 Dic 2024
=====================================================================
 Copy the following sql commands and paste them in your MySQL client
 to create the database and the tables needed for the link shortener
---------------------------------------------------------------------
*/

create database shortlinks;

use shortlinks;

create table link (
    short_id varchar(10) not null,
    full_uri longtext,
    sha_uri varchar(128),
    cust_id int(10) unsigned not null,
    created timestamp not null default current_timestamp(),
    calls int(10) unsigned not null default 0,
    last_call datetime default '1999-12-31 23:59:59',
    PRIMARY KEY (short_id),
    UNIQUE KEY uri_sha_uniq (sha_uri)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

create table calls(
    short_id varchar(10) not null,
    call_log longtext,
    PRIMARY KEY (short_id),
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

create table customer (
	cust_id int(10) unsigned NOT NULL AUTO_INCREMENT,
	descr varchar(100),
	email varchar(50),
	email_verif_code varchar(64),
    email_verified BOOLEAN DEFAULT FALSE,
	pass varchar(128),
	active int(1) unsigned,
	apikey varchar(64),
	max_links int(3) unsigned,
	PRIMARY KEY (cust_id),
	UNIQUE (email)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

create table custlinks (
    cust_id int(10) unsigned NOT NULL,
    short_id varchar(10) not null,
    PRIMARY KEY (cust_id, short_id),
    FOREIGN KEY (cust_id) REFERENCES customer(cust_id) ON DELETE CASCADE,
    FOREIGN KEY (short_id) REFERENCES link(short_id) ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE USER 'shlnkusr'@'localhost' IDENTIFIED BY 'laTuaPass';
GRANT ALL PRIVILEGES ON shortlinks.* TO 'shlnkusr'@'localhost';
FLUSH PRIVILEGES;
