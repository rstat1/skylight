CREATE DATABASE IF NOT EXISTS `skylight`;
USE `skylight` ;
CREATE TABLE IF NOT EXISTS `categories` (
  `category` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `group_members` (
  `groupid` int(7) NOT NULL,
  `userid` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(7) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `category` text NOT NULL,
  `author` text NOT NULL,
  `postdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `sessions` (
  `sessid` varchar(255) NOT NULL,
  `ip` text NOT NULL,
  `expiration` int(11) NOT NULL,
  `data` text NOT NULL,
  `userid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `passhash` text NOT NULL,
  `salt` int(2) NOT NULL,
  `theme` text NOT NULL,
  `enablejs` int(1) NOT NULL,
  `email` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;