-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 08:35 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isocorp`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activity_id` int(11) NOT NULL,
  `activity_done_on` varchar(100) DEFAULT NULL,
  `activity_done_on_id` varchar(50) NOT NULL,
  `activity_name` varchar(100) DEFAULT NULL,
  `activity_by` varchar(100) DEFAULT NULL,
  `activity_date` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activity_id`, `activity_done_on`, `activity_done_on_id`, `activity_name`, `activity_by`, `activity_date`) VALUES
(1, 'linked_control_policy', '1', 'Added details to policy', 'Siddharth Asthana', '04-14-2025 16:58:12'),
(2, 'linked_control_policy', '5', 'Added details to policy', 'Siddharth Asthana', '04-14-2025 16:58:57'),
(3, 'Policy Details', '5', 'Assigned policy to and changed status', 'Siddharth Asthana', '04-14-2025 16:59:14'),
(4, 'Policy Details', '1', 'Assigned policy to and changed status', 'Siddharth Asthana', '04-14-2025 17:10:57'),
(5, 'linked_control_policy', '1', 'Added details to policy', 'Siddharth Asthana', '04-23-2025 14:19:12'),
(6, 'Policy Details', '1', 'Assigned policy to and changed status', 'Siddharth Asthana', '04-23-2025 14:20:10'),
(7, 'linked_control_policy', '1', 'Added details to policy', 'Siddharth Asthana', '04-23-2025 14:29:00'),
(8, 'linked_control_policy', '1', 'Added details to policy', 'Unknown', '04-23-2025 14:34:14'),
(9, 'linked_control_policy', '1', 'Added details to policy', 'Unknown', '04-23-2025 14:34:47'),
(10, 'linked_control_policy', '1', 'Added details to policy', 'Siddharth Asthana', '04-23-2025 14:37:39'),
(11, 'linked_control_policy', '1', 'Added details to policy', 'Siddharth Asthana', '04-23-2025 14:48:58'),
(12, 'inner_linked_control_policy', '1', 'Document added', 'Siddharth Asthana', '04-23-2025 15:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

CREATE TABLE `asset` (
  `asset_id` int(11) NOT NULL,
  `asset_name` varchar(255) DEFAULT NULL,
  `asset_note` blob,
  `asset_status` varchar(100) DEFAULT NULL,
  `asset_value` varchar(100) DEFAULT NULL,
  `asset_type` varchar(100) DEFAULT NULL,
  `asset_classification` varchar(100) DEFAULT NULL,
  `asset_location` varchar(100) DEFAULT NULL,
  `asset_owner_legal` varchar(100) DEFAULT NULL,
  `asset_owner` varchar(100) DEFAULT NULL,
  `asset_details_status` varchar(11) DEFAULT NULL,
  `asset_form_status` varchar(11) DEFAULT NULL,
  `asset_assigned_to` varchar(100) DEFAULT NULL,
  `asset_created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `asset_created_by` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `asset`
--

INSERT INTO `asset` (`asset_id`, `asset_name`, `asset_note`, `asset_status`, `asset_value`, `asset_type`, `asset_classification`, `asset_location`, `asset_owner_legal`, `asset_owner`, `asset_details_status`, `asset_form_status`, `asset_assigned_to`, `asset_created_date`, `asset_created_by`) VALUES
(2, 'Hello', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-24 19:08:14', 'Siddharth Asthana');

-- --------------------------------------------------------

--
-- Table structure for table `asset_comment`
--

CREATE TABLE `asset_comment` (
  `asset_comment_id` int(11) NOT NULL,
  `asset_comment_parent_id` varchar(100) DEFAULT NULL,
  `asset_comment_data` blob,
  `asset_comment_by` varchar(100) DEFAULT NULL,
  `asset_comment_date` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_program`
--

CREATE TABLE `audit_program` (
  `ap_id` int(11) NOT NULL,
  `ap_name` varchar(100) DEFAULT NULL,
  `ap_act_name` varchar(100) DEFAULT NULL,
  `ap_details` varchar(100) DEFAULT NULL,
  `ap_blob` blob,
  `ap_assigned_to` varchar(100) DEFAULT NULL,
  `ap_status` varchar(100) DEFAULT NULL,
  `ap_due_date` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `controls`
--

CREATE TABLE `controls` (
  `control_id` int(11) NOT NULL,
  `control_name` varchar(255) DEFAULT NULL,
  `control_linked_1` varchar(100) DEFAULT NULL,
  `control_linked_2` varchar(100) DEFAULT NULL,
  `control_linked_3` varchar(100) DEFAULT NULL,
  `control_details` blob,
  `control_support` blob,
  `control_doc` varchar(100) DEFAULT NULL,
  `control_doc_version` varchar(100) DEFAULT NULL,
  `control_doc_path` varchar(100) DEFAULT NULL,
  `control_assigned_to` varchar(100) DEFAULT NULL,
  `control_due_date` varchar(100) DEFAULT NULL,
  `control_update_date` varchar(100) NOT NULL,
  `control_added_by` varchar(100) NOT NULL,
  `control_status` varchar(100) DEFAULT NULL,
  `control_added_date` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `controls`
--

INSERT INTO `controls` (`control_id`, `control_name`, `control_linked_1`, `control_linked_2`, `control_linked_3`, `control_details`, `control_support`, `control_doc`, `control_doc_version`, `control_doc_path`, `control_assigned_to`, `control_due_date`, `control_update_date`, `control_added_by`, `control_status`, `control_added_date`) VALUES
(1, 'Test Policy Name', 'Linked 1', 'Test 2', 'Test 3', 0x3c703e4c6f72656d2c20697073756d20646f6c6f722073697420616d657420636f6e7365637465747572206164697069736963696e6720656c69742e2045786365707475726920726570656c6c6174206c617564616e7469756d20706572666572656e64697320756c6c616d206163637573616e7469756d207175616d20766f6c757074617465732c2062656174616520667567612074656d706f72612063757069646974617465207574206d696e757320766f6c7570746174652071756973206e65736369756e7420636f6e736563746574757220766f6c7570746174656d206d6f6c6573746961732074656d706f7269627573206e617475732e3c2f703e, 0x3c703e5369642048656c6c6f20576f726c643c2f703e, NULL, NULL, NULL, NULL, NULL, '', 'Siddharth Asthana', NULL, '04-23-2025 19:43:05'),
(2, 'Test 2', 'Hello', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '04-23-2025 20:44:32'),
(3, 'Test 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, '04-24-2025 15:20:26');

-- --------------------------------------------------------

--
-- Table structure for table `control_history`
--

CREATE TABLE `control_history` (
  `ctrl_h_id` int(11) NOT NULL,
  `ctrl_h_pol_id` varchar(100) DEFAULT NULL,
  `ctrl_h_pol_old_det` blob,
  `ctrl_h_updated_by` varchar(100) DEFAULT NULL,
  `ctrl_h_assigned_to_old` varchar(100) DEFAULT NULL,
  `ctrl_h_due_date_old` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inner_linked_control_policy`
--

CREATE TABLE `inner_linked_control_policy` (
  `inner_linked_control_policy_id` int(11) NOT NULL,
  `main_control_policy_id` int(15) DEFAULT NULL,
  `sub_control_policy_id` int(15) DEFAULT NULL,
  `linked_control_policy_id` int(15) DEFAULT NULL,
  `inner_linked_control_policy_number` varchar(15) DEFAULT NULL,
  `inner_linked_control_policy_heading` varchar(100) DEFAULT NULL,
  `inner_linked_control_policy_det` longtext,
  `inner_linked_control_policy_status` int(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inner_linked_control_policy`
--

INSERT INTO `inner_linked_control_policy` (`inner_linked_control_policy_id`, `main_control_policy_id`, `sub_control_policy_id`, `linked_control_policy_id`, `inner_linked_control_policy_number`, `inner_linked_control_policy_heading`, `inner_linked_control_policy_det`, `inner_linked_control_policy_status`) VALUES
(1, 2, 6, 8, '6.1.1', 'General', '<div id="6.1.1" data-bs-parent="#nestedAccordionLevel2">\r\n<div>\r\n<p>When planning for the information security management system, the organization shall consider the issues referred to in 4.1 and the requirements referred to in 4.2 and determine the risks and opportunities that need to be addressed to:</p>\r\n<ol type="a">\r\n<li>ensure the information security management system can achieve its intended outcome(s)</li>\r\n<li>prevent, or reduce, undesired effects;</li>\r\n<li>achieve continual improvement.</li>\r\n</ol>\r\n<p>The organization shall plan:</p>\r\n<ol type="a">\r\n<li>actions to address these risks and opportunities; and</li>\r\n<li>how to\r\n<ol>\r\n<li>integrate and implement the actions into its information security management system processes; and</li>\r\n<li>evaluate the effectiveness of these actions.</li>\r\n</ol>\r\n</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(5, 2, 6, 8, '6.1.2', 'Information security objectives and planning to achieve them', '<div id="6.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall establish information security objectives at relevant functions and levels.</p>\r\n<p>The information security objectives shall:</p>\r\n<ol type="a">\r\n<li>be consistent with the information security policy;</li>\r\n<li>be measurable (if practicable);</li>\r\n<li>take into account applicable information security requirements, and results from risk assessment and risk treatment;</li>\r\n<li>be monitored;</li>\r\n<li>be communicated;</li>\r\n<li>be updated as appropriate;</li>\r\n<li>be available as documented information.</li>\r\n</ol>\r\n<p>The organization shall retain documented information on the information security objectives. When planning how to achieve its information security objectives, the organization shall determine:</p>\r\n<ol type="a">\r\n<li>what will be done;</li>\r\n<li>what resources will be required;</li>\r\n<li>who will be responsible;</li>\r\n<li>when it will be completed; and</li>\r\n<li>how the results will be evaluated.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(6, 2, 6, 8, '6.1.3', 'Planning of changes', '<p>When the organization determines the need for changes to the information security management system, the changes shall be carried out in a planned manner.</p>', 1),
(7, 2, 7, 16, '7.5.1', 'General', '<div id="7.5.1" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization&rsquo;s information security management system shall include:</p>\r\n<ol type="a">\r\n<li>documented information required by this document; and</li>\r\n<li>documented information determined by the organization as being necessary for the effectiveness of the information security management system</li>\r\n</ol>\r\n<p>NOTE The extent of documented information for an information security management system can differ from one organization to another due to:</p>\r\n<ol>\r\n<li>the size of organization and its type of activities, processes, products and services;</li>\r\n<li>the complexity of processes and their interactions; and</li>\r\n<li>the competence of persons.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(8, 2, 7, 16, '7.5.2', 'Creating and Updating', '<div id="7.5.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>When creating and updating documented information the organization shall ensure appropriate:</p>\r\n<ol type="a">\r\n<li>identification and description (e.g. a title, date, author, or reference number);</li>\r\n<li>format (e.g. language, software version, graphics) and media (e.g. paper, electronic); and</li>\r\n<li>review and approval for suitability and adequacy.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(9, 2, 7, 16, '7.5.3', 'Control of documented information', '<div id="7.5.3" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>Documented information required by the information security management system and by this document shall be controlled to ensure:</p>\r\n<ol type="a">\r\n<li>it is available and suitable for use, where and when it is needed; and</li>\r\n<li>it is adequately protected (e.g. from loss of confidentiality, improper use, or loss of integrity).</li>\r\n</ol>\r\n<p>For the control of documented information, the organization shall address the following activities, as applicable:</p>\r\n<ol type="a">\r\n<li>distribution, access, retrieval and use;</li>\r\n<li>storage and preservation, including the preservation of legibility;</li>\r\n<li>control of changes (e.g. version control); and</li>\r\n<li>retention and disposition.</li>\r\n</ol>\r\n<p>Documented information of external origin, determined by the organization to be necessary for the planning and operation of the information security management system, shall be identified as appropriate, and controlled.</p>\r\n<p><strong>NOTE: </strong>Access can imply a decision regarding the permission to view the documented information only, or the permission and authority to view and change the documented information, etc.</p>\r\n</div>\r\n</div>', 1),
(10, 2, 9, 21, '9.2.1', 'General', '<div id="9.2.1" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall conduct internal audits at planned intervals to provide information on whether the information security management system:</p>\r\n<ol type="a">\r\n<li>conforms to\r\n<ol>\r\n<li>the organization&rsquo;s own requirements for its information security management system;</li>\r\n<li>the requirements of this document;</li>\r\n</ol>\r\n</li>\r\n<li>is effectively implemented and maintained.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(11, 2, 9, 21, '9.2.2', 'Internal Audit Programme', '<div id="9.2.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall plan, establish, implement and maintain an audit programme(s), including the frequency, methods, responsibilities, planning requirements and reporting.</p>\r\n<p>When establishing the internal audit programme(s), the organization shall consider the importance of the processes concerned and the results of previous audits.</p>\r\n<p>The organization shall:</p>\r\n<ol type="a">\r\n<li>define the audit criteria and scope for each audit;</li>\r\n<li>select auditors and conduct audits that ensure objectivity and the impartiality of the audit process;</li>\r\n<li>ensure that the results of the audits are reported to relevant management;</li>\r\n</ol>\r\n<p>Documented information shall be available as evidence of the implementation of the audit programme(s) and the audit results.</p>\r\n</div>\r\n</div>', 1),
(12, 2, 9, 22, '9.3.1', 'General', '<p>Top management shall review the organization\'s information security management system at planned intervals to ensure its continuing suitability, adequacy and effectiveness.</p>', 1),
(13, 2, 9, 22, '9.3.2', 'Management review inputs', '<div id="9.3.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The management review shall include consideration of:</p>\r\n<ol type="a">\r\n<li>the status of actions from previous management reviews;</li>\r\n<li>changes in external and internal issues that are relevant to the information security management system;</li>\r\n<li>changes in needs and expectations of interested parties that are relevant to the information security management system;</li>\r\n<li>feedback on the information security performance, including trends in:\r\n<ol>\r\n<li>nonconformities and corrective actions;</li>\r\n<li>monitoring and measurement results;</li>\r\n<li>audit results;</li>\r\n<li>fulfilment of information security objectives;</li>\r\n</ol>\r\n</li>\r\n<li>feedback from interested parties;</li>\r\n<li>results of risk assessment and status of risk treatment plan;</li>\r\n<li>opportunities for continual improvement.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(14, 2, 9, 22, '9.3.3', 'Management review results', '<div id="9.3.3" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The results of the management review shall include decisions related to continual improvement opportunities and any needs for changes to the information security management system</p>\r\n<p>Documented information shall be available as evidence of the results of management reviews.</p>\r\n</div>\r\n</div>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `linked_control_policy`
--

CREATE TABLE `linked_control_policy` (
  `linked_control_policy_id` int(11) NOT NULL,
  `main_control_policy_id` int(15) NOT NULL,
  `sub_control_policy_id` int(15) NOT NULL,
  `linked_control_policy_number` float NOT NULL,
  `linked_control_policy_heading` varchar(100) NOT NULL,
  `linked_control_policy_det` longtext,
  `linked_control_policy_status` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `linked_control_policy`
--

INSERT INTO `linked_control_policy` (`linked_control_policy_id`, `main_control_policy_id`, `sub_control_policy_id`, `linked_control_policy_number`, `linked_control_policy_heading`, `linked_control_policy_det`, `linked_control_policy_status`) VALUES
(1, 2, 4, 4.1, 'Understanding the organization and its context', '<p>The organization shall determine external and internal issues that are relevant to its purpose and that affect its ability to achieve the intended outcome(s) of its information security management system.</p>\r\n<p><strong>NOTE:</strong> Determining these issues refers to establishing the external and internal context of the organization considered in Clause 5.4.1 of ISO 31000:2018[5].</p>', 1),
(2, 2, 4, 4.2, 'Understanding the needs and expectations of interested parties', '<p><span style="font-size: 14pt;">The organization shall determine:</span></p>\r\n<ul>\r\n<li>interested parties that are relevant to the information security management system</li>\r\n<li>the relevant requirements of these interested parties;</li>\r\n<li>which of these requirements will be addressed through the information security management system.</li>\r\n</ul>\r\n<p><strong>NOTE:</strong> The requirements of interested parties can include legal and regulatory requirements and contractual obligations.</p>', 1),
(3, 2, 4, 4.3, 'Determining the scope of the information security management system', '<div id="4.3" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall determine the boundaries and applicability of the information security management system to establish its scope.</p>\r\n<p>When determining this scope, the organization shall consider:</p>\r\n<ol type="a">\r\n<li>the external and internal issues referred to in 4.1;</li>\r\n<li>the requirements referred to in 4.2;</li>\r\n<li>interfaces and dependencies between activities performed by the organization, and those that are performed by other organizations.</li>\r\n</ol>\r\n<p>The scope shall be available as documented information.</p>\r\n</div>\r\n</div>', 1),
(4, 2, 4, 4.4, 'Information security management system', '<p>The organization shall establish, implement, maintain and continually improve an information security management system, including the processes needed and their interactions, in accordance with the requirements of this document.</p>', 1),
(5, 2, 5, 5.1, 'Leadership and commitment', '<p>Top management shall demonstrate leadership and commitment with respect to the information security management system by:</p>\n<ol type="a">\n<li>ensuring the information security policy and the information security objectives are established and are compatible with the strategic direction of the organization;</li>\n<li>ensuring the integration of the information security management system requirements into the organization&rsquo;s processes;</li>\n<li>ensuring that the resources needed for the information security management system are available;</li>\n<li>communicating the importance of effective information security management and of conforming to the information security management system requirements;</li>\n<li>ensuring that the information security management system achieves its intended outcome(s)</li>\n<li>directing and supporting persons to contribute to the effectiveness of the information security management system;</li>\n<li>promoting continual improvement; and</li>\n<li>supporting other relevant management roles to demonstrate their leadership as it applies to their areas of responsibility.</li>\n</ol>\n<p><strong>NOTE:</strong> Reference to &ldquo;business&rdquo; in this document can be interpreted broadly to mean those activities that are core to the purposes of the organization&rsquo;s existence.</p>', 1),
(6, 2, 5, 5.2, 'Policy', '<div id="5.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>Top management shall establish an information security policy that:</p>\r\n<ol type="a">\r\n<li>is appropriate to the purpose of the organization;</li>\r\n<li>includes information security objectives (see 6.2) or provides the framework for setting information security objectives;</li>\r\n<li>includes a commitment to satisfy applicable requirements related to information security;</li>\r\n<li>includes a commitment to continual improvement of the information security management system.</li>\r\n</ol>\r\n<p>The information security policy shall:</p>\r\n<ol type="a">\r\n<li>be available as documented information;</li>\r\n<li>be communicated within the organization;</li>\r\n<li>be available to interested parties, as appropriate.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(7, 2, 5, 5.3, 'Organizational roles, responsibilities and authorities', '<div id="5.3" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>Top management shall ensure that the responsibilities and authorities for roles relevant to information security are assigned and communicated within the organization.</p>\r\n<p>Top management shall assign the responsibility and authority for:</p>\r\n<ol type="a">\r\n<li>ensuring that the information security management system conforms to the requirements of this document;</li>\r\n<li>reporting on the performance of the information security management system to top management.</li>\r\n</ol>\r\n<p><strong>NOTE:</strong> Top management can also assign responsibilities and authorities for reporting performance of the information security management system within the organization.</p>\r\n</div>\r\n</div>', 1),
(8, 2, 6, 6.1, 'Actions to address risks and opportunities', '', 1),
(10, 2, 6, 6.2, 'Information security objectives and planning to achieve them', '<div id="6.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall establish information security objectives at relevant functions and levels.</p>\r\n<p>The information security objectives shall:</p>\r\n<ol type="a">\r\n<li>be consistent with the information security policy;</li>\r\n<li>be measurable (if practicable);</li>\r\n<li>take into account applicable information security requirements, and results from risk assessment and risk treatment;</li>\r\n<li>be monitored;</li>\r\n<li>be communicated;</li>\r\n<li>be updated as appropriate;</li>\r\n<li>be available as documented information.</li>\r\n</ol>\r\n<p>The organization shall retain documented information on the information security objectives. When planning how to achieve its information security objectives, the organization shall determine:</p>\r\n<ol type="a">\r\n<li>what will be done;</li>\r\n<li>what resources will be required;</li>\r\n<li>who will be responsible;</li>\r\n<li>when it will be completed; and</li>\r\n<li>how the results will be evaluated.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(11, 2, 6, 6.3, 'Planning of changes', '<p>When the organization determines the need for changes to the information security management system, the changes shall be carried out in a planned manner.</p>', 1),
(12, 2, 7, 7.1, 'Resources', '<p>The organization shall determine and provide the resources needed for the establishment, implementation, maintenance and continual improvement of the information security management system.</p>', 1),
(13, 2, 7, 7.2, 'Competence', '<div id="7.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall:</p>\r\n<ol type="a">\r\n<li>determine the necessary competence of person(s) doing work under its control that affects its information security performance;</li>\r\n<li>ensure that these persons are competent on the basis of appropriate education, training, or experience;</li>\r\n<li>where applicable, take actions to acquire the necessary competence, and evaluate the effectiveness of the actions taken; and</li>\r\n<li>retain appropriate documented information as evidence of competence.</li>\r\n</ol>\r\n<p><strong>NOTE: </strong> Applicable actions can include, for example: the provision of training to, the mentoring of, or the re- assignment of current employees; or the hiring or contracting of competent persons.</p>\r\n</div>\r\n</div>', 1),
(14, 2, 7, 7.3, 'Awareness', '<div id="7.3" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>Persons doing work under the organization&rsquo;s control shall be aware of:</p>\r\n<ol type="a">\r\n<li>the information security policy;</li>\r\n<li>their contribution to the effectiveness of the information security management system, including the benefits of improved information security performance; and</li>\r\n<li>the implications of not conforming with the information security management system requirements.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(15, 2, 7, 7.4, 'Communication', '<div id="7.4" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall determine the need for internal and external communications relevant to the information security management system including:</p>\r\n<ol type="a">\r\n<li>on what to communicate;</li>\r\n<li>when to communicate;</li>\r\n<li>with whom to communicate;</li>\r\n<li>how to communicate.</li>\r\n</ol>\r\n</div>\r\n</div>', 1),
(16, 2, 7, 7.5, 'Documented Information', '', 1),
(17, 2, 8, 8.1, 'Operational planning and control', '<div id="8.1" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall plan, implement and control the processes needed to meet requirements, and to implement the actions determined in Clause 6, by:</p>\r\n<ul>\r\n<li>establishing criteria for the processes;</li>\r\n<li>implementing control of the processes in accordance with the criteria.</li>\r\n</ul>\r\n<p>Documented information shall be available to the extent necessary to have confidence that the processes have been carried out as planned.</p>\r\n<p>The organization shall control planned changes and review the consequences of unintended changes, taking action to mitigate any adverse effects, as necessary.</p>\r\n<p>The organization shall ensure that externally provided processes, products or services that are relevant to the information security management system are controlled.</p>\r\n</div>\r\n</div>', 1),
(18, 2, 8, 8.2, 'Information security risk assessment', '<div id="8.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall perform information security risk assessments at planned intervals or when significant changes are proposed or occur, taking account of the criteria established in 6.1.2 a).</p>\r\n<p>The organization shall retain documented information of the results of the information security risk assessments.</p>\r\n</div>\r\n</div>', 1),
(19, 2, 8, 8.3, 'Information security risk treatment', '<div id="8.3" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall implement the information security risk treatment plan.</p>\r\n<p>The organization shall retain documented information of the results of the information security risk treatment.</p>\r\n</div>\r\n</div>', 1),
(20, 2, 9, 9.1, 'Monitoring, measurement, analysis and evaluation', '<div id="9.1" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>The organization shall determine:</p>\r\n<ol type="a">\r\n<li>what needs to be monitored and measured, including information security processes and controls;</li>\r\n<li>the methods for monitoring, measurement, analysis and evaluation, as applicable, to ensure valid results. The methods selected should produce comparable and reproducible results to be considered valid;</li>\r\n<li>when the monitoring and measuring shall be performed;</li>\r\n<li>who shall monitor and measure;</li>\r\n<li>when the results from monitoring and measurement shall be analysed and evaluated;</li>\r\n<li>who shall analyse and evaluate these results.</li>\r\n</ol>\r\n<p>Documented information shall be available as evidence of the results.</p>\r\n<p>The organization shall evaluate the information security performance and the effectiveness of the information security management system.</p>\r\n</div>\r\n</div>', 1),
(21, 2, 9, 9.2, 'Internal Audit', '', 1),
(22, 2, 9, 9.3, 'Management Review', '', 1),
(23, 2, 10, 10.1, 'Continual Improvement', '<p>The organization shall continually improve the suitability, adequacy and effectiveness of the information security management system.</p>', 1),
(24, 2, 10, 10.2, 'Non-Conformity and corrective actions', '<div id="10.2" data-bs-parent="#nestedAccordion">\r\n<div>\r\n<p>When a nonconformity occurs, the organization shall:</p>\r\n<ol type="a">\r\n<li>react to the nonconformity, and as applicable:\r\n<ol>\r\n<li>take action to control and correct it;</li>\r\n<li>deal with the consequences;</li>\r\n</ol>\r\n</li>\r\n<li>evaluate the need for action to eliminate the causes of nonconformity, in order that it does not recur or occur elsewhere, by:\r\n<ol>\r\n<li>reviewing the nonconformity;</li>\r\n<li>determining the causes of the nonconformity; and</li>\r\n<li>determining if similar nonconformities exist, or could potentially occur;</li>\r\n</ol>\r\n</li>\r\n<li>implement any action needed;</li>\r\n<li>review the effectiveness of any corrective action taken; and</li>\r\n<li>make changes to the information security management system, if necessary.</li>\r\n</ol>\r\n<p>Corrective actions shall be appropriate to the effects of the nonconformities encountered.</p>\r\n<p>Documented information shall be available as evidence of:</p>\r\n<ol type="a">\r\n<li>the nature of the nonconformities and any subsequent actions taken,</li>\r\n<li>the results of any corrective action.</li>\r\n</ol>\r\n</div>\r\n</div>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mrb`
--

CREATE TABLE `mrb` (
  `mrb_id` int(11) NOT NULL,
  `mrb_topic` varchar(255) DEFAULT NULL,
  `mrb_details` blob,
  `mrb_status` varchar(15) DEFAULT NULL,
  `mrb_added_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mrb_added_by` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mrb`
--

INSERT INTO `mrb` (`mrb_id`, `mrb_topic`, `mrb_details`, `mrb_status`, `mrb_added_on`, `mrb_added_by`) VALUES
(1, 'Information Security Meetings', NULL, '1', '2025-02-20 10:03:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mrb_activities`
--

CREATE TABLE `mrb_activities` (
  `mrb_act_id` int(11) NOT NULL,
  `mrb_act_deliverable_id` int(11) DEFAULT NULL,
  `mrb_act_activity` varchar(255) DEFAULT NULL,
  `mrb_act_details` blob,
  `mrb_act_status` varchar(15) DEFAULT NULL,
  `mrb_act_added_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mrb_act_added_by` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mrb_activities`
--

INSERT INTO `mrb_activities` (`mrb_act_id`, `mrb_act_deliverable_id`, `mrb_act_activity`, `mrb_act_details`, `mrb_act_status`, `mrb_act_added_on`, `mrb_act_added_by`) VALUES
(1, 1, 'Implementation review meeting 1', 0x3c703e5465737420646174613c2f703e, '2', '2025-02-20 10:04:05', NULL),
(2, 1, 'Implementation review meeting 2', 0x3c703e48656c6c6f20576f726c643c2f703e, '2', '2025-02-20 10:04:10', NULL),
(3, 1, 'Implementation review meeting 3', 0x3c703e5468697264204d656574696e672048656c643c2f703e, '2', '2025-02-20 10:04:15', NULL),
(4, 1, 'Implementation review meeting 4', NULL, '1', '2025-02-20 10:04:24', NULL),
(5, 2, 'Document the formal launch of your ISMS here', NULL, '1', '2025-02-20 10:04:54', NULL),
(6, 2, 'Internal Audit Part 1', NULL, '1', '2025-02-20 10:05:02', NULL),
(7, 2, 'Internal Audit Part 2', NULL, '1', '2025-02-20 10:05:09', NULL),
(8, 3, 'Meeting 1', NULL, '1', '2025-02-20 10:05:49', NULL),
(9, 3, 'Meeting 2', NULL, '1', '2025-02-20 10:05:59', NULL),
(10, 3, 'Meeting 3', NULL, '1', '2025-02-20 10:06:04', NULL),
(11, 3, 'Meeting 4', NULL, '1', '2025-02-20 10:06:12', NULL),
(12, 3, 'Meeting 5', NULL, '1', '2025-02-20 10:06:18', NULL),
(13, 3, 'Meeting 6', NULL, '1', '2025-02-20 10:06:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mrb_deliverables`
--

CREATE TABLE `mrb_deliverables` (
  `mrb_del_id` int(11) NOT NULL,
  `mrb_del_board_id` int(11) DEFAULT NULL,
  `mrb_del_deliverable` varchar(255) DEFAULT NULL,
  `mrb_del_details` blob,
  `mrb_del_status` varchar(15) DEFAULT NULL,
  `mrb_del_added_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mrb_del_added_by` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `policy`
--

CREATE TABLE `policy` (
  `policy_id` int(11) NOT NULL,
  `policy_clause` varchar(15) DEFAULT NULL,
  `policy_name` varchar(100) DEFAULT NULL,
  `policy_det` longtext,
  `policy_status` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `policy`
--

INSERT INTO `policy` (`policy_id`, `policy_clause`, `policy_name`, `policy_det`, `policy_status`) VALUES
(1, '0', 'Background Information', '', '1'),
(2, '0.1', 'ISO 27001 Requirements - 4.1 to 10.2', '', '1'),
(3, 'A.5. ', 'Organizational Controls', '', '1'),
(4, 'A.6', 'People Controls', '', '1'),
(5, 'A.7', 'Physical Controls', '', '1'),
(7, 'A.8', 'Technological Controls', '<p>Helo World,<br><br><strong>This is what this clause means, this is a test.</strong></p>', '1');

-- --------------------------------------------------------

--
-- Table structure for table `policy_details`
--

CREATE TABLE `policy_details` (
  `policy_details_id` int(11) NOT NULL,
  `policy_id` varchar(25) NOT NULL,
  `policy_table` varchar(100) NOT NULL,
  `policy_details` blob NOT NULL,
  `policy_document` varchar(255) DEFAULT NULL,
  `policy_assigned_to` varchar(100) DEFAULT NULL,
  `policy_status` varchar(100) DEFAULT NULL,
  `policy_update_on` varchar(100) DEFAULT NULL,
  `policy_updated_by` varchar(100) DEFAULT NULL,
  `policy_details_added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `policy_details`
--

INSERT INTO `policy_details` (`policy_details_id`, `policy_id`, `policy_table`, `policy_details`, `policy_document`, `policy_assigned_to`, `policy_status`, `policy_update_on`, `policy_updated_by`, `policy_details_added_on`) VALUES
(1, '2', 'sub_control_policy', 0x3c68363e49534f2032373030313a32303133207374616e6461726420636f707920686173206265656e2070726f7669646564206279204d5343492e3c2f68363e3c68363e49534f2032373030313a32303232207374616e6461726420636f707920746f20626520646576656c6f70656420696e7465726e616c6c7920616e642075706c6f616465642e3c2f68363e3c703e3c62723e3c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 14:56:26'),
(2, '1', 'linked_control_policy', 0x3c703e4e657720636f6e74656e7420323c2f703e, NULL, NULL, NULL, '2025-04-23 14:48:58', 'Siddharth Asthana', '2025-02-17 15:33:47'),
(3, '2', 'linked_control_policy', 0x3c68343e496e746572657374656420506172746965733c62723e3c2f68343e3c703e3c2f703e3c703e496e7465726e616c20506172746965733c62723e3c2f703e3c7461626c653e3c74626f64793e3c74723e3c74683e3c623e496e74657265737465642050617274793c2f623e3c62723e3c2f74683e3c74683e3c623e4e656564733c2f623e3c62723e3c2f74683e3c74683e3c623e4578706563746174696f6e733c2f623e3c2f74683e3c2f74723e3c74723e3c74643e5570706572204d616e6167656d656e74202843454f2c2056502c204d616e6167657273293c62723e3c2f74643e3c74643e436f6d6d69746d656e7420746f20696e666f7365632e3c62723e506c616e6e696e67206f6620627573696e657373206f7065726174696f6e732e3c62723e5265706f72747320616e6420757064617465732e3c62723e3c2f74643e3c74643e456e737572652049534d5320697320757020746f20646174652e3c62723e4d616e6167656d656e7420726576696577206d656574696e67732e3c62723e50726f636564757265732061726520666f6c6c6f77656420617320646f63756d656e7465642e3c62723e45737461626c69736820706c616e7320616e6420666f6c6c6f7775702e3c62723e4861766520726567756c6172206d656574696e67732e3c62723e3c2f74643e3c2f74723e3c74723e3c74643e4d6964646c65204d616e6167656d656e7420285465616d204c656164732c2041756469746f7273293c62723e3c2f74643e3c74643e4f7065726174696f6e7320666f6c6c6f772073656375726520646174612070726f636564757265732e3c62723e4d616e6167652064617920746f206461792070726f6a65637420616374697669746965732e3c62723e3c62723e3c2f74643e3c74643e526567756c617220696e666f73656320747261696e696e67732c206d6f6e69746f72696e6720616e6420636f6e74726f6c2e3c62723e5465616d206d656574696e6773206172652068656c6420726567756c61726c792e3c62723e3c2f74643e3c2f74723e3c74723e3c74643e4f7065726174696f6e732053746166663c62723e3c2f74643e3c74643e547261696e696e672072656c6174656420746f20496e666f7365633c62723e4a6f62204465736372697074696f6e20697320636c6561726c7920646566696e65642e3c62723e547261696e696e6720616e6420666565646261636b2e3c62723e3c2f74643e3c74643e456d706c6f796565732073686f756c6420617474656e6420696e666f73656320747261696e696e677320616e642070617373206576616c756174696f6e2e3c62723e48522073686f756c6420636f6d6d756e6963617465204a44206174207468652074696d65206f6620686972696e6720616e642070726f6d6f74696f6e2e3c62723e506572666f726d616e63652061707072616973616c732c20747261696e696e6720616e64206576616c756174696f6e73206172652074696d656c7920636f6e6475637465642e3c2f74643e3c2f74723e3c74723e3c74643e495420616e642053656375726974793c62723e3c2f74643e3c74643e50726f74656374696f6e206f6620706572736f6e616c20646174613c62723e5265736f7572636573206172652070726f76696465642074696d656c793c62723e3c62723e3c2f74643e3c74643e4761696e206b6e6f776c65646765206f6620726567756c6174696f6e732073756368206173204744505220616e6420736166652064617461207072616374696365732e3c62723e526567756c617220696e7465726e616c206175646974732e3c62723e4f7065726174696f6e73204954206e6565647320617265206576616c756174656420616e6420636f6d6d756e6963617465642074696d656c792e3c62723e3c2f74643e3c2f74723e3c2f74626f64793e3c2f7461626c653e3c703e45787465726e616c20506172746965733c62723e3c2f703e3c7461626c653e3c74626f64793e3c74723e3c74643e3c623e496e74657265737465642050617274793c2f623e3c62723e3c2f74643e3c74643e3c623e4e656564733c2f623e3c62723e3c2f74643e3c74643e3c623e4578706563746174696f6e733c2f623e3c2f74643e3c2f74723e3c74723e3c74643e436c69656e743c2f74643e3c74643e4f6e2d74696d652064656c6976657279206f662073657276696365732e3c62723e50726f74656374696f6e206f6620636f6e666964656e7469616c20646174612e3c62723e436f6d706c61696e74207265736f6c7574696f6e2e3c62723e3c2f74643e3c74643e466f6c6c6f77757020616e64206d656574696e677320746f20656e7375726520616c6c20726571756573747320617265206164647265737365642e3c62723e456e7375726520696e666f73656320706f6c6963792065786973747320616e6420697320666f6c6c6f7765642e3c62723e437573746f6d657220636f6d706c61696e7420616e6420666565646261636b206d656368616e69736d2e3c62723e3c2f74643e3c2f74723e3c74723e3c74643e45787465726e616c20536572766963652050726f76696465723c62723e3c2f74643e3c74643e4964656e74696669636174696f6e206f662070726f76696465722069732063617272696564206f7574206279206465706172746d656e74616c206d616e616765722e3c62723e3c2f74643e3c74643e5570706572206d616e6167656d656e7420617070726f7665732074686520736572766963652070726f76696465722e3c62723e3c2f74643e3c2f74723e3c74723e3c74643e56697369746f723c2f74643e3c74643e496e666f726d6174696f6e20726567617264696e672073657276696365733c62723e3c2f74643e3c74643e576562736974652073686f756c642070726f7669646520696e666f726d6174696f6e20726567617264696e672073657276696365732e3c62723e3c2f74643e3c2f74723e3c74723e3c74643e476f7665726e6d656e7420417574686f72697469657320616e6420526567756c61746f727920426f646965733c62723e3c2f74643e3c74643e436f6d706c69616e636520746f206c6567616c20726571756972656d656e74732e3c62723e3c2f74643e3c74643e41776172656e657373206f66206c61777320616e6420726567756c6174696f6e732e3c62723e526567756c6174696f6e732061726520666f6c6c6f7765642e3c62723e3c2f74643e3c2f74723e3c74723e3c74643e49534f2043657274696669636174696f6e20426f64793c62723e3c2f74643e3c74643e2045666665637469766520696d706c656d656e746174696f6e206f662049534f207374616e6461726473207769746820616c6c2072656c6576616e7420636c617573657320696e20746865206f7267616e697a6174696f6e207c20417564697473207265706f7274733c62723e3c2f74643e3c74643e4d61696e74656e616e6365206f662049534f2063657274696669636174696f6e732e3c62723e5265636f6e6e61697373616e6365206175646974732e203c2f74643e3c2f74723e3c2f74626f64793e3c2f7461626c653e3c62723e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 15:39:58'),
(4, '3', 'linked_control_policy', 0x3c64697620636c6173733d5c22636c617573652d636f6e7461696e65725c223e0d0a202020202020202020202020202020203c64697620636c6173733d5c22777973697779675f666f726d617474656420705f735c223e0d0a2020202020203c68343e3c7370616e207374796c653d5c22666f6e742d7765696768743a206e6f726d616c3b5c223e3c623e53636f70653c62723e3c2f623e3c2f7370616e3e3c2f68343e3c68363e5365637572697479206f6620646174612077697468696e205468756e646572436c6f75642070726f6772616d20616e642070726f636573732e3c2f68363e0d0a0d0a3c703e3c623e416464726573733c62723e3c2f623e3c2f703e3c68363e696e33636f727020636f72706f72617465206f66666963653c62723e313735302033307468205374726565742c20537569746520233231363c62723e426f756c6465722c20434f2038303330313c2f68363e0d0a3c703e3c623e496e7465726e6174696f6e616c206f7065726174696f6e733c62723e3c2f623e3c2f703e3c68363e535032302c20536563746f7220432c20416c6967616e6a2c204c75636b6e6f772c20552e502e2c20496e6469613c2f68363e0d0a3c703e3c2f703e0d0a3c703e3c623e4e6f6e2d6170706c696361626c6520636c61757365733c2f623e3a203c62723e3c2f703e3c68363e412e31342e322e37204f7574736f7572636564202020536f66747761726520446576656c6f706d656e743c2f68363e3c2f6469763e2020202020202020202020203c2f6469763e3c703e3c62723e3c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 15:42:44'),
(5, '4', 'linked_control_policy', 0x3c68343e3c623e5768617420776520646f3c2f623e3c2f68343e3c703e5468697320636f6d706c657465642049534d532e6f6e6c696e6520656e7669726f6e6d656e74206973206d616465207570206f662074686520666f6c6c6f77696e6720636f726520636f6d706f6e656e747320666f72206f7065726174696e672c206d61696e7461696e696e6720616e6420696d70726f76696e6720746865206f7267616e697a6174696f6e5c27732049534d533a3c62723e3c62723e266e6273703b266e6273703b266e6273703b205468697320506f6c696369657320616e6420436f6e74726f6c73206172656120616e642053746174656d656e74206f66204170706c69636162696c697479203c62723e266e6273703b266e6273703b266e6273703b20546865205269736b2026616d703b2054726561746d656e7420617265613c62723e266e6273703b266e6273703b266e6273703b2054686520496e74657265737465642050617274696573206d61703c62723e266e6273703b266e6273703b266e6273703b20546865207265676973746572206f66204170706c696361626c65204c656769736c6174696f6e3c62723e266e6273703b266e6273703b266e6273703b2054686520417373657420496e76656e746f7279203c62723e266e6273703b266e6273703b266e6273703b20546865204d616e6167656d656e742052657669657720426f617264203c62723e266e6273703b266e6273703b266e6273703b205468652041756469742050726f6772616d6d65203c62723e266e6273703b266e6273703b266e6273703b2054686520436f727265637469766520416374696f6e7320616e6420496d70726f76656d656e747320747261636b3c62723e266e6273703b266e6273703b266e6273703b2054686520536563757269747920496e636964656e74204d616e6167656d656e7420747261636b3c62723e266e6273703b266e6273703b266e6273703b2054686520537461666620636f6d6d756e69636174696f6e7320617265613c62723e3c62723e45616368206172656120697320726576696577656420616e64207570646174656420696e206c696e65207769746820412e352e312e323a20526576696577206f662074686520706f6c696369657320666f7220696e666f726d6174696f6e20736563757269747920616e6420412e31382e3220496e666f726d6174696f6e20736563757269747920726576696577732e20696d70726f76656d656e747320617265206d616e6167656420696e206c696e65207769746820313020496d70726f76656d656e742e3c62723e3c62723e416e20617564697420747261696c206f6620616c6c206368616e67657320697320636c6561726c792076697369626c6520696e2065616368206172656120616e6420746865204c696e6b656420576f726b20746f6f6c206973207573657320746f2073686f772072656c6174696f6e7368697073206265747765656e20696e646976696475616c207061727473206f66207468652049534d532e3c62723e3c62723e546869732049534d532069732064657369676e656420616e6420646576656c6f70656420746f20616c6c6f7720757320746f206372656174652c20636f6c6c61626f726174652c20636f6e74726f6c20616e6420636f6d6d756e696361746520696e206c696e6520776974682074686520636c6175736520616e6420636f6e74726f6c20726571756972656d656e74732e204175746f6d61746963616c6c7920676976696e67207573206120636f6e747269627574696f6e20746f77617264733a3c62723e3c62723e3c623e436c61757365733c62723e3c2f623e3c62723e266e6273703b266e6273703b266e6273703b20352e333a204f7267616e69736174696f6e20726f6c65732c20726573706f6e736962696c697469657320616e6420617574686f7269746965733c62723e266e6273703b266e6273703b266e6273703b20362e312e333a2053746174656d656e74206f66204170706c69636162696c6974793c62723e266e6273703b266e6273703b266e6273703b20372e313a205265736f75726365733c62723e266e6273703b266e6273703b266e6273703b20372e323a20436f6d706574656e63653c62723e266e6273703b266e6273703b266e6273703b20372e333a2041776172656e6573733c62723e266e6273703b266e6273703b266e6273703b20372e343a20436f6d6d756e69636174696f6e3c62723e266e6273703b266e6273703b266e6273703b20372e353a20446f63756d656e74656420696e666f726d6174696f6e3c62723e266e6273703b266e6273703b266e6273703b20383a204f7065726174696f6e3c62723e266e6273703b266e6273703b266e6273703b20392e313a204d6f6e69746f72696e672026616d703b207265766965773c62723e266e6273703b266e6273703b266e6273703b2031302e323a20436f6e74696e756f757320696d70726f76656d656e74203c62723e3c62723e3c623e436f6e74726f6c733c2f623e3c62723e3c62723e266e6273703b266e6273703b266e6273703b20412e352e312e313a20506f6c696369657320666f7220696e666f20736563757269747920617070726f766564206279206d616e6167656d656e742c207075626c697368656420616e6420636f6d6d756e6963617465643c62723e266e6273703b266e6273703b266e6273703b20412e352e312e323a20526576696577206f6620706f6c696369657320e280932072657669657720617420706c616e6e656420696e74657276616c732c206f72207369676e69666963616e74206368616e6765733c62723e266e6273703b266e6273703b266e6273703b20412e362e312e313a20526f6c65732026616d703b20726573706f6e736962696c69746965733c62723e266e6273703b266e6273703b266e6273703b20412e362e312e323a205365677265676174696f6e206f66206475746965733c62723e266e6273703b266e6273703b266e6273703b20412e372e322e323a2041776172656e6573732c20656475636174696f6e2026616d703b20747261696e696e673c62723e266e6273703b266e6273703b266e6273703b20412e31382e323a20496e646570656e64656e7420616e6420746563686e6963616c20726576696577206f6620696e666f2073656320617420706c616e6e656420696e74657276616c733c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 15:45:37'),
(6, '5', 'linked_control_policy', 0x3c703e546573743c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 15:46:31'),
(7, '6', 'linked_control_policy', 0x3c68363e546865206f7267616e697a6174696f6e616c20496e666f726d6174696f6e20536563757269747920506f6c69637920697320617474616368656420756e646572203c6120687265663d5c2268747470733a2f2f706c6174666f726d2e72332e69736d732e6f6e6c696e652f70726f6a656374732f32323434362f64656c6976657261626c65732f3931323233395c223e41352e312e313c2f613e20616e642063616e20626520736861726564207769746820696e74657265737465642070617274696573206173206e65636573736172792e3c2f68363e0d0a3c68363e416c6c207375626f7264696e61746520706f6c696369657320616e6420636f6e74726f6c732061646865726520746f207468697320706f6c69637920616e6420617265200d0a636f6e7461696e65642077697468696e2074686973206f6e6c696e652049534d5320656e7669726f6e6d656e7420616e642063616e206265206d61646520617661696c61626c65200d0a617320737065636966696320646f63756d656e74732077686572652072657175697265642e3c2f68363e3c703e3c62723e3c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 16:07:27'),
(8, '7', 'linked_control_policy', 0x3c68363e52656c6576616e74204a4473206172652061747461636865642077697468207468697320706167652e3c62723e3c2f68363e0d0a3c68363e546869732073656374696f6e2073756d6d6172697a65732074686520726f6c6573206f66207570706572206d616e6167656d656e7420616e642070656f706c6520726573706f6e7369626c6520666f722049534d532e3c62723e3c2f68363e0d0a3c68363e4d696775656c205a6176616c61206973207468652043454f206f6620696e33636f727020616e642069732061206b6579207374616b65686f6c646572206f6620746865200d0a636f6d70616e792e266e6273703b2048652068617320612064697265637420696e74657265737420696e20656e737572696e67207468617420746865206f7267616e697a6174696f6e200d0a70617373657320636c69656e74206578706563746174696f6e7320696e20666c79696e6720636f6c6f72732e266e6273703b20486520697320746865206b6579206465636973696f6e200d0a6d616b657220616e6420617070726f766573206465636973696f6e7320666f72206f7065726174696f6e732c20496e666f726d6174696f6e2053797374656d7320616e64200d0a66696e616e6365732e266e6273703b2043454f206973207468652068656164206f66204d616e6167656d656e742052657669657720426f617264206f662049534d532061732077656c6c2061730d0a20746865204349534f2c20616e64206c6561647320717561727465726c79204d616e6167656d656e742052657669657720426f617264206d656574696e67732c20656e73757265730d0a20696e7465726e616c2061756469742074616b657320706c6163657320616e6e75616c6c792077697468696e2033206d6f6e746873206265666f72652065787465726e616c200d0a61756469742e3c62723e3c2f68363e0d0a3c68363e4e69636b2042726f776e206173205669636520507265736964656e7420697320616c736f2061207061727479206f6620696e746572657374206265696e67206120706172740d0a206f66207570706572206d616e6167656d656e742e20486520697320616c736f2070617274206f662049534d53204d616e6167656d656e742052657669657720426f617264200d0a77697468696e20776869636820686520697320726573706f6e7369626c6520666f7220656e737572696e67207468617420616c6c206f7065726174697665207374616666206172650d0a2070726f7065726c7920747261696e656420616e6420666f6c6c6f77696e6720616c6c2070726f746f636f6c73206173736f6369617465642077697468207468652049534d532e203c62723e3c2f68363e0d0a3c68363e4a61796120506f6f6e6a6120697320696e20636861726765206f6620616c6c2048522072656c6174656420616374697669746965732e20536865206973200d0a726573706f6e7369626c6520666f7220656e737572696e67206261636b67726f756e6420636865636b732c206f6e2d626f617264696e6720616e642065786974206f66200d0a656d706c6f79656573206172652070726f7065726c7920646f63756d656e7465642c2061732077656c6c206173206f74686572204852207461736b732061732074686579200d0a72656c61746520746f207468652049534d532e266e6273703b2053686520697320616c736f2070617274206f66204d616e6167656d656e742052657669657720426f6172642e3c62723e3c2f68363e0d0a3c68363e5369646468617274682041737468616e6120616e642053687269796120526167687576616e736869206172652064657369676e61746564205365637572697479200d0a436f6f7264696e61746f727320616e642061726520726573706f6e7369626c6520666f72207265706f7274696e67206f6e2074686520706572666f726d616e6365206f66207468650d0a2073797374656d20746f207570706572206d616e6167656d656e7420616e6420746865792061726520616c736f2070617274206f662049534d53204d616e6167656d656e74200d0a52657669657720426f6172642e266e6273703b2054686579206f776e20616374696f6e73207065727461696e696e6720746f206d61696e74656e616e6365206f662049534d53200d0a73797374656d2c207375636820617320757064617465206f6620636f6e74726f6c20706f696e74732c207265636f7264696e6720616e6420747261636b696e67200d0a696e636964656e74732c207265636f7264696e6720616e6420747261636b696e6720636f6d706c69616e636520616e6420696d70726f76656d656e74732e3c62723e3c2f68363e0d0a3c68363e5369646468617274682041737468616e6120686173206265656e2064657369676e6174656420666f72207265706f7274696e67206f6e2074686520706572666f726d616e6365206f662049534d53207370656369666963616c6c7920696e766f6c76696e6720696d70726f76656d656e747320746f20746f70206d616e6167656d656e742e3c62723e3c2f68363e0d0a3c68363e5369646468617274682041737468616e6120616e642053687269796120526167687576616e73686920617265207468652064657369676e617465642044617461200d0a50726f74656374696f6e204f666669636572732028736563757269747920636f6f7264696e61746f72732920616e64206c656164206f6e20617370656374732072656c6174696e670d0a20746f207468652070726f74656374696f6e206f66207072697661637920616e6420706572736f6e616c206461746120696e636c7564696e6720706572736f6e616c6c79200d0a6964656e7469666961626c6520696e666f726d6174696f6e20285049492920696e20636f6d706c69616e636520776974682074686520474450522e3c62723e3c2f68363e0d0a0d0a3c68363e4561636820636f6e74726f6c20616e6420706f6c69637920617265612077697468696e207468697320656e7669726f6e6d656e742068617320616e206f776e65722077686f0d0a20697320726573706f6e7369626c6520666f7220697473207570646174696e6720616e64207265706f7274696e6720666f7220616e792070726f706f736564206368616e676573200d0a6f7220616d656e646d656e74732e20546865792063616e20616c6c6f77206f7468657220617574686f72697a656420737461666620746f2070726f706f7365206368616e676573200d0a746f2074686520726571756972656d656e74732c20706f6c696369657320616e6420636f6e74726f6c732e20417574686f72697a65642073746166662077696c6c2068617665200d0a646972656374207465616d206d656d62657273686970206f66207468697320706f6c696369657320616e6420636f6e74726f6c7320656e7669726f6e6d656e742e3c2f68363e0d0a3c68363e416e7920616d656e646d656e747320746f207468652049534d5320726571756972656d656e74732c20706f6c696369657320616e6420636f6e74726f6c73206d6179200d0a67657420646f6e652064796e616d6963616c6c792c20692e652e2061732074686520736974756174696f6e2072657175697265732c206f7220666f72206d6f7265200d0a66756e64616d656e74616c206368616e6765732c2061667465722064697363757373696f6e20616e642061677265656d656e742077697468206f6e65206f72206d6f7265200d0a72656c6576616e74207570706572206d616e6167656d656e74206d656d62657273202877686f20696e20616e79206576656e7420696e646570656e64656e746c79200d0a617070726f766520616e7920706f6c696379206368616e6765206f72206172652064656c65676174696e6720617070726f76616c20696e206163636f7264616e63652077697468200d0a74686520726f6c65732061626f7665292e3c2f68363e3c703e3c62723e3c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 16:07:59'),
(9, '1', 'inner_linked_control_policy', 0x3c68363e496e7465726e616c20616e642065787465726e616c207269736b732068617665206265656e206964656e7469666965642c206d6561737572656420616e64200d0a61737369676e656420746f2072656c6576616e7420636f6e74726f6c20706f696e74732e266e6273703b204164646974696f6e616c207269736b7320616e642074687265617473206966200d0a616e64207768656e206964656e74696669656420696e206675747572652c20776f756c642062652061737369676e656420746f2072656c6576616e7420636f6e74726f6c200d0a706f696e74732e3c2f68363e0d0a3c68363e546865266e6273703b7269736b732026616d703b2074726561746d656e7473266e6273703b746f6f6c0d0a206973266e6273703b7573656420746f206d617020616e64207472656174207269736b73206964656e7469666965642066726f6d2074686520616e616c797369732e204974206973266e6273703b616c736f0d0a207573656420746f2065766964656e636520616374697669747920616e642064656d6f6e737472617465206c696e6b73206261636b20746f2074686520636f6e74726f6c73200d0a616e6420706f6c69636965732073656c656374656420627920746865206f7267616e697a6174696f6e20746f206164647265737320746865207269736b2074687265617473200d0a616e64206f70706f7274756e69746965732e3c62723e3c2f68363e0d0a3c68363e5269736b73206964656e746966696564206172652070726573656e746564206f6e205269736b204d61702077697468696e205269736b732026616d703b2054726561746d656e747320746f6f6c206f6e20746865206261736973206f662074686569722073636f726573202f2072656164696e672e3c62723e3c2f68363e0d0a3c68363e4c696b656c69686f6f642069732072617465732061733a3c62723e3c2f68363e0d0a3c68363e31202d2056657279204c6f773c62723e3c2f68363e0d0a3c68363e32202d204c6f773c62723e3c2f68363e0d0a3c68363e33202d204d656469756d3c62723e3c2f68363e0d0a3c68363e34202d20486967683c62723e3c2f68363e0d0a3c68363e35202d205665727920486967683c62723e3c2f68363e0d0a0d0a3c68363e496d70616374206973207468652043494120776974682074686520686967686573742076616c75652e266e6273703b20496d706163742069732072617465642061733a3c62723e3c2f68363e0d0a3c68363e30202d204e6f6e653c62723e3c2f68363e0d0a3c68363e31202d20496e7369676e69666963616e743c62723e3c2f68363e0d0a3c68363e32202d204d696e6f723c62723e3c2f68363e0d0a3c68363e33202d204d6f6465726174653c62723e3c2f68363e0d0a3c68363e34202d204d616a6f723c62723e3c2f68363e0d0a3c68363e35202d205365766572653c62723e3c2f68363e0d0a0d0a3c68363e4c696b656c69686f6f6420616e6420496d706163742076616c756573206172652061737369676e656420746f2065616368207269736b20646570656e64696e672075706f6e0d0a207768617420636f6e74726f6c732068617665206265656e20696d706c656d656e7465642e2054686520696e74656e7420697320746f206c6f776572207468652073636f7265200d0a6173206d75636820617320706f737369626c6520746f2c20696620706f737369626c652c20656c696d696e61746520746865207269736b2e3c62723e3c2f68363e0d0a3c68363e5269736b73206f6e63652072617465642f73636f72656420737461727420617070656172696e67206f6e20746865205269736b204d6170207769746820746865200d0a6c6f776573742073636f726564207269736b732061742074686520626f74746f6d206c65667420616e642074686520686967686573742073636f72696e67206f6e20746865200d0a75707065722072696768742e266e6273703b205269736b204d617020697320612067726964206f662035205820352077697468204c696b656c69686f6f642061732074686520592061786973200d0a616e6420496d7061637420617320746865205820617869732e266e6273703b2041207269736b207769746820496d70616374203320286d6f6465726174652920616e64200d0a4c696b656c69686f6f64203220286c6f772920776f756c642073686f7720696e206772696420332c322e3c62723e3c2f68363e0d0a3c68363e47726964732061726520636f6c6f726564206163636f7264696e6720746f20746865207269736b2073636f726520696e746f20666f757220636f6c6f72732e266e6273703b20477265656e206265696e6720746865206c6f77657374207269736b7320616e6420626c61636b20686967686573742e3c62723e3c2f68363e0d0a3c68363e5269736b20726576696577206672657175656e637920646570656e64732075706f6e20746865206772696420636f6c6f7220696e20776869636820746865207269736b20617070656172732e3c62723e3c2f68363e0d0a3c68363e477265656e202d206174206c6561737420616e6e75616c6c793c62723e3c2f68363e0d0a3c68363e59656c6c6f77202d206174206c656173742068616c6620796561726c793c62723e3c2f68363e0d0a3c68363e526564202d206174206c6561737420717561727465726c793c62723e3c2f68363e0d0a3c68363e426c61636b202d206174206c65617374206d6f6e74686c793c62723e3c2f68363e0d0a0d0a3c68363e466f722064657461696c20726567617264696e67205269736b204d617020706c6561736520726566657220746f20617474616368656420646f63756d656e7420362e315f5269736b5f54726561746d656e745f4d6574686f646f6c6f67795f2d5f49534d532e4f6e6c696e655f2d5f56312e372e646f63783c2f68363e3c68363e3c62723e3c2f68363e, NULL, NULL, NULL, NULL, NULL, '2025-02-17 16:11:14'),
(10, '17', 'sub_control_policy', 0x3c703e5465737420446174613c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-02-27 14:12:40'),
(11, '1', 'sub_control_policy', 0x3c703e416464656420436f6d6d656e74204e65773c2f703e, NULL, NULL, NULL, NULL, NULL, '2025-04-14 16:41:36');

-- --------------------------------------------------------

--
-- Table structure for table `policy_details_history`
--

CREATE TABLE `policy_details_history` (
  `history_id` int(11) NOT NULL,
  `policy_details_id` int(11) DEFAULT NULL,
  `policy_id` varchar(25) DEFAULT NULL,
  `policy_table` varchar(100) DEFAULT NULL,
  `policy_details` blob,
  `policy_document` varchar(255) DEFAULT NULL,
  `policy_assigned_to` varchar(100) DEFAULT NULL,
  `policy_status` varchar(100) DEFAULT NULL,
  `policy_update_on` varchar(100) DEFAULT NULL,
  `policy_updated_by` varchar(100) DEFAULT NULL,
  `version_saved_on` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `policy_documents`
--

CREATE TABLE `policy_documents` (
  `policy_document_id` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `policy_table_for_document` enum('sub_control_policy','linked_control_policy','inner_linked_control_policy') NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_path` varchar(500) NOT NULL,
  `document_version` varchar(55) DEFAULT NULL,
  `document_uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `policy_documents`
--

INSERT INTO `policy_documents` (`policy_document_id`, `policy_id`, `policy_table_for_document`, `document_name`, `document_path`, `document_version`, `document_uploaded_at`) VALUES
(1, 2, 'sub_control_policy', 'ISO_27001_standard.PDF', 'uploads/1739784534_ISO_27001_standard.PDF', '1', '2025-02-17 09:28:54'),
(2, 1, 'inner_linked_control_policy', '6.1_Risk_Treatment_Methodology_-_ISMS.Online_-_V1.7.docx', 'uploads/1739788891_6.1_Risk_Treatment_Methodology_-_ISMS.Online_-_V1.7.docx', '1', '2025-02-17 10:41:31'),
(3, 1, 'linked_control_policy', 'ISO27001-2022.pdf', 'uploads/1740570264_ISO27001-2022.pdf', '1.2', '2025-02-26 11:44:24'),
(4, 17, 'sub_control_policy', 'ISO27001-2022.pdf', 'uploads/1740645773_ISO27001-2022.pdf', '1.2', '2025-02-27 08:42:53'),
(5, 82, 'sub_control_policy', 'BACKUP LOGS - V2 - MASTER.xlsx', 'uploads/BACKUP LOGS - V2 - MASTER.xlsx', '1.2', '2025-03-03 09:16:26'),
(6, 1, 'inner_linked_control_policy', 'Headers.xlsx', 'uploads/1745401868_Headers.xlsx', '1', '2025-04-23 09:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `remote_user`
--

CREATE TABLE `remote_user` (
  `ru_id` int(11) NOT NULL,
  `ru_name` varchar(100) DEFAULT NULL,
  `ru_serv_prov` varchar(100) DEFAULT NULL,
  `ru_modem` varchar(100) DEFAULT NULL,
  `ru_ipd` varchar(100) DEFAULT NULL,
  `ru_dsp` varchar(100) DEFAULT NULL,
  `ru_usp` varchar(100) DEFAULT NULL,
  `ru_image` blob,
  `ru_sec_type` varchar(100) DEFAULT NULL,
  `ru_band` varchar(100) DEFAULT NULL,
  `ru_upload_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `risks`
--

CREATE TABLE `risks` (
  `risks_id` int(11) NOT NULL,
  `risks_name` varchar(255) NOT NULL,
  `risks_description` blob,
  `risks_likelihood` varchar(50) DEFAULT NULL,
  `risks_impact` varchar(50) DEFAULT NULL,
  `risks_status` varchar(50) DEFAULT NULL,
  `risks_created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `risks_created_by` varchar(50) DEFAULT NULL,
  `risks_action` varchar(100) DEFAULT NULL,
  `risks_review_date` varchar(100) DEFAULT NULL,
  `risks_assigned_to` varchar(100) DEFAULT NULL,
  `risks_updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `risks`
--

INSERT INTO `risks` (`risks_id`, `risks_name`, `risks_description`, `risks_likelihood`, `risks_impact`, `risks_status`, `risks_created_at`, `risks_created_by`, `risks_action`, `risks_review_date`, `risks_assigned_to`, `risks_updated_at`) VALUES
(11, 'Test', 0x3c703e546573743c2f703e, 'Very Low', 'Insignificant', 'Open', '2025-03-18 09:47:11', 'Siddharth Asthana', NULL, NULL, NULL, '2025-03-18 09:47:19'),
(12, 'Test 2', 0x3c703e5465737420323c2f703e, 'Very Low', 'Insignificant', 'Open', '2025-03-18 09:49:13', 'Siddharth Asthana', NULL, NULL, NULL, '2025-03-18 09:49:20'),
(13, 'New', NULL, 'Very Low', 'Insignificant', 'Open', '2025-03-18 11:37:37', 'Siddharth Asthana', NULL, NULL, NULL, '2025-03-18 11:37:37'),
(14, 'New 2', NULL, 'Very Low', 'Insignificant', 'Open', '2025-03-18 11:37:42', 'Siddharth Asthana', NULL, NULL, NULL, '2025-03-18 11:37:42'),
(15, 'New 34', 0x3c703e546573743c2f703e, 'High', 'Minor', 'In Progress', '2025-03-18 11:37:47', 'Siddharth Asthana', 'Treat (Other)', '2025-04-24', 'Siddharth Asthana', '2025-04-24 09:39:11'),
(19, 'Test Risks after additions', 0x3c703e546573742054726561746d656e743c2f703e, 'High', 'Moderate', 'Open', '2025-04-09 08:30:58', 'Siddharth Asthana', 'Combination of actions', '2025-05-09', 'Manish Kumar', '2025-04-15 10:30:49');

-- --------------------------------------------------------

--
-- Table structure for table `risk_policies`
--

CREATE TABLE `risk_policies` (
  `risk_policy_id` int(11) NOT NULL,
  `risks_id` int(11) DEFAULT NULL,
  `clause_id` int(11) DEFAULT NULL,
  `clause_type` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sim`
--

CREATE TABLE `sim` (
  `sim_id` int(11) NOT NULL,
  `sim_topic` varchar(255) DEFAULT NULL,
  `sim_details` blob,
  `sim_status` varchar(50) DEFAULT NULL,
  `sim_severity` varchar(50) DEFAULT NULL,
  `sim_source` varchar(50) DEFAULT NULL,
  `sim_type` varchar(50) DEFAULT NULL,
  `sim_final` varchar(15) DEFAULT NULL,
  `sim_reported_date` varchar(100) DEFAULT NULL,
  `sim_reported_by` varchar(50) DEFAULT NULL,
  `sim_assigned_to` varchar(100) DEFAULT NULL,
  `sim_due_date` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sim`
--

INSERT INTO `sim` (`sim_id`, `sim_topic`, `sim_details`, `sim_status`, `sim_severity`, `sim_source`, `sim_type`, `sim_final`, `sim_reported_date`, `sim_reported_by`, `sim_assigned_to`, `sim_due_date`) VALUES
(4, 'Test', 0x3c703e546573743c2f703e, '2', '1', '2', '2', '2', NULL, 'Siddharth Asthana', 'Siddharth Asthana', '2024-05-17'),
(5, 'Proforce', 0x3c703e546573742068656c6c6f3c2f703e, '2', '1', '1', '1', '2', '2025-04-22', 'Siddharth Asthana', 'Siddharth Asthana', '2025-04-23'),
(2, 'New topic', 0x3c703e4c6f72656d20697073756d20646f6c6f722073697420616d657420636f6e7365637465747572206164697069736963696e6720656c69742e20436f6e73656374657475722073757363697069742c20616c69617320646f6c6f726520646f6c6f72656d206e61747573206973746520697572652073617069656e74653f2043756d20636f72706f7269732076656c20636f727275707469206661636572652061737065726e61747572206c61626f7265206d6f6c6573746961732069737465206d61676e616d206f66666963696973206e616d2e20456f73213c2f703e, '2', '2', '2', '1', '2', '2025-04-22', 'Siddharth Asthana', 'Siddharth Asthana', '2025-04-23');

-- --------------------------------------------------------

--
-- Table structure for table `sim_comment`
--

CREATE TABLE `sim_comment` (
  `comment_id` int(11) NOT NULL,
  `comment_parent_id` varchar(100) DEFAULT NULL,
  `comment_owner` varchar(100) DEFAULT NULL,
  `comment_data` blob,
  `comment_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sim_comment`
--

INSERT INTO `sim_comment` (`comment_id`, `comment_parent_id`, `comment_owner`, `comment_data`, `comment_date`) VALUES
(10, '10', 'Siddharth Asthana', 0x4e657720436f6d6d656e74, '2025-03-24 19:41:42'),
(11, '12', 'Siddharth Asthana', 0x5465737420636f6d6d656e740d0a, '2025-04-14 17:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `sub_control_policy`
--

CREATE TABLE `sub_control_policy` (
  `sub_control_policy_id` int(11) NOT NULL,
  `main_control_policy_id` varchar(15) NOT NULL,
  `sub_control_policy_number` varchar(15) NOT NULL,
  `sub_control_policy_heading` varchar(100) NOT NULL,
  `sub_control_policy_det` longtext,
  `sub_control_policy_status` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_control_policy`
--

INSERT INTO `sub_control_policy` (`sub_control_policy_id`, `main_control_policy_id`, `sub_control_policy_number`, `sub_control_policy_heading`, `sub_control_policy_det`, `sub_control_policy_status`) VALUES
(1, '1', '1.', 'Scope', '<p>This document specifies the requirements for establishing, implementing, maintaining and continually improving an information security management system within the context of the organization. This document also includes requirements for the assessment and treatment of information security risks tailored to the needs of the organization. The requirements set out in this document are generic and are intended to be applicable to all organizations, regardless of type, size or nature. Excluding any of the requirements specified in <a href="policies-controls.php"> Clauses 4</a> to <a href="policies-controls.php">10</a> is not acceptable when an organization claims conformity to this document.</p>', '1'),
(2, '1', '2.', 'Normative References', '<p>The following documents are referred to in the text in such a way that some or all of their content constitutes requirements of this document. For dated references, only the edition cited applies. For undated references, the latest edition of the referenced document (including any amendments) applies.</p>\r\n<p>ISO/IEC 27000, Information technology &mdash; Security techniques &mdash; Information security management systems &mdash; Overview and vocabulary&nbsp;<br><br>&nbsp;</p>', '1'),
(3, '1', '3.', 'Terms and definitions', '<p>For the purposes of this document, the terms and definitions given in ISO/IEC 27000 apply.</p>\n<p>ISO and IEC maintain terminology databases for use in standardization at the following addresses:</p>\n<ul>\n<li>ISO Online browsing platform: available at <a href="https://www.iso.org/obp">https://www.iso.org/obp</a></li>\n<li>IEC Electropedia: available at <a href="https://www.electropedia.org/">https://www.electropedia.org/</a></li>\n</ul>', '1'),
(4, '2', '4.', 'Context of the Organization', '', '1'),
(5, '2', '5.', 'Leadership', '', '1'),
(6, '2', '6.', 'Planning', '', '1'),
(7, '2', '7.', 'Support', '', '1'),
(8, '2', '8.', 'Operation', '', '1'),
(9, '2', '9.', 'Performance Evaluation', '', '1'),
(10, '2', '10.', 'Improvement', '', '1'),
(11, '3', 'A.5.1', 'Policies for information security', '<div id="5.1" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information security policy and topic-specific policies shall be de- fined, approved by management, published, communicated to and acknowledged by relevant personnel and relevant interested parties, and reviewed at planned intervals and if significant changes occur.</p>\r\n</div>\r\n</div>', '1'),
(12, '3', 'A.5.2', 'Information security roles and responsibilities', '<div id="5.2" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information security roles and responsibilities shall be defined and allocated according to the organization needs.</p>\r\n</div>\r\n</div>', '1'),
(13, '3', 'A.5.3', 'Segregation of duties', '<div id="5.3" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Conflicting duties and conflicting areas of responsibility shall be seg- regated.</p>\r\n</div>\r\n</div>', '1'),
(14, '3', 'A.5.4', 'Management Responsibilities', '<div id="5.4" data-bs-parent="#accordionExample1">\n<div>\n<p><strong>CONTROL</strong></p>\n<p>Management shall require all personnel to apply information security in accordance with the established information security policy, topic-specific policies and procedures of the organization.</p>\n</div>\n</div>', '1'),
(15, '3', 'A.5.5', 'Contact with authorities', '<div id="5.5" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall establish and maintain contact with relevant authorities.</p>\r\n</div>\r\n</div>', '1'),
(16, '3', 'A.5.6', 'Contact with special interest groups', '<div id="5.6" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall establish and maintain contact with special interest groups or other specialist security forums and professional associations.</p>\r\n</div>\r\n</div>', '1'),
(17, '3', 'A.5.7', 'Threat Intelligence', '<div id="5.7" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information relating to information security threats shall be collected and analysed to produce threat intelligence.</p>\r\n</div>\r\n</div>', '1'),
(18, '3', 'A.5.8', 'Information security in project management', '<div id="5.8" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information security shall be integrated into project management.</p>\r\n</div>\r\n</div>', '1'),
(19, '3', 'A.5.9', 'Inventory of information and other associated assets', '<div id="5.9" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>An inventory of information and other associated assets, including owners, shall be developed and maintained.</p>\r\n</div>\r\n</div>', '1'),
(20, '3', 'A.5.10', 'Acceptable use of information and other associated assets', '<div id="5.10" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Rules for the acceptable use and procedures for handling information and other associated assets shall be identified, documented and implemented.</p>\r\n</div>\r\n</div>', '1'),
(21, '3', 'A.5.11', 'Return of assets', '<div id="5.11" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Personnel and other interested parties as appropriate shall return all the organization&rsquo;s assets in their possession upon change or termination of their employment, contract or agreement.</p>\r\n</div>\r\n</div>', '1'),
(22, '3', 'A.5.12', 'Classification of information', '<div id="5.12" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information shall be classified according to the information security needs of the organization based on confidentiality, integrity, availability and relevant interested party requirements.</p>\r\n</div>\r\n</div>', '1'),
(23, '3', 'A.5.13', 'Labelling of information', '<div id="5.13" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>An appropriate set of procedures for information labelling shall be developed and implemented in accordance with the information clas- sification scheme adopted by the organization.</p>\r\n</div>\r\n</div>', '1'),
(24, '3', 'A.5.14', 'Information transfer', '<div id="5.14" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information transfer rules, procedures, or agreements shall be in place for all types of transfer facilities within the organization and between the organization and other parties.</p>\r\n</div>\r\n</div>', '1'),
(25, '3', 'A.5.15', 'Access Control', '<div id="5.15" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Rules to control physical and logical access to information and other associated assets shall be established and implemented based on busi- ness and information security requirements.</p>\r\n</div>\r\n</div>', '1'),
(26, '3', 'A.5.16', 'Identity Management', '<div id="5.16" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The full life cycle of identities shall be managed.</p>\r\n</div>\r\n</div>', '1'),
(27, '3', 'A.5.17', 'Authentication information', '<div id="5.17" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Allocation and management of authentication information shall be controlled by a management process, including advising personnel on appropriate handling of authentication information.</p>\r\n</div>\r\n</div>', '1'),
(28, '3', 'A.5.18', 'Access Rights', '<div id="5.18" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Access rights to information and other associated assets shall be provisioned, reviewed, modified and removed in accordance with the organization&rsquo;s topic-specific policy on and rules for access control.</p>\r\n</div>\r\n</div>', '1'),
(29, '3', 'A.5.19', 'Information security in supplier relationships', '<div id="5.19" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Processes and procedures shall be defined and implemented to manage the information security risks associated with the use of supplier&rsquo;s products or services.</p>\r\n</div>\r\n</div>', '1'),
(30, '3', 'A.5.20', 'Addressing information security within supplier agreements', '<div id="5.20" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Relevant information security requirements shall be established and agreed with each supplier based on the type of supplier relationship.</p>\r\n</div>\r\n</div>', '1'),
(31, '3', 'A.5.21', 'Managing information security in the information and communication technology (ICT) supply chain', '<div id="5.21" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Processes and procedures shall be defined and implemented to manage the information security risks associated with the ICT products and services supply chain.</p>\r\n</div>\r\n</div>', '1'),
(32, '3', 'A.5.22', 'Monitoring, review and change management of supplier services', '<div id="5.22" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall regularly monitor, review, evaluate and manage change in supplier information security practices and service delivery.</p>\r\n</div>\r\n</div>', '1'),
(33, '3', 'A.5.23', 'Information security for use of cloud services', '<div id="5.23" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Processes for acquisition, use, management and exit from cloud services shall be established in accordance with the organization&rsquo;s information security requirements.</p>\r\n</div>\r\n</div>', '1'),
(34, '3', 'A.5.24', 'Information security incident management planning and preparation', '<div id="5.24" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall plan and prepare for managing information secu- rity incidents by defining, establishing and communicating information security incident management processes, roles and responsibilities.</p>\r\n</div>\r\n</div>', '1'),
(35, '3', 'A.5.25', 'Assessment and decision on information security events', '<div id="5.25" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall assess information security events and decide if they are to be categorized as information security incidents.</p>\r\n</div>\r\n</div>', '1'),
(36, '3', 'A.5.26', 'Response to information security incidents', '<div id="5.26" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information security incidents shall be responded to in accordance with the documented procedures.</p>\r\n</div>\r\n</div>', '1'),
(37, '3', 'A.5.27', 'Learning from information security incidents', '<div id="5.27" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Knowledge gained from information security incidents shall be used to strengthen and improve the information security controls.</p>\r\n</div>\r\n</div>', '1'),
(38, '3', 'A.5.28', 'Collection of evidence', '<div id="5.28" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall establish and implement procedures for the identification, collection, acquisition and preservation of evidence related to information security events.</p>\r\n</div>\r\n</div>', '1'),
(39, '3', 'A.5.29', 'Information security during disruption', '<div id="5.29" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall plan how to maintain information security at an appropriate level during disruption.</p>\r\n</div>\r\n</div>', '1'),
(40, '3', 'A.5.30', 'ICT readiness for business continuity', '<div id="5.30" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>ICT readiness shall be planned, implemented, maintained and tested based on business continuity objectives and ICT continuity requirements.</p>\r\n</div>\r\n</div>', '1'),
(41, '3', 'A.5.31', 'Legal, statutory, regulatory and contractual requirements', '<div id="5.31" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Legal, statutory, regulatory and contractual requirements relevant to information security and the organization&rsquo;s approach to meet these requirements shall be identified, documented and kept up to date.</p>\r\n</div>\r\n</div>', '1'),
(42, '3', 'A.5.32', 'Intellectual property rights', '<div id="5.32" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall implement appropriate procedures to protect intellectual property rights</p>\r\n</div>\r\n</div>', '1'),
(43, '3', 'A.5.33', 'Protection of records', '<div id="5.33" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Records shall be protected from loss, destruction, falsification, unau- thorized access and unauthorized release</p>\r\n</div>\r\n</div>', '1'),
(44, '3', 'A.5.34', 'Privacy and protection of personal identifiable information (PII)', '<div id="5.34" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall identify and meet the requirements regarding the preservation of privacy and protection of PII according to applicable laws and regulations and contractual requirements.</p>\r\n</div>\r\n</div>', '1'),
(45, '3', 'A.5.35', 'Independent review of information security', '<div id="5.35" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization&rsquo;s approach to managing information security and its implementation including people, processes and technologies shall be reviewed independently at planned intervals, or when significant changes occur.</p>\r\n</div>\r\n</div>', '1'),
(46, '3', 'A.5.36', 'Compliance with policies, rules and standards for information security ', '<div id="5.36" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Compliance with the organization&rsquo;s information security policy, topic-specific policies, rules and standards shall be regularly reviewed.</p>\r\n</div>\r\n</div>', '1'),
(47, '3', 'A.5.37', 'Documented operating procedures', '<div id="5.37" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Operating procedures for information processing facilities shall be documented and made available to personnel who need them.</p>\r\n</div>\r\n</div>', '1'),
(48, '4', 'A.6.1', 'Screening', '<div id="6.1" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Background verification checks on all candidates to become personnel shall be carried out prior to joining the organization and on an ongoing basis taking into consideration applicable laws, regulations and ethics and be proportional to the business requirements, the classification of the information to be accessed and the perceived risks.</p>\r\n</div>\r\n</div>', '1'),
(49, '4', 'A.6.2', 'Terms and conditions of employment', '<div id="6.2" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The employment contractual agreements shall state the personnel&rsquo;s and the organization&rsquo;s responsibilities for information security.</p>\r\n</div>\r\n</div>', '1'),
(50, '4', 'A.6.3', 'Information security awareness, education and training', '<div id="6.3" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Personnel of the organization and relevant interested parties shall receive appropriate information security awareness, education and training and regular updates of the organization\'s information security policy, topic-specific policies and procedures, as relevant for their job function</p>\r\n</div>\r\n</div>', '1'),
(51, '4', 'A.6.4', 'Disciplinary process', '<div id="6.4" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>A disciplinary process shall be formalized and communicated to take actions against personnel and other relevant interested parties who have committed an information security policy violation.</p>\r\n</div>\r\n</div>', '1'),
(52, '4', 'A.6.5', 'Responsibilities after termination or change of employment', '<div id="6.5" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information security responsibilities and duties that remain valid after termination or change of employment shall be defined, enforced and communicated to relevant personnel and other interested parties.</p>\r\n</div>\r\n</div>', '1'),
(53, '4', 'A.6.6', 'Confidentialy or non-disclosure agreements', '<div id="6.6" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Confidentiality or non-disclosure agreements reflecting the organization&rsquo;s needs for the protection of information shall be identified, documented, regularly reviewed and signed by personnel and other relevant interested parties.</p>\r\n</div>\r\n</div>', '1'),
(54, '4', 'A.6.7', 'Remote Working', '<div id="6.7" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Security measures shall be implemented when personnel are working remotely to protect information accessed, processed or stored outside the organization&rsquo;s premises.</p>\r\n</div>\r\n</div>', '1'),
(55, '4', 'A.6.8', 'Information security event reporting', '<div id="6.8" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall provide a mechanism for personnel to report observed or suspected information security events through appropriate channels in a timely manner</p>\r\n</div>\r\n</div>', '1'),
(56, '5', 'A.7.1', 'Physical security perimeters', '<div id="7.1" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Security perimeters shall be defined and used to protect areas that contain information and other associated assets.</p>\r\n</div>\r\n</div>', '1'),
(57, '5', 'A.7.2', 'Physical entry', '<div id="7.2" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Secure areas shall be protected by appropriate entry controls and access points</p>\r\n</div>\r\n</div>', '1'),
(58, '5', 'A.7.3', 'Securing offices, rooms and facilities', '<div id="7.3" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Physical security for offices, rooms and facilities shall be designed and implemented.</p>\r\n</div>\r\n</div>', '1'),
(59, '5', 'A.7.4', 'Physical security monitoring', '<div id="7.4" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Premises shall be continuously monitored for unauthorized physical access.</p>\r\n</div>\r\n</div>', '1'),
(60, '5', 'A.7.5', 'Protecting against physical and environmental threats', '<div id="7.5" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Protection against physical and environmental threats, such as natural disasters and other intentional or unintentional physical threats to infrastructure shall be designed and implemented.</p>\r\n</div>\r\n</div>', '1'),
(61, '5', 'A.7.6', 'Working in secure areas', '<div id="7.6" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Security measures for working in secure areas shall be designed and implemented</p>\r\n</div>\r\n</div>', '1'),
(62, '5', 'A.7.7', 'Clear desk and clear screen', '<div id="7.7" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Clear desk rules for papers and removable storage media and clear screen rules for information processing facilities shall be defined and appropriately enforced.</p>\r\n</div>\r\n</div>', '1'),
(63, '5', 'A.7.8', 'Equipment siting and protection', '<div id="7.8" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Equipment shall be sited securely and protected.</p>\r\n</div>\r\n</div>', '1'),
(64, '5', 'A.7.9', 'Security of assets off-premises', '<div id="7.9" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Off-site assets shall be protected.</p>\r\n</div>\r\n</div>', '1'),
(65, '5', 'A.7.10', 'Storage media', '<div id="7.10" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Storage media shall be managed through their life cycle of acquisition, use, transportation and disposal in accordance with the organization&rsquo;s classification scheme and handling requirements</p>\r\n</div>\r\n</div>', '1'),
(66, '5', 'A.7.11', 'Supporting utilities', '<div id="7.11" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information processing facilities shall be protected from power failures and other disruptions caused by failures in supporting utilities.</p>\r\n</div>\r\n</div>', '1'),
(67, '5', 'A.7.12', 'Cabling security', '<div id="7.12" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Cables carrying power, data or supporting information services shall be protected from interception, interference or damage.</p>\r\n</div>\r\n</div>', '1'),
(68, '5', 'A.7.13', 'Equipment maintenance', '<div id="7.13" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Equipment shall be maintained correctly to ensure availability, integrity and confidentiality of information.</p>\r\n</div>\r\n</div>', '1'),
(69, '5', 'A.7.14', 'Secure disposal of re-use of equipment', '<div id="7.14" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Items of equipment containing storage media shall be verified to en- sure that any sensitive data and licensed software has been removed or securely overwritten prior to disposal or re-use.</p>\r\n</div>\r\n</div>', '1'),
(70, '7', 'A.8.1', 'User end point devices', '<div id="8.1" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information stored on, processed by or accessible via user end point devices shall be protected.</p>\r\n</div>\r\n</div>', '1'),
(71, '7', 'A.8.2', 'Priviledged Access Rights', '<div id="8.2" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The allocation and use of privileged access rights shall be restricted and managed.</p>\r\n</div>\r\n</div>', '1'),
(72, '7', 'A.8.3', 'Information access restriction', '<div id="8.3" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Access to information and other associated assets shall be restricted in accordance with the established topic-specific policy on access control.</p>\r\n</div>\r\n</div>', '1'),
(73, '7', 'A.8.4', 'Access to source code ', '<div id="8.4" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Read and write access to source code, development tools and software libraries shall be appropriately managed.</p>\r\n</div>\r\n</div>', '1'),
(74, '7', 'A.8.5', 'Secure authentication', '<div id="8.5" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Secure authentication technologies and procedures shall be implemented based on information access restrictions and the topic-specific policy on access control.</p>\r\n</div>\r\n</div>', '1'),
(75, '7', 'A.8.6', 'Capacity management', '<div id="8.6" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The use of resources shall be monitored and adjusted in line with current and expected capacity requirements.</p>\r\n</div>\r\n</div>', '1'),
(76, '7', 'A.8.7', 'Protection against malware', '<div id="8.7" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Protection against malware shall be implemented and supported by appropriate user awareness.</p>\r\n</div>\r\n</div>', '1'),
(77, '7', 'A.8.8', 'Management of technical vulnerabilities', '<div id="8.8" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information about technical vulnerabilities of information systems in use shall be obtained, the organization&rsquo;s exposure to such vulnerabilities shall be evaluated and appropriate measures shall be taken.</p>\r\n</div>\r\n</div>', '1'),
(78, '7', 'A.8.9', 'Configuration management', '<div id="8.9" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Configurations, including security configurations, of hardware, software, services and networks shall be established, documented, implemented, monitored and reviewed.</p>\r\n</div>\r\n</div>', '1'),
(79, '7', 'A.8.10', 'Information deletion', '<div id="8.10" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information stored in information systems, devices or in any other storage media shall be deleted when no longer required.</p>\r\n</div>\r\n</div>', '1'),
(80, '7', 'A.8.11', 'Data masking', '<div id="8.11" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Data masking shall be used in accordance with the organization&rsquo;s topic-specific policy on access control and other related topic-specific policies, and business requirements, taking applicable legislation into consideration.</p>\r\n</div>\r\n</div>', '1'),
(81, '7', 'A.8.12', 'Data leakage prevention', '<div id="8.12" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Data leakage prevention measures shall be applied to systems, net- works and any other devices that process, store or transmit sensitive information.</p>\r\n</div>\r\n</div>', '1'),
(82, '7', 'A.8.13', 'Information backup', '<div id="8.13" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Backup copies of information, software and systems shall be maintained and regularly tested in accordance with the agreed topic-specific policy on backup.</p>\r\n</div>\r\n</div>', '1'),
(83, '7', 'A.8.14', 'Redundancy of information processing facilities', '<div id="8.14" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information processing facilities shall be implemented with redundancy sufficient to meet availability requirements.</p>\r\n</div>\r\n</div>', '1'),
(84, '7', 'A.8.15', 'Logging', '<div id="8.15" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Logs that record activities, exceptions, faults and other relevant events shall be produced, stored, protected and analysed.</p>\r\n</div>\r\n</div>', '1'),
(85, '7', 'A.8.16', 'Monitoring activities', '<div id="8.16" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Networks, systems and applications shall be monitored for anomalous behavior and appropriate actions taken to evaluate potential information security incidents</p>\r\n</div>\r\n</div>', '1'),
(86, '7', 'A.8.17', 'Clock synchronization', '<div id="8.17" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The clocks of information processing systems used by the organization shall be synchronized to approved time sources</p>\r\n</div>\r\n</div>', '1'),
(87, '7', 'A.8.18', 'Use of priviledged utility program', '<div id="8.18" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The use of utility programs that can be capable of overriding system and application controls shall be restricted and tightly controlled.</p>\r\n</div>\r\n</div>', '1'),
(88, '7', 'A.8.19', 'Installation of software on operating systems', '<div id="8.19" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Procedures and measures shall be implemented to securely manage software installation on operational systems.</p>\r\n</div>\r\n</div>', '1'),
(89, '7', 'A.8.20', 'Networks security', '<div id="8.20" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Networks and network devices shall be secured, managed and controlled to protect information in systems and applications.</p>\r\n</div>\r\n</div>', '1'),
(90, '7', 'A.8.21', 'Security of network services', '<div id="8.21" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Security mechanisms, service levels and service requirements of network services shall be identified, implemented and monitored.</p>\r\n</div>\r\n</div>', '1'),
(91, '7', 'A.8.22', 'Segregation of networks', '<div id="8.22" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Groups of information services, users and information systems shall be segregated in the organization&rsquo;s networks</p>\r\n</div>\r\n</div>', '1'),
(92, '7', 'A.8.23', 'Web filtering', '<div id="8.23" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Access to external websites shall be managed to reduce exposure to malicious content.</p>\r\n</div>\r\n</div>', '1'),
(93, '7', 'A.8.24', 'Use of cryptography', '<div id="8.24" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Rules for the effective use of cryptography, including cryptographic key management, shall be defined and implemented.</p>\r\n</div>\r\n</div>', '1'),
(94, '7', 'A.8.25', 'Secure development life cycle', '<div id="8.25" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Rules for the secure development of software and systems shall be established and applied.</p>\r\n</div>\r\n</div>', '1'),
(95, '7', 'A.8.26', 'Application security requirements', '<div id="8.26" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Information security requirements shall be identified, specified and approved when developing or acquiring applications.</p>\r\n</div>\r\n</div>', '1'),
(96, '7', 'A.8.27', 'Secure system architecture and engineering principles ', '<div id="8.27" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Principles for engineering secure systems shall be established, docu- mented, maintained and applied to any information system development activities.</p>\r\n</div>\r\n</div>', '1'),
(97, '7', 'A.8.28', 'Secure coding', '<div id="8.28" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Secure coding principles shall be applied to software development.</p>\r\n</div>\r\n</div>', '1'),
(98, '7', 'A.8.29', 'Security testing in development and acceptance', '<div id="8.29" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Security testing processes shall be defined and implemented in the development life cycle.</p>\r\n</div>\r\n</div>', '1'),
(99, '7', 'A.8.30', 'Outsourced development ', '<div id="8.30" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>The organization shall direct, monitor and review the activities related to outsourced system development.</p>\r\n</div>\r\n</div>', '1'),
(100, '7', 'A.8.31', 'Separation of development, test and production environments', '<div id="8.31" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Development, testing and production environments shall be separated and secured.</p>\r\n</div>\r\n</div>', '1'),
(101, '7', 'A.8.32', 'Change management', '<div id="8.32" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Changes to information processing facilities and information systems shall be subject to change management procedures.</p>\r\n</div>\r\n</div>', '1'),
(102, '7', 'A.8.33', 'Test information', '<p><strong>CONTROL</strong></p>\r\n<p>Control Test information shall be appropriately selected, protected and managed&nbsp;</p>', '1'),
(103, '7', 'A.8.34', 'Protection of information systems during audit testing', '<div id="8.34" data-bs-parent="#accordionExample1">\r\n<div>\r\n<p><strong>CONTROL</strong></p>\r\n<p>Audit tests and other assurance activities involving assessment of op- erational systems shall be planned and agreed between the tester and appropriate management.</p>\r\n</div>\r\n</div>', '1'),
(104, '1', 'Test', 'Test', '<p>Test</p>', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tblca`
--

CREATE TABLE `tblca` (
  `ca_id` int(11) NOT NULL,
  `ca_topic` varchar(255) DEFAULT NULL,
  `ca_description` blob,
  `ca_description_status` varchar(11) DEFAULT NULL,
  `ca_status` varchar(100) DEFAULT NULL,
  `ca_form_status` varchar(10) DEFAULT NULL,
  `ca_assigned_to` varchar(100) DEFAULT NULL,
  `ca_created_by` varchar(100) DEFAULT NULL,
  `ca_updated_by` varchar(100) DEFAULT NULL,
  `ca_updated_date` varchar(100) DEFAULT NULL,
  `ca_financial_value` varchar(100) DEFAULT NULL,
  `ca_source` varchar(100) DEFAULT NULL,
  `ca_severity` varchar(100) DEFAULT NULL,
  `ca_created_date` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblca`
--

INSERT INTO `tblca` (`ca_id`, `ca_topic`, `ca_description`, `ca_description_status`, `ca_status`, `ca_form_status`, `ca_assigned_to`, `ca_created_by`, `ca_updated_by`, `ca_updated_date`, `ca_financial_value`, `ca_source`, `ca_severity`, `ca_created_date`) VALUES
(2, 'Test Topic for the corrective actions & improvement section', NULL, NULL, NULL, NULL, NULL, 'Siddharth Asthana', NULL, NULL, NULL, NULL, NULL, '04-14-2025 17:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `tblca_comment`
--

CREATE TABLE `tblca_comment` (
  `ca_comment_id` int(11) NOT NULL,
  `ca_comment_parent_id` varchar(100) DEFAULT NULL,
  `ca_comment_data` blob NOT NULL,
  `ca_comment_by` varchar(100) DEFAULT NULL,
  `ca_comment_date` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `training_id` int(11) NOT NULL,
  `training_topic` varchar(255) DEFAULT NULL,
  `training_details` blob,
  `training_details_status` varchar(11) DEFAULT NULL,
  `training_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `training_created_by` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`training_id`, `training_topic`, `training_details`, `training_details_status`, `training_date`, `training_created_by`) VALUES
(3, 'Information Security Meeting - Implementation Review Meeting 1', 0x3c703e54657374204e657720436865636b3c2f703e, '2', '2025-03-21 13:47:48', 'Siddharth Asthana'),
(4, 'Information Security Meeting - Implementation Review Meeting 2', NULL, NULL, '2025-04-15 15:54:07', 'Siddharth Asthana');

-- --------------------------------------------------------

--
-- Table structure for table `training_comment`
--

CREATE TABLE `training_comment` (
  `training_comment_id` int(11) NOT NULL,
  `training_comment_parent_id` varchar(100) DEFAULT NULL,
  `training_comment_data` blob,
  `training_comment_by` varchar(100) DEFAULT NULL,
  `training_comment_datetime` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `isms_user_id` int(11) NOT NULL,
  `isms_user_name` varchar(100) NOT NULL,
  `isms_user_email` varchar(100) NOT NULL,
  `isms_user_password` varchar(100) NOT NULL,
  `isms_user_role` varchar(15) NOT NULL,
  `isms_user_last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isms_user_creation_dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`isms_user_id`, `isms_user_name`, `isms_user_email`, `isms_user_password`, `isms_user_role`, `isms_user_last_login`, `isms_user_creation_dt`) VALUES
(1, 'Siddharth Asthana', 'asthana@in3corp.com', '$2a$10$Nt6piL0A1bUGE5/Zd9Wtv.7Q1vFSTBGoYrgI3Vu1jFV6IzkLL6ouu', '1', '2025-04-22 13:12:50', '2025-02-26 14:43:24'),
(4, 'Manish Kumar', 'kumar@in3corp.com', '$2y$10$aZUj6X4sk7QZ.osMIdW.Zu66Z4y7YG0h4h60eyG7Bx9Bobrbt68lW', '1', '2025-03-07 15:55:51', '2025-03-07 15:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_log`
--

CREATE TABLE `user_activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_description` varchar(255) NOT NULL,
  `activity_screen` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `version_control`
--

CREATE TABLE `version_control` (
  `vc_id` int(11) NOT NULL,
  `vc_data_id` varchar(100) DEFAULT NULL,
  `vc_screen_name` varchar(100) DEFAULT NULL,
  `vc_assigned_to` varchar(100) DEFAULT NULL,
  `vc_status` varchar(100) DEFAULT NULL,
  `vc_updated_on` varchar(100) DEFAULT NULL,
  `vc_updated_by` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `version_control`
--

INSERT INTO `version_control` (`vc_id`, `vc_data_id`, `vc_screen_name`, `vc_assigned_to`, `vc_status`, `vc_updated_on`, `vc_updated_by`) VALUES
(1, '1', 'Policy Details', 'Siddharth Asthana', 'Open', '04-23-2025 14:20:10', 'Siddharth Asthana');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `asset`
--
ALTER TABLE `asset`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `asset_comment`
--
ALTER TABLE `asset_comment`
  ADD PRIMARY KEY (`asset_comment_id`);

--
-- Indexes for table `audit_program`
--
ALTER TABLE `audit_program`
  ADD PRIMARY KEY (`ap_id`);

--
-- Indexes for table `controls`
--
ALTER TABLE `controls`
  ADD PRIMARY KEY (`control_id`);

--
-- Indexes for table `control_history`
--
ALTER TABLE `control_history`
  ADD PRIMARY KEY (`ctrl_h_id`);

--
-- Indexes for table `inner_linked_control_policy`
--
ALTER TABLE `inner_linked_control_policy`
  ADD PRIMARY KEY (`inner_linked_control_policy_id`);

--
-- Indexes for table `linked_control_policy`
--
ALTER TABLE `linked_control_policy`
  ADD PRIMARY KEY (`linked_control_policy_id`);

--
-- Indexes for table `mrb`
--
ALTER TABLE `mrb`
  ADD PRIMARY KEY (`mrb_id`);

--
-- Indexes for table `mrb_activities`
--
ALTER TABLE `mrb_activities`
  ADD PRIMARY KEY (`mrb_act_id`),
  ADD KEY `mrb_act_deliverable_id` (`mrb_act_deliverable_id`);

--
-- Indexes for table `mrb_deliverables`
--
ALTER TABLE `mrb_deliverables`
  ADD PRIMARY KEY (`mrb_del_id`),
  ADD KEY `mrb_del_board_id` (`mrb_del_board_id`);

--
-- Indexes for table `policy`
--
ALTER TABLE `policy`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `policy_details`
--
ALTER TABLE `policy_details`
  ADD PRIMARY KEY (`policy_details_id`);

--
-- Indexes for table `policy_details_history`
--
ALTER TABLE `policy_details_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `policy_documents`
--
ALTER TABLE `policy_documents`
  ADD PRIMARY KEY (`policy_document_id`),
  ADD KEY `policy_id` (`policy_id`);

--
-- Indexes for table `remote_user`
--
ALTER TABLE `remote_user`
  ADD PRIMARY KEY (`ru_id`);

--
-- Indexes for table `risks`
--
ALTER TABLE `risks`
  ADD PRIMARY KEY (`risks_id`);

--
-- Indexes for table `risk_policies`
--
ALTER TABLE `risk_policies`
  ADD PRIMARY KEY (`risk_policy_id`),
  ADD KEY `risks_id` (`risks_id`);

--
-- Indexes for table `sim`
--
ALTER TABLE `sim`
  ADD PRIMARY KEY (`sim_id`);

--
-- Indexes for table `sim_comment`
--
ALTER TABLE `sim_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `sub_control_policy`
--
ALTER TABLE `sub_control_policy`
  ADD PRIMARY KEY (`sub_control_policy_id`);

--
-- Indexes for table `tblca`
--
ALTER TABLE `tblca`
  ADD PRIMARY KEY (`ca_id`);

--
-- Indexes for table `tblca_comment`
--
ALTER TABLE `tblca_comment`
  ADD PRIMARY KEY (`ca_comment_id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`training_id`);

--
-- Indexes for table `training_comment`
--
ALTER TABLE `training_comment`
  ADD PRIMARY KEY (`training_comment_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`isms_user_id`);

--
-- Indexes for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `version_control`
--
ALTER TABLE `version_control`
  ADD PRIMARY KEY (`vc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `asset`
--
ALTER TABLE `asset`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `asset_comment`
--
ALTER TABLE `asset_comment`
  MODIFY `asset_comment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `audit_program`
--
ALTER TABLE `audit_program`
  MODIFY `ap_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `controls`
--
ALTER TABLE `controls`
  MODIFY `control_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `control_history`
--
ALTER TABLE `control_history`
  MODIFY `ctrl_h_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inner_linked_control_policy`
--
ALTER TABLE `inner_linked_control_policy`
  MODIFY `inner_linked_control_policy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `linked_control_policy`
--
ALTER TABLE `linked_control_policy`
  MODIFY `linked_control_policy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `mrb`
--
ALTER TABLE `mrb`
  MODIFY `mrb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `mrb_activities`
--
ALTER TABLE `mrb_activities`
  MODIFY `mrb_act_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `mrb_deliverables`
--
ALTER TABLE `mrb_deliverables`
  MODIFY `mrb_del_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `policy`
--
ALTER TABLE `policy`
  MODIFY `policy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `policy_details`
--
ALTER TABLE `policy_details`
  MODIFY `policy_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `policy_details_history`
--
ALTER TABLE `policy_details_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `policy_documents`
--
ALTER TABLE `policy_documents`
  MODIFY `policy_document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `remote_user`
--
ALTER TABLE `remote_user`
  MODIFY `ru_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `risks`
--
ALTER TABLE `risks`
  MODIFY `risks_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `risk_policies`
--
ALTER TABLE `risk_policies`
  MODIFY `risk_policy_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sim`
--
ALTER TABLE `sim`
  MODIFY `sim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sim_comment`
--
ALTER TABLE `sim_comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `sub_control_policy`
--
ALTER TABLE `sub_control_policy`
  MODIFY `sub_control_policy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
--
-- AUTO_INCREMENT for table `tblca`
--
ALTER TABLE `tblca`
  MODIFY `ca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tblca_comment`
--
ALTER TABLE `tblca_comment`
  MODIFY `ca_comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `training_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `training_comment`
--
ALTER TABLE `training_comment`
  MODIFY `training_comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `isms_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `version_control`
--
ALTER TABLE `version_control`
  MODIFY `vc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
