-- All actions are done by users!
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `active` tinyint(1) NOT NULL COMMENT 'Is it active?',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time',
  `name` char(255) NOT NULL COMMENT 'Unique name',
  `password` char(66) NOT NULL COMMENT '2 characters salt + base64-encoded SHA256 hash of the password',
  `email` char(255) NOT NULL COMMENT 'Email address',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- Each proposal has a well defined type.
--
-- Maybe it should be replaced by an enumerate?
CREATE TABLE IF NOT EXISTS `types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `active` tinyint(1) NOT NULL COMMENT 'Is it active?',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time',
  `user_id` int(10) unsigned NOT NULL COMMENT 'Creator',
  `name` char(255) NOT NULL COMMENT 'Human readable name (unique)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- Each proposal is linked to a particular event.
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `active` tinyint(1) NOT NULL COMMENT 'Is it active?',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time',
  `user_id` int(10) unsigned NOT NULL COMMENT 'Creator',
  `name` char(255) NOT NULL COMMENT 'Human readable name',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `proposals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `active` tinyint(1) NOT NULL COMMENT 'Is it active?',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time',
  `event_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL COMMENT 'Type of proposal (accomodation, car pool, …)',
  `slots` tinyint(3) unsigned NOT NULL COMMENT 'Total slots',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- A reservation is linked to a proposal.
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `active` tinyint(1) NOT NULL COMMENT 'Is it active?',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time',
  `proposal_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `slots` tinyint(3) unsigned NOT NULL COMMENT 'Number of reserved slots',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
