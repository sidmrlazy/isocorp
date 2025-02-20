-- MySQL dump 10.13  Distrib 5.7.14, for Win64 (x86_64)
--
-- Host: localhost    Database: isms
-- ------------------------------------------------------
-- Server version	5.7.14

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
-- Table structure for table `sim`
--

DROP TABLE IF EXISTS `sim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sim` (
  `sim_id` int(11) NOT NULL AUTO_INCREMENT,
  `sim_topic` varchar(255) DEFAULT NULL,
  `sim_details` blob,
  `sim_status` varchar(50) DEFAULT NULL,
  `sim_severity` varchar(50) DEFAULT NULL,
  `sim_source` varchar(50) DEFAULT NULL,
  `sim_type` varchar(50) DEFAULT NULL,
  `sim_final` varchar(15) DEFAULT NULL,
  `sim_reported_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `sim_reported_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sim_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sim`
--

LOCK TABLES `sim` WRITE;
/*!40000 ALTER TABLE `sim` DISABLE KEYS */;
INSERT INTO `sim` VALUES (1,'Security Incident Number 1 on 19 Feb 2024','                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','2','1','2','1','2','2025-02-19 15:01:20','Siddharth Asthana'),(2,'Security Incident Number 2 on 19 Feb 2024','<h3><strong>Lorem Ipsum Sample (2 Paragraphs):</strong></h3>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam dignissim, risus sit amet suscipit scelerisque, felis justo cursus metus, nec cursus nisi libero non lacus. Vestibulum eget felis sed ligula tempor tincidunt. Aliquam erat volutpat. Proin interdum neque nec tortor varius, id bibendum purus condimentum. Vivamus malesuada, libero at tincidunt feugiat, ex eros vehicula nisl, ut consectetur mi risus id turpis. Donec nec dapibus ligula, nec malesuada metus. Duis fringilla elit sed eros hendrerit, vel volutpat orci tempus.</p>\r\n<p>Suspendisse potenti. Phasellus varius, nulla a efficitur <span style=\"background-color: rgb(255, 255, 0);\">gravida, ex metus varius purus, a interdum enim felis vel sem. Aenean sed faucibus enim. Integer fringilla orci in nunc molestie, id bibendum lorem condimentum. Nulla facilisi. Sed in dui sed ligula molestie laoreet at id mi. Curabitur tempus ligula in ex faucibus, a fermentum metus vestibulum. Nam vitae suscipit lacus.</span></p><p><br></p>','2','1','2','1','2','2025-02-19 15:02:07','Siddharth Asthana'),(3,'Security Incident 3','<p>Test</p>','2','2','3','2','2','2025-02-19 15:29:21','Siddharth Asthana'),(4,'Security Incident 4',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:32:52',NULL),(5,'Security Incident 5',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:32:57',NULL),(6,'Security Incident 6',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:33:01',NULL),(7,'Security Incident 7',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:33:04',NULL),(8,'Security Incident 8',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:33:07',NULL),(9,'Security Incident 9',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:33:11',NULL),(10,'Security Incident 10',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:33:14',NULL),(11,'Security Incident 11',NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-19 15:33:49',NULL);
/*!40000 ALTER TABLE `sim` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-20 13:19:16
