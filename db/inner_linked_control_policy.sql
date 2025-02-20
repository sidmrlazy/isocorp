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
-- Table structure for table `inner_linked_control_policy`
--

DROP TABLE IF EXISTS `inner_linked_control_policy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inner_linked_control_policy` (
  `inner_linked_control_policy_id` int(11) NOT NULL AUTO_INCREMENT,
  `main_control_policy_id` int(15) DEFAULT NULL,
  `sub_control_policy_id` int(15) DEFAULT NULL,
  `linked_control_policy_id` int(15) DEFAULT NULL,
  `inner_linked_control_policy_number` varchar(15) DEFAULT NULL,
  `inner_linked_control_policy_heading` varchar(100) DEFAULT NULL,
  `inner_linked_control_policy_det` longtext,
  `inner_linked_control_policy_status` int(15) DEFAULT NULL,
  PRIMARY KEY (`inner_linked_control_policy_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inner_linked_control_policy`
--

LOCK TABLES `inner_linked_control_policy` WRITE;
/*!40000 ALTER TABLE `inner_linked_control_policy` DISABLE KEYS */;
INSERT INTO `inner_linked_control_policy` VALUES (1,2,6,8,'6.1.1','General','<div id=\"6.1.1\" data-bs-parent=\"#nestedAccordionLevel2\">\r\n<div>\r\n<p>When planning for the information security management system, the organization shall consider the issues referred to in 4.1 and the requirements referred to in 4.2 and determine the risks and opportunities that need to be addressed to:</p>\r\n<ol type=\"a\">\r\n<li>ensure the information security management system can achieve its intended outcome(s)</li>\r\n<li>prevent, or reduce, undesired effects;</li>\r\n<li>achieve continual improvement.</li>\r\n</ol>\r\n<p>The organization shall plan:</p>\r\n<ol type=\"a\">\r\n<li>actions to address these risks and opportunities; and</li>\r\n<li>how to\r\n<ol>\r\n<li>integrate and implement the actions into its information security management system processes; and</li>\r\n<li>evaluate the effectiveness of these actions.</li>\r\n</ol>\r\n</li>\r\n</ol>\r\n</div>\r\n</div>',1),(5,2,6,8,'6.1.2','Information security objectives and planning to achieve them','<div id=\"6.2\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>The organization shall establish information security objectives at relevant functions and levels.</p>\r\n<p>The information security objectives shall:</p>\r\n<ol type=\"a\">\r\n<li>be consistent with the information security policy;</li>\r\n<li>be measurable (if practicable);</li>\r\n<li>take into account applicable information security requirements, and results from risk assessment and risk treatment;</li>\r\n<li>be monitored;</li>\r\n<li>be communicated;</li>\r\n<li>be updated as appropriate;</li>\r\n<li>be available as documented information.</li>\r\n</ol>\r\n<p>The organization shall retain documented information on the information security objectives. When planning how to achieve its information security objectives, the organization shall determine:</p>\r\n<ol type=\"a\">\r\n<li>what will be done;</li>\r\n<li>what resources will be required;</li>\r\n<li>who will be responsible;</li>\r\n<li>when it will be completed; and</li>\r\n<li>how the results will be evaluated.</li>\r\n</ol>\r\n</div>\r\n</div>',1),(6,2,6,8,'6.1.3','Planning of changes','<p>When the organization determines the need for changes to the information security management system, the changes shall be carried out in a planned manner.</p>',1),(7,2,7,16,'7.5.1','General','<div id=\"7.5.1\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>The organization&rsquo;s information security management system shall include:</p>\r\n<ol type=\"a\">\r\n<li>documented information required by this document; and</li>\r\n<li>documented information determined by the organization as being necessary for the effectiveness of the information security management system</li>\r\n</ol>\r\n<p>NOTE The extent of documented information for an information security management system can differ from one organization to another due to:</p>\r\n<ol>\r\n<li>the size of organization and its type of activities, processes, products and services;</li>\r\n<li>the complexity of processes and their interactions; and</li>\r\n<li>the competence of persons.</li>\r\n</ol>\r\n</div>\r\n</div>',1),(8,2,7,16,'7.5.2','Creating and Updating','<div id=\"7.5.2\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>When creating and updating documented information the organization shall ensure appropriate:</p>\r\n<ol type=\"a\">\r\n<li>identification and description (e.g. a title, date, author, or reference number);</li>\r\n<li>format (e.g. language, software version, graphics) and media (e.g. paper, electronic); and</li>\r\n<li>review and approval for suitability and adequacy.</li>\r\n</ol>\r\n</div>\r\n</div>',1),(9,2,7,16,'7.5.3','Control of documented information','<div id=\"7.5.3\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>Documented information required by the information security management system and by this document shall be controlled to ensure:</p>\r\n<ol type=\"a\">\r\n<li>it is available and suitable for use, where and when it is needed; and</li>\r\n<li>it is adequately protected (e.g. from loss of confidentiality, improper use, or loss of integrity).</li>\r\n</ol>\r\n<p>For the control of documented information, the organization shall address the following activities, as applicable:</p>\r\n<ol type=\"a\">\r\n<li>distribution, access, retrieval and use;</li>\r\n<li>storage and preservation, including the preservation of legibility;</li>\r\n<li>control of changes (e.g. version control); and</li>\r\n<li>retention and disposition.</li>\r\n</ol>\r\n<p>Documented information of external origin, determined by the organization to be necessary for the planning and operation of the information security management system, shall be identified as appropriate, and controlled.</p>\r\n<p><strong>NOTE: </strong>Access can imply a decision regarding the permission to view the documented information only, or the permission and authority to view and change the documented information, etc.</p>\r\n</div>\r\n</div>',1),(10,2,9,21,'9.2.1','General','<div id=\"9.2.1\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>The organization shall conduct internal audits at planned intervals to provide information on whether the information security management system:</p>\r\n<ol type=\"a\">\r\n<li>conforms to\r\n<ol>\r\n<li>the organization&rsquo;s own requirements for its information security management system;</li>\r\n<li>the requirements of this document;</li>\r\n</ol>\r\n</li>\r\n<li>is effectively implemented and maintained.</li>\r\n</ol>\r\n</div>\r\n</div>',1),(11,2,9,21,'9.2.2','Internal Audit Programme','<div id=\"9.2.2\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>The organization shall plan, establish, implement and maintain an audit programme(s), including the frequency, methods, responsibilities, planning requirements and reporting.</p>\r\n<p>When establishing the internal audit programme(s), the organization shall consider the importance of the processes concerned and the results of previous audits.</p>\r\n<p>The organization shall:</p>\r\n<ol type=\"a\">\r\n<li>define the audit criteria and scope for each audit;</li>\r\n<li>select auditors and conduct audits that ensure objectivity and the impartiality of the audit process;</li>\r\n<li>ensure that the results of the audits are reported to relevant management;</li>\r\n</ol>\r\n<p>Documented information shall be available as evidence of the implementation of the audit programme(s) and the audit results.</p>\r\n</div>\r\n</div>',1),(12,2,9,22,'9.3.1','General','<p>Top management shall review the organization\'s information security management system at planned intervals to ensure its continuing suitability, adequacy and effectiveness.</p>',1),(13,2,9,22,'9.3.2','Management review inputs','<div id=\"9.3.2\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>The management review shall include consideration of:</p>\r\n<ol type=\"a\">\r\n<li>the status of actions from previous management reviews;</li>\r\n<li>changes in external and internal issues that are relevant to the information security management system;</li>\r\n<li>changes in needs and expectations of interested parties that are relevant to the information security management system;</li>\r\n<li>feedback on the information security performance, including trends in:\r\n<ol>\r\n<li>nonconformities and corrective actions;</li>\r\n<li>monitoring and measurement results;</li>\r\n<li>audit results;</li>\r\n<li>fulfilment of information security objectives;</li>\r\n</ol>\r\n</li>\r\n<li>feedback from interested parties;</li>\r\n<li>results of risk assessment and status of risk treatment plan;</li>\r\n<li>opportunities for continual improvement.</li>\r\n</ol>\r\n</div>\r\n</div>',1),(14,2,9,22,'9.3.3','Management review results','<div id=\"9.3.3\" data-bs-parent=\"#nestedAccordion\">\r\n<div>\r\n<p>The results of the management review shall include decisions related to continual improvement opportunities and any needs for changes to the information security management system</p>\r\n<p>Documented information shall be available as evidence of the results of management reviews.</p>\r\n</div>\r\n</div>',1);
/*!40000 ALTER TABLE `inner_linked_control_policy` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-20 13:17:21
