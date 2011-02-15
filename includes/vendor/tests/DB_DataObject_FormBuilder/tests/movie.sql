-- MySQL dump 9.11
--
-- Host: localhost    Database: FormBuilder_testing
-- ------------------------------------------------------
-- Server version	4.0.24

--
-- Table structure for table `audioFormat`
--

CREATE TABLE `audioFormat` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `audioFormat`
--

INSERT INTO `audioFormat` VALUES (1,'Mono');
INSERT INTO `audioFormat` VALUES (2,'Stereo');
INSERT INTO `audioFormat` VALUES (3,'Dolby Pro Logic');
INSERT INTO `audioFormat` VALUES (4,'Dolby Digital 5.1');
INSERT INTO `audioFormat` VALUES (5,'Dolby Digital EX');
INSERT INTO `audioFormat` VALUES (6,'DTS');

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` VALUES (1,'Sci-Fi');
INSERT INTO `genre` VALUES (2,'Fantasy');
INSERT INTO `genre` VALUES (3,'Romance');
INSERT INTO `genre` VALUES (4,'Action');
INSERT INTO `genre` VALUES (5,'Horror');
INSERT INTO `genre` VALUES (13,'Alien');

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `language`
--

INSERT INTO `language` VALUES (1,'English');
INSERT INTO `language` VALUES (2,'German');
INSERT INTO `language` VALUES (3,'French');
INSERT INTO `language` VALUES (4,'Spanish');
INSERT INTO `language` VALUES (5,'Japanese');
INSERT INTO `language` VALUES (6,'Chinese');

--
-- Table structure for table `linkTest`
--

CREATE TABLE `linkTest` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(10) default NULL,
  `name2` varchar(15) default NULL,
  `linkTest2_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `linkTest`
--

INSERT INTO `linkTest` VALUES (1,'1name','2name',1);
INSERT INTO `linkTest` VALUES (2,'test 2','test 2 2',1);
INSERT INTO `linkTest` VALUES (3,'test 3','test 3 2',2);
INSERT INTO `linkTest` VALUES (4,'test','testt',NULL);
INSERT INTO `linkTest` VALUES (5,'another','yep',2);
INSERT INTO `linkTest` VALUES (6,'test','2 test',NULL);

--
-- Table structure for table `linkTest2`
--

CREATE TABLE `linkTest2` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(10) default NULL,
  `name2` varchar(15) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `linkTest2`
--

INSERT INTO `linkTest2` VALUES (1,'1 name','2 name');
INSERT INTO `linkTest2` VALUES (2,'blah','foo');
INSERT INTO `linkTest2` VALUES (3,'blah','abc');
INSERT INTO `linkTest2` VALUES (4,'straw','abc');

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `genre_id` int(10) unsigned NOT NULL default '0',
  `dateAcquired` datetime default NULL,
  `enumTest` enum('option 1','option 2','option 3') default NULL,
  `enumTest2` enum('option a','option b','optionc3') default NULL,
  `anotherField` varchar(20) default NULL,
  `timeField` time default NULL,
  `dateField` date default NULL,
  `linkTest_id` int(10) unsigned default NULL,
  `summary` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` VALUES (4,'Alien',5,'2005-05-24 22:41:51','option 2','optionc3','',NULL,NULL,1,NULL);
INSERT INTO `movie` VALUES (15,'Alien Resurrection',4,'2005-06-06 23:08:37','option 2','','',NULL,NULL,1,NULL);
INSERT INTO `movie` VALUES (13,'Aliens',4,'2005-06-06 23:08:23','option 2','','',NULL,NULL,1,NULL);
INSERT INTO `movie` VALUES (14,'Alien3',4,'2005-06-06 23:08:32','option 3','','',NULL,NULL,1,NULL);
INSERT INTO `movie` VALUES (16,'Brazil',4,'2005-06-06 23:08:44','option 1','','',NULL,NULL,1,NULL);
INSERT INTO `movie` VALUES (17,'Shawn of the Dead',5,'2005-06-06 23:08:55','option 1','','',NULL,NULL,1,NULL);
INSERT INTO `movie` VALUES (18,'12 Monkeys',4,'2005-06-06 23:09:01','option 1','','',NULL,NULL,1,NULL);
INSERT INTO `movie` VALUES (19,'The Ring',5,'2005-06-23 02:39:53','option 3','optionc3','',NULL,NULL,1,NULL);

--
-- Table structure for table `movie_audioFormat_language`
--

CREATE TABLE `movie_audioFormat_language` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `movie_id` int(10) unsigned default NULL,
  `audioFormat_id` int(10) unsigned default NULL,
  `language_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `movie_audioFormat_language`
--

INSERT INTO `movie_audioFormat_language` VALUES (35,17,1,5);
INSERT INTO `movie_audioFormat_language` VALUES (34,17,6,3);
INSERT INTO `movie_audioFormat_language` VALUES (33,17,3,2);
INSERT INTO `movie_audioFormat_language` VALUES (32,17,3,1);
INSERT INTO `movie_audioFormat_language` VALUES (31,17,4,1);
INSERT INTO `movie_audioFormat_language` VALUES (25,4,1,5);
INSERT INTO `movie_audioFormat_language` VALUES (26,4,3,1);
INSERT INTO `movie_audioFormat_language` VALUES (16,4,4,1);
INSERT INTO `movie_audioFormat_language` VALUES (23,4,3,5);
INSERT INTO `movie_audioFormat_language` VALUES (36,19,5,3);
INSERT INTO `movie_audioFormat_language` VALUES (37,19,3,6);

--
-- Table structure for table `movie_song`
--

CREATE TABLE `movie_song` (
  `id` int(11) NOT NULL auto_increment,
  `movie_id` int(10) unsigned default NULL,
  `song_id` int(10) unsigned default NULL,
  `movie_song__type_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `movie_song`
--

INSERT INTO `movie_song` VALUES (33,18,2,2);
INSERT INTO `movie_song` VALUES (32,17,4,2);
INSERT INTO `movie_song` VALUES (24,4,5,1);
INSERT INTO `movie_song` VALUES (34,16,1,1);
INSERT INTO `movie_song` VALUES (26,4,1,NULL);
INSERT INTO `movie_song` VALUES (31,17,1,1);
INSERT INTO `movie_song` VALUES (35,16,3,2);
INSERT INTO `movie_song` VALUES (36,15,5,2);
INSERT INTO `movie_song` VALUES (37,15,4,2);
INSERT INTO `movie_song` VALUES (38,15,3,2);
INSERT INTO `movie_song` VALUES (39,13,2,1);
INSERT INTO `movie_song` VALUES (40,13,5,1);
INSERT INTO `movie_song` VALUES (41,13,1,2);
INSERT INTO `movie_song` VALUES (42,14,2,2);
INSERT INTO `movie_song` VALUES (43,14,1,NULL);
INSERT INTO `movie_song` VALUES (44,14,3,1);
INSERT INTO `movie_song` VALUES (45,18,5,NULL);
INSERT INTO `movie_song` VALUES (46,18,1,NULL);
INSERT INTO `movie_song` VALUES (47,18,4,NULL);

--
-- Table structure for table `movie_song__type`
--

CREATE TABLE `movie_song__type` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `movie_song__type`
--

INSERT INTO `movie_song__type` VALUES (1,'Inspired By');
INSERT INTO `movie_song__type` VALUES (2,'Soundtrack');

--
-- Table structure for table `song`
--

CREATE TABLE `song` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `song`
--

INSERT INTO `song` VALUES (1,'Forty-Six and Two');
INSERT INTO `song` VALUES (2,'A Change of Seasons');
INSERT INTO `song` VALUES (3,'Watermark');
INSERT INTO `song` VALUES (4,'Gnosiennes');
INSERT INTO `song` VALUES (5,'Absolution');

