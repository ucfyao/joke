-- 
-- 表的结构 `loowei_admin`
-- 

CREATE TABLE `loowei_admin` (
  `admin_id` mediumint(9) NOT NULL auto_increment,
  `admin_name` char(15) NOT NULL,
  `admin_pwd` char(32) NOT NULL,
  `admin_realname` char(5) NOT NULL,
  `admin_email` varchar(50) NOT NULL,
  `admin_qq` char(15) NOT NULL,
  `admin_phone` char(15) NOT NULL,
  `admin_login_count` mediumint(9) NOT NULL,
  `admin_last_time` int(11) NOT NULL,
  `admin_last_ip` int(11) NOT NULL,
  `admin_level` tinyint(1) NOT NULL,
  `admin_status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_adver`
-- 

CREATE TABLE `loowei_adver` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `code` text NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_article`
-- 

CREATE TABLE `loowei_article` (
  `id` mediumint(9) NOT NULL auto_increment,
  `uid` mediumint(9) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `pic_url` varchar(100) NOT NULL,
  `pic_height` smallint(6) NOT NULL,
  `pic_width` smallint(6) NOT NULL,
  `video` varchar(255) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `ip` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `upper` mediumint(9) NOT NULL,
  `below` mediumint(9) NOT NULL,
  `reply` smallint(6) NOT NULL,
  `is_anonymous` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_config`
-- 

CREATE TABLE `loowei_config` (
  `name` varchar(20) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans`
-- 

CREATE TABLE `loowei_fans` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_1`
-- 

CREATE TABLE `loowei_fans_1` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_2`
-- 

CREATE TABLE `loowei_fans_2` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_3`
-- 

CREATE TABLE `loowei_fans_3` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_4`
-- 

CREATE TABLE `loowei_fans_4` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_5`
-- 

CREATE TABLE `loowei_fans_5` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_6`
-- 

CREATE TABLE `loowei_fans_6` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_7`
-- 

CREATE TABLE `loowei_fans_7` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_8`
-- 

CREATE TABLE `loowei_fans_8` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_fans_9`
-- 

CREATE TABLE `loowei_fans_9` (
  `follow_uid` mediumint(9) NOT NULL,
  `fans_uid` mediumint(9) NOT NULL,
  KEY `follow_uid` (`follow_uid`),
  KEY `fans_uid` (`fans_uid`),
  KEY `follow_uid_2` (`follow_uid`,`fans_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_flink`
-- 

CREATE TABLE `loowei_flink` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `info` varchar(100) NOT NULL,
  `url` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_hot_tag`
-- 

CREATE TABLE `loowei_hot_tag` (
  `tag_id` int(11) NOT NULL auto_increment,
  `tag_name` varchar(20) NOT NULL,
  `tag_sort` int(11) NOT NULL,
  `tag_status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`tag_id`),
  KEY `tag_status` (`tag_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_new`
-- 

CREATE TABLE `loowei_new` (
  `id` mediumint(9) NOT NULL auto_increment,
  `uid` mediumint(9) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `pic_url` varchar(100) NOT NULL,
  `pic_height` smallint(6) NOT NULL,
  `pic_width` smallint(6) NOT NULL,
  `video` varchar(255) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `ip` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `upper` tinyint(3) NOT NULL,
  `below` tinyint(3) NOT NULL,
  `is_anonymous` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply`
-- 

CREATE TABLE `loowei_reply` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_0`
-- 

CREATE TABLE `loowei_reply_0` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_1`
-- 

CREATE TABLE `loowei_reply_1` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_2`
-- 

CREATE TABLE `loowei_reply_2` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_3`
-- 

CREATE TABLE `loowei_reply_3` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_4`
-- 

CREATE TABLE `loowei_reply_4` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_5`
-- 

CREATE TABLE `loowei_reply_5` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_6`
-- 

CREATE TABLE `loowei_reply_6` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_7`
-- 

CREATE TABLE `loowei_reply_7` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_8`
-- 

CREATE TABLE `loowei_reply_8` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_reply_9`
-- 

CREATE TABLE `loowei_reply_9` (
  `reply_id` mediumint(9) NOT NULL auto_increment,
  `reply_sort` smallint(6) NOT NULL,
  `article_id` mediumint(9) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `reply_ip` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `reply_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_user`
-- 

CREATE TABLE `loowei_user` (
  `uid` mediumint(9) NOT NULL auto_increment,
  `nickname` char(30) NOT NULL,
  `email` char(40) NOT NULL,
  `pwd` char(32) NOT NULL,
  `avatar_suffix` char(10) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `reg_time` int(11) NOT NULL,
  `reg_ip` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  `last_ip` int(11) NOT NULL,
  `emailstatus` tinyint(4) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `loowei_user_bind`
-- 

CREATE TABLE `loowei_user_bind` (
  `uid` mediumint(9) NOT NULL,
  `opentype` tinyint(1) NOT NULL,
  `openid` char(50) NOT NULL,
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='绑定其他网站帐号';