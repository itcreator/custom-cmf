-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: vcmf
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.12.04.1-log

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
-- Dumping data for table `article_category`
--

LOCK TABLES `article_category` WRITE;
/*!40000 ALTER TABLE `article_category` DISABLE KEYS */;
INSERT INTO `article_category` VALUES (1,NULL,'1,','root',1,'root','root','2013-11-05 00:42:08','2013-11-05 00:42:08');
/*!40000 ALTER TABLE `article_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `block_container`
--

LOCK TABLES `block_container` WRITE;
/*!40000 ALTER TABLE `block_container` DISABLE KEYS */;
INSERT INTO `block_container` VALUES (1,'top','2011-11-11 10:10:10','2011-11-11 10:10:10'),(2,'bottom','2011-11-11 10:10:10','2011-11-11 10:10:10'),(3,'after-content','2014-02-06 13:54:46','2014-02-06 13:54:48');
/*!40000 ALTER TABLE `block_container` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `block_block`
--

LOCK TABLES `block_block` WRITE;
/*!40000 ALTER TABLE `block_block` DISABLE KEYS */;
INSERT INTO `block_block` VALUES (1,'auth','Cmf\\User\\Block\\AuthBlock','2013-01-01 11:10:10','2013-01-01 11:10:10'),(2,'menu','Cmf\\Menu\\Block\\MenuBlock','2014-03-18 19:28:13','2014-03-18 19:28:15');
/*!40000 ALTER TABLE `block_block` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Dumping data for table `block_binding`
--

LOCK TABLES `block_binding` WRITE;
/*!40000 ALTER TABLE `block_binding` DISABLE KEYS */;
INSERT INTO `block_binding` VALUES (1,1,1,'Cmf\\User','User','{}','2011-11-11 10:10:10','2011-11-11 10:10:10'),(2,1,1,'Cmf\\Index','Index','{}','2011-11-11 10:00:00','2011-11-11 10:00:00'),(3,1,1,'Cmf\\Article','Article','{}','2011-11-11 10:00:00','2011-11-11 10:00:00'),(4,1,1,'Cmf\\Error','Error404','{}','2011-11-11 10:00:00','2011-11-11 10:00:00'),(5,2,1,'Cmf\\Index','Index','{\"menuName\": \"main\"}','2014-03-18 19:29:17','2014-03-18 19:29:19'),(6,2,1,'Cmf\\User','User','{\"menuName\": \"main\"}','2014-03-21 17:33:54','2014-03-21 17:33:56'),(7,2,1,'Cmf\\Article','Article','{\"menuName\": \"main\"}','2014-03-21 17:34:15','2014-03-21 17:34:20'),(8,2,1,'Cmf\\Article','Category','{\"menuName\": \"main\"}','2014-03-22 14:08:21','2014-03-22 14:08:25'),(9,2,1,'Cmf\\Error','Error404','{\"menuName\": \"main\"}','2014-05-22 02:29:48','2014-05-22 02:29:50');
/*!40000 ALTER TABLE `block_binding` ENABLE KEYS */;
UNLOCK TABLES;


LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,'guest','2013-10-17 22:12:03','2013-10-17 22:12:05'),(2,'user','2013-10-17 22:13:13','2014-03-14 14:20:19'),(3,'admin','2013-10-17 22:13:29','2013-10-17 22:13:32');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Dumping data for table `users`
--

LOCK TABLES `user_user` WRITE;
/*!40000 ALTER TABLE `user_user` DISABLE KEYS */;
INSERT INTO `user_user` VALUES
  (1,null,'guest','guest','guest','guest','guest_at_mail_com','',1,'',1330813046,2,'2013-04-30 10:09:11','0000-00-00 00:00:00'),
  (2,null,'admin','adm','adm','adm','adm_at_mail_com','1',1,'',1330810409,0,'2013-11-03 23:59:08','0000-00-00 00:00:00')
;
/*!40000 ALTER TABLE `user_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `permission_user_2_role`
--

LOCK TABLES `permission_user_2_role` WRITE;
/*!40000 ALTER TABLE `permission_user_2_role` DISABLE KEYS */;
INSERT INTO `permission_user_2_role` VALUES (1,1),(2,2),(3,2);
/*!40000 ALTER TABLE `permission_user_2_role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-24 20:36:10
