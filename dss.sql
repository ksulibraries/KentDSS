-- MySQL dump 10.13  Distrib 5.1.69, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: dss
-- ------------------------------------------------------
-- Server version	5.1.69

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditlog`
--

DROP TABLE IF EXISTS `auditlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auditlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) DEFAULT '',
  `actionByUserId` int(11) DEFAULT '0',
  `actionDate` int(11) DEFAULT '0',
  `actionComment` varchar(255) DEFAULT '',
  `workgroupName` varchar(255) DEFAULT '',
  `projectName` varchar(255) DEFAULT '',
  `retentionGroup` varchar(255) DEFAULT '',
  `filetype` varchar(255) DEFAULT '',
  `filesize` float DEFAULT '0',
  `filename` varchar(255) DEFAULT '',
  `expirationDate` int(11) DEFAULT '0',
  `addedByUserId` int(11) DEFAULT '0',
  `addedDate` int(11) DEFAULT '0',
  `lastUpdatedByUserId` int(11) DEFAULT '0',
  `lastUpdatedDate` int(11) DEFAULT '0',
  `significant` int(1) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` text,
  `creator` varchar(255) DEFAULT '',
  `creationDate` varchar(255) DEFAULT '',
  `location` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `action` (`action`),
  KEY `actionDate` (`actionDate`),
  KEY `workgroupName` (`workgroupName`),
  KEY `projectName` (`projectName`),
  KEY `filename` (`filename`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditlog`
--

LOCK TABLES `auditlog` WRITE;
/*!40000 ALTER TABLE `auditlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `auditlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filetype`
--

DROP TABLE IF EXISTS `filetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extension` char(4) DEFAULT '',
  `mimeType` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `extension` (`extension`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filetype`
--

LOCK TABLES `filetype` WRITE;
/*!40000 ALTER TABLE `filetype` DISABLE KEYS */;
INSERT INTO `filetype` VALUES (1,'AIF','audio/x-aiff'),(2,'AU','audio/basic'),(3,'AVI','video/x-msvideo'),(4,'CRT','application/x-x509-ca-cert'),(5,'CSS','text/css'),(6,'EPS','application/postscript'),(7,'GIF','image/gif'),(8,'HTM','text/html'),(9,'HTML','text/html'),(10,'ICO','image/x-icon'),(11,'J2K','image/jpeg2000'),(12,'JP2','image/jpeg2000'),(13,'JPE','image/jpeg'),(14,'JPEG','image/jpeg'),(15,'JPG','image/jpeg'),(16,'JS','application/x-javascript'),(17,'MID','audio/mid'),(18,'MOV','video/quicktime'),(19,'MP2','video/mpeg'),(20,'MP3','audio/mpeg'),(21,'MPA','video/mpeg'),(22,'MPE','video/mpeg'),(23,'MPEG','video/mpeg'),(24,'MPG','video/mpeg'),(25,'MPV2','video/mpeg'),(26,'PBM','image/x-portable-bitmap'),(27,'PDF','application/pdf'),(28,'PS','application/postscript'),(29,'QT','video/quicktime'),(30,'RA','audio/x-pn-realaudio'),(31,'RAM','audio/x-pn-realaudio'),(32,'RTF','application/rtf'),(33,'RTX','text/richtext'),(34,'SVG','image/svg+xml'),(35,'TIF','image/tiff'),(36,'TIFF','image/tiff'),(37,'TXT','text/plain'),(38,'WAV','audio/x-wav'),(39,'XBM','image/x-xbitmap'),(40,'ZIP','application/zip'),(41,'TAR','application/tar');
/*!40000 ALTER TABLE `filetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) DEFAULT '0',
  `retentionGroup` varchar(255) DEFAULT '',
  `filetype` varchar(255) DEFAULT '',
  `filesize` float DEFAULT '0',
  `filename` varchar(255) DEFAULT '',
  `thumbnailCompleted` int(1) DEFAULT '0',
  `expirationDate` int(11) DEFAULT '0',
  `warnLevel` int(11) DEFAULT '0',
  `checksum` varchar(255) DEFAULT '',
  `checksumChecked` int(11) DEFAULT '0',
  `checksumCorrect` int(1) DEFAULT '0',
  `restorePending` int(1) DEFAULT '0',
  `restoreInProgress` int(1) DEFAULT '0',
  `addedByUserId` int(11) DEFAULT '0',
  `addedDate` int(11) DEFAULT '0',
  `lastUpdatedByUserId` int(11) DEFAULT '0',
  `lastUpdatedDate` int(11) DEFAULT '0',
  `metadataCompleted` int(1) DEFAULT '0',
  `significant` int(1) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` text,
  `creator` varchar(255) DEFAULT '',
  `creationDate` varchar(255) DEFAULT '',
  `location` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `projectId` (`projectId`),
  KEY `filename` (`filename`),
  KEY `title` (`title`),
  KEY `creator` (`creator`),
  KEY `creationDate` (`creationDate`),
  KEY `location` (`location`),
  KEY `warning` (`expirationDate`,`warnLevel`)
) ENGINE=MyISAM AUTO_INCREMENT=262231 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workgroupId` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `createdByUserId` int(11) DEFAULT '0',
  `createdDate` int(11) DEFAULT '0',
  `metadataCompleted` int(11) DEFAULT '0',
  `lastUpdatedByUserId` int(11) DEFAULT '0',
  `lastUpdatedDate` int(11) DEFAULT '0',
  `testmode` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `departmentId` (`workgroupId`)
) ENGINE=MyISAM AUTO_INCREMENT=3928 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retention`
--

DROP TABLE IF EXISTS `retention`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retentionGroup` varchar(255) DEFAULT '',
  `retentionPeriod` int(11) DEFAULT '1',
  `testmode` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `retentionGroup` (`retentionGroup`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retention`
--

LOCK TABLES `retention` WRITE;
/*!40000 ALTER TABLE `retention` DISABLE KEYS */;
INSERT INTO `retention` VALUES (60,'-- Other --',999,0);
/*!40000 ALTER TABLE `retention` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` varchar(255) DEFAULT '',
  `ipAddress` varchar(255) DEFAULT '',
  `timeout` int(11) DEFAULT '0',
  `userId` int(11) DEFAULT '0',
  `accessLevel` int(11) DEFAULT '0',
  `numWorkgroups` int(11) DEFAULT '0',
  `currentWorkgroup` int(11) DEFAULT '0',
  `currentProject` int(11) DEFAULT '0',
  `displayListSize` int(11) DEFAULT '10',
  KEY `id` (`id`),
  KEY `timeout` (`timeout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `superstore`
--

DROP TABLE IF EXISTS `superstore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `superstore` (
  `script` varchar(255) NOT NULL,
  `sessionId` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `timeout` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `superstore`
--

LOCK TABLES `superstore` WRITE;
/*!40000 ALTER TABLE `superstore` DISABLE KEYS */;
/*!40000 ALTER TABLE `superstore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) DEFAULT '1',
  `accessLevel` int(11) DEFAULT '0',
  `firstName` varchar(255) DEFAULT '',
  `lastName` varchar(255) DEFAULT '',
  `username` varchar(255) DEFAULT '',
  `password` varchar(255) DEFAULT '',
  `emailAddress` varchar(255) DEFAULT '',
  `displayListSize` int(11) DEFAULT '10',
  `createdByUserId` int(11) DEFAULT '0',
  `createdDate` int(11) DEFAULT '0',
  `lastUpdatedByUserId` int(11) DEFAULT '0',
  `lastUpdatedDate` int(11) DEFAULT '0',
  `lastLoginDate` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,2,'DSS','Admin','dssadmin','944cd53d4f276cf661eb83e0b02d0807','dssadmin',10,0,0,1,1362422296,1377625100);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workgroup`
--

DROP TABLE IF EXISTS `workgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `createdByUserId` int(11) DEFAULT '0',
  `createdDate` int(11) DEFAULT '0',
  `lastUpdatedByUserId` int(11) DEFAULT '0',
  `lastUpdatedDate` int(11) DEFAULT '0',
  `testmode` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workgroup`
--

LOCK TABLES `workgroup` WRITE;
/*!40000 ALTER TABLE `workgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `workgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workgroupUser`
--

DROP TABLE IF EXISTS `workgroupUser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workgroupUser` (
  `workgroupId` int(11) DEFAULT '0',
  `userId` int(11) DEFAULT '0',
  KEY `workgroupId` (`workgroupId`),
  KEY `userId` (`userId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workgroupUser`
--

LOCK TABLES `workgroupUser` WRITE;
/*!40000 ALTER TABLE `workgroupUser` DISABLE KEYS */;
/*!40000 ALTER TABLE `workgroupUser` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-27 15:05:26
