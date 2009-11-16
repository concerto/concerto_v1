# Schema dump from Concerto 1.8.1
# Performed on 2009-02-02


CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'Content ID',
  `name` varchar(255) NOT NULL COMMENT 'Content Name',
  `user_id` smallint(5) unsigned default NULL COMMENT 'User ID of who owns the content',
  `content` text NOT NULL COMMENT 'Content in binary format',
  `mime_type` varchar(255) NOT NULL COMMENT 'MIME Content Type',
  `type_id` smallint(5) unsigned NOT NULL COMMENT 'Type of where the content is displayed',
  `duration` smallint(5) unsigned NOT NULL default '5000' COMMENT 'The duration the content has to be displayed',
  `start_time` datetime default NULL COMMENT 'Start date of when the content becomes live',
  `end_time` datetime default NULL COMMENT 'End date of when the content is retired',
  `submitted` datetime NOT NULL COMMENT 'When the content was submitted',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `content-type` (`type_id`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table that stores the content';


CREATE TABLE IF NOT EXISTS `dynamic` (
  `id` smallint(5) NOT NULL auto_increment COMMENT 'Id of the dynamic thing',
  `type` tinyint(1) NOT NULL COMMENT 'Type of dynamic content, 1 = rss, 2 = ndc',
  `path` text NOT NULL COMMENT 'Path to the content',
  `rules` text COMMENT 'Rules used to parse the content',
  `update_interval` int(11) NOT NULL COMMENT 'Frequency the dynamic content is updated',
  `last_update` datetime NOT NULL COMMENT 'Last time the source was refreshed',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='System for handling dynamic sources of content';


CREATE TABLE IF NOT EXISTS `feed` (
  `id` smallint(5) unsigned NOT NULL auto_increment COMMENT 'Feed ID',
  `name` varchar(255) NOT NULL COMMENT 'Feed Name',
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'Group ID of owner of Feed',
  `type` tinyint(1) NOT NULL COMMENT 'Type of feed, 0 = basic, 1 = dynamic',
  `dynamic_id` smallint(5) default NULL COMMENT 'Id of the feed in the dynamic_feed table',
  `description` varchar(255) NOT NULL COMMENT 'brief description of the feed for human consumption',
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table stores every feed';


CREATE TABLE IF NOT EXISTS `feed_content` (
  `feed_id` smallint(5) unsigned NOT NULL COMMENT 'Feed ID for this Feed <-> Content connection',
  `content_id` int(5) unsigned NOT NULL COMMENT 'Content ID for this Feed <-> Content connection',
  `moderation_flag` tinyint(1) default NULL COMMENT 'Moderation flag: 0=Declined, 1=Accepted, NULL=No Action',
  `moderator_id` smallint(5) default NULL COMMENT 'The moderator who has moderated the content',
  `duration` int(10) unsigned NOT NULL COMMENT 'Duration for this content feed mapping',
  `display_count` int(10) unsigned NOT NULL default '0' COMMENT 'Number of times a content has been displayed per feed',
  `yesterday_count` int(10) unsigned NOT NULL default '0' COMMENT 'Number of times the content was displayed on this feed yesterday',
  KEY `feed_id` (`feed_id`),
  KEY `content_id` (`content_id`),
  KEY `moderation_flag` (`moderation_flag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Xref Table between Feed and Content';



CREATE TABLE IF NOT EXISTS `field` (
  `id` smallint(5) unsigned NOT NULL auto_increment COMMENT 'Field ID',
  `name` varchar(255) NOT NULL COMMENT 'Field Name',
  `template_id` smallint(5) unsigned NOT NULL COMMENT 'Corresponding Template ID for this field',
  `type_id` smallint(5) unsigned NOT NULL COMMENT 'Corresponding Type for this field',
  `style` text NOT NULL COMMENT 'Contains CSS to modify the display of the field box.',
  `left` float unsigned NOT NULL COMMENT 'Location of left side',
  `top` float unsigned NOT NULL COMMENT 'Location of top side',
  `width` float unsigned NOT NULL COMMENT 'Width of box',
  `height` float unsigned NOT NULL COMMENT 'Height of box',
  PRIMARY KEY  (`id`),
  KEY `template_id` (`template_id`),
  KEY `content-type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Controls the fields which are the subdivisions in a Layout';


CREATE TABLE IF NOT EXISTS `group` (
  `id` smallint(5) unsigned NOT NULL auto_increment COMMENT 'Group ID',
  `name` varchar(255) NOT NULL COMMENT 'Group Name',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Group Table stores the privilges for each group';


CREATE TABLE IF NOT EXISTS `newsfeed` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'id of newsfeed entry',
  `notification_id` int(10) unsigned NOT NULL COMMENT 'id of the associated notification',
  `user_id` smallint(5) unsigned NOT NULL COMMENT 'user who has this newsfeed entry',
  `hidden` tinyint(1) NOT NULL default '0' COMMENT 'visible status, 0 = visible, 1 = hidden',
  PRIMARY KEY  (`id`),
  KEY `notification_id` (`notification_id`),
  KEY `user_id` (`user_id`),
  KEY `hidden` (`hidden`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Newfeed data';


CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'id of notification',
  `type` varchar(25) NOT NULL COMMENT 'type object',
  `type_id` smallint(5) NOT NULL COMMENT 'type object''s id',
  `by_id` smallint(5) NOT NULL COMMENT 'by object''s id',
  `by_type` varchar(25) NOT NULL COMMENT 'by object',
  `msg` varchar(100) NOT NULL COMMENT 'The action that was performed',
  `additional` text NOT NULL COMMENT 'Additional string of information',
  `processed` smallint(6) NOT NULL COMMENT 'If the notification has been processed yet',
  `timestamp` datetime NOT NULL COMMENT 'When the action occured',
  `text` text NOT NULL COMMENT 'The expanded item text',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `page` (
  `id` tinyint(5) unsigned NOT NULL auto_increment COMMENT 'Canonical url path (text)',
  `page_category_id` smallint(5) unsigned NOT NULL COMMENT 'Document category',
  `name` varchar(64) NOT NULL COMMENT 'Page title',
  `content` text NOT NULL COMMENT 'Page HTML content',
  `user_id` tinyint(11) unsigned NOT NULL COMMENT 'Last edited by',
  `timestamp` date NOT NULL COMMENT 'Time last edited',
  `order` tinyint(5) unsigned NOT NULL COMMENT 'Order for listing content within each category, lowest comes first.  0 indicates default page.',
  `in_menu` tinyint(1) NOT NULL default '1',
  `get_feedback` tinyint(1) NOT NULL default '0' COMMENT 'If true, display a form for user feedback',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `page_category` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `path` varchar(25) NOT NULL,
  `name` varchar(32) NOT NULL COMMENT 'Name of the category',
  `layout` varchar(32) default NULL COMMENT 'filename of layout to use',
  `default_page` int(5) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `position` (
  `id` smallint(5) unsigned NOT NULL auto_increment COMMENT 'Position ID',
  `screen_id` smallint(5) unsigned NOT NULL COMMENT 'Correspoding screen to display the content on',
  `feed_id` smallint(5) unsigned NOT NULL COMMENT 'Feed to be placed in the template',
  `field_id` smallint(5) unsigned NOT NULL COMMENT 'Field in a Template corresponding to the placement',
  `weight` tinyint(3) unsigned NOT NULL default '3' COMMENT 'Content Weighting',
  `display_count` int(10) unsigned NOT NULL default '0' COMMENT 'Number of contents that have been displayed on the position',
  `yesterday_count` int(10) unsigned NOT NULL default '0' COMMENT 'Number of times a positon was shown yesterday',
  PRIMARY KEY  (`id`),
  KEY `screen_id` (`screen_id`),
  KEY `feed_id` (`feed_id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores where every feed is placed on the layout';


CREATE TABLE IF NOT EXISTS `screen` (
  `id` smallint(5) unsigned NOT NULL auto_increment COMMENT 'Content ID',
  `name` varchar(80) NOT NULL COMMENT 'Name of the screen',
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'Group ID of owner of screen',
  `location` varchar(255) NOT NULL COMMENT 'Physical location of the screen',
  `mac_address` bigint(15) unsigned NOT NULL COMMENT 'MAC address of the screen',
  `width` smallint(1) unsigned NOT NULL COMMENT 'Horizontal resolution of the screen',
  `height` smallint(1) unsigned NOT NULL COMMENT 'Vertical resolution of the screen',
  `template_id` smallint(5) unsigned NOT NULL COMMENT 'template used by the screen',
  `last_updated` datetime NOT NULL COMMENT 'Stores the last time the screen was updated',
  `last_ip` varchar(15) NOT NULL COMMENT 'The IP the last update came from',
  `controls_display` tinyint(1) NOT NULL default '0' COMMENT 'true if the machine can control the power state of the display',
  `time_on` varchar(10) NOT NULL default '05:00' COMMENT 'Time the computer should turn the screen on',
  `time_off` varchar(10) NOT NULL default '23:00' COMMENT 'Time the computer should turn the screen off',
  `display_count` int(10) unsigned NOT NULL default '0' COMMENT 'Number of contents that have been displayed on the screen',
  `type` tinyint(4) NOT NULL default '0' COMMENT 'The type of screen',
  `latitude` double default NULL COMMENT 'Physical screen location',
  `longitude` double default NULL COMMENT 'Physical screen location',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `mac_address` (`mac_address`),
  KEY `user_id` (`group_id`),
  KEY `template_id` (`template_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table stores properties of each screen';


CREATE TABLE IF NOT EXISTS `template` (
  `id` smallint(5) unsigned NOT NULL auto_increment COMMENT 'Template ID',
  `name` varchar(255) NOT NULL COMMENT 'Template Name',
  `filename` varchar(255) NOT NULL COMMENT 'Template [XML] filename',
  `height` int(11) NOT NULL COMMENT 'Height of the template file',
  `width` int(11) NOT NULL COMMENT 'Width of the template file',
  `creator` varchar(100) NOT NULL COMMENT 'Author of the template',
  `modified` datetime NOT NULL COMMENT 'Date the template was modified',
  `hidden` tinyint(1) NOT NULL COMMENT 'Flag to hide the template or not',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores predefined templates';


CREATE TABLE IF NOT EXISTS `type` (
  `id` smallint(5) unsigned NOT NULL COMMENT 'Content Type ID',
  `name` varchar(255) NOT NULL COMMENT 'Content Type Name',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Content Type used in the feeds';


CREATE TABLE IF NOT EXISTS `user` (
  `id` smallint(5) unsigned NOT NULL auto_increment COMMENT 'User ID',
  `username` varchar(65) NOT NULL COMMENT 'Username which cannot exceed 65 characters',
  `password` varchar(35) NOT NULL COMMENT 'Users password hash',
  `name` varchar(255) NOT NULL COMMENT 'Fullname of the user',
  `email` varchar(75) NOT NULL COMMENT 'Email address of the user',
  `admin_privileges` tinyint(1) NOT NULL COMMENT 'Property to see if the user is an administrator',
  `allow_email` tinyint(1) NOT NULL default '1' COMMENT 'Can we send the user mail',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `admin_privileges` (`admin_privileges`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='User Table stores user information';


CREATE TABLE IF NOT EXISTS `user_group` (
  `user_id` smallint(5) unsigned NOT NULL COMMENT 'Corresponding User ID',
  `group_id` smallint(5) unsigned NOT NULL COMMENT 'Corresponding Group ID',
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Xref Table to match users with multiple groups';

