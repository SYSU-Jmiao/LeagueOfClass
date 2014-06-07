create database if not exists `leagueofclass`;

use `leagueofclass`;

create table if not exists `student` (
	`email` char(100) not null,
	`password` char(40) not null,
	`realname` char(20),
	`accessdate` date not null,
	primary key (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `salt` (
	`email` char(100) not null,
	`pre_salt` char(10) not null,
	`post_salt` char(10) not null,
	primary key (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `createclassnotice`(
	`noticeid` int(30) unsigned not null AUTO_INCREMENT,
	`classname` char(40) not null,
	`applicant` char(100) not null,
	`reason` text,
	primary key(noticeid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `class`(
	`classid` int(10) unsigned not null AUTO_INCREMENT,
	`classname` char(40) not null,
	`createday` date not null,
	primary key(classid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `authority`(
	`useremail` char(100) not null,
	`classid` int(10) unsigned not null,
	`admin` int(10) unsigned not null default '0',
	primary key(useremail, classid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `newmembernotice`(
	`noticeid` int(30) unsigned not null AUTO_INCREMENT,
	`classid` char(10) not null,
	`applicant` char(100) not null,
	`reason` text,
	primary key(noticeid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `classnews`(
	`newsid` int(30) unsigned not null AUTO_INCREMENT,
	`classid` int(10) unsigned not null,
	`author` char(100) not null,
	`content` text,
	`posttime` datetime not null,
	primary key(newsid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `classchat`(
	`chatid` int(30) unsigned not null AUTO_INCREMENT,
	`classid` int(10) unsigned not null,
	`author` char(100),
	`content` text,
	`posttime` datetime not null,
	primary key(chatid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `cookie` (
	`sso` varchar(64) not null,
	`useremail` char(100) not null,
	`ip` varchar(20) not null,
	`time` int(12) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `resource` (
	`name` varchar(80) DEFAULT NULL,
	`postdate` datetime DEFAULT NULL,
	`description` text,
	`downloadtimes` int(5) not null,
	`uploader` char(100) not null,
	`classid` int(10) unsigned not null,
	`category` char(20) not null
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
