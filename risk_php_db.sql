-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2025 at 09:39 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `risk_php_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `actionplan`
--

CREATE TABLE `actionplan` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` varchar(255) NOT NULL,
  `description` text,
  `dueDate` datetime DEFAULT NULL,
  `riskId` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `processId` int(11) NOT NULL,
  `parentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `createdAt`, `updatedAt`, `name`, `code`, `description`, `processId`, `parentId`) VALUES
(1, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Planification des Achats', 'ACT001', 'Planification des activités d\'achat', 1, NULL),
(2, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Sélection des Fournisseurs', 'ACT002', 'Sélection et évaluation des fournisseurs', 1, NULL),
(3, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Gestion des Contrats', 'ACT003', 'Gestion des contrats et accords', 1, NULL),
(4, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Exécution des Achats', 'ACT004', 'Exécution des bons de commande', 1, NULL),
(5, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Contrôle Qualité', 'ACT005', 'Contrôle qualité et inspection', 1, NULL),
(6, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Traitement des Paiements', 'ACT006', 'Traitement des paiements et factures', 1, NULL),
(7, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Gestion des Stocks', 'ACT007', 'Gestion de l\'inventaire et des stocks', 1, NULL),
(8, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Relations Fournisseurs', 'ACT008', 'Gestion des relations fournisseurs', 1, NULL),
(9, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Suivi de la Conformité', 'ACT009', 'Surveillance de la conformité réglementaire', 1, NULL),
(10, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Évaluation des Risques', 'ACT010', 'Évaluation des risques d\'achat', 1, NULL),
(11, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Contrôle Budgétaire', 'ACT011', 'Contrôle du budget des achats', 1, NULL),
(12, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Documentation', 'ACT012', 'Gestion de la documentation des achats', 1, NULL),
(13, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Processus d\'Approbation', 'ACT013', 'Gestion des flux d\'approbation', 1, NULL),
(14, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Suivi des Performances', 'ACT014', 'Suivi des performances des achats', 1, NULL),
(15, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Support d\'Audit', 'ACT015', 'Support des activités d\'audit', 1, NULL),
(16, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Formation et Développement', 'ACT016', 'Formation du personnel des achats', 1, NULL),
(17, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Gestion Technologique', 'ACT017', 'Gestion de la technologie des achats', 1, NULL),
(18, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Reporting', 'ACT018', 'Génération des rapports d\'achat', 1, NULL),
(19, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Gestion des Exceptions', 'ACT019', 'Traitement des exceptions d\'achat', 1, NULL),
(20, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Amélioration Continue', 'ACT020', 'Amélioration des processus d\'achat', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auditlog`
--

CREATE TABLE `auditlog` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userId` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `entity` varchar(255) NOT NULL,
  `entityId` int(11) DEFAULT NULL,
  `description` text,
  `ipAddress` varchar(45) DEFAULT NULL,
  `userAgent` text,
  `oldValues` json DEFAULT NULL,
  `newValues` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clientprofile`
--

CREATE TABLE `clientprofile` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `denomination` varchar(255) NOT NULL,
  `judicial` varchar(255) NOT NULL,
  `sector` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `config` enum('NORMAL','COEFFIECCIENT','BASIC') NOT NULL,
  `appetencyActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clientprofile`
--

INSERT INTO `clientprofile` (`id`, `createdAt`, `updatedAt`, `denomination`, `judicial`, `sector`, `code`, `config`, `appetencyActive`) VALUES
(1, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Client Principal', 'SARL', 'Technologie', 'CLI001', 'NORMAL', 1);

-- --------------------------------------------------------

--
-- Table structure for table `control_types`
--

CREATE TABLE `control_types` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `effectiveness` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `control_types`
--

INSERT INTO `control_types` (`id`, `type`, `effectiveness`) VALUES
(1, 'Préventif', 85),
(2, 'Détectif', 78),
(3, 'Correctif', 92),
(4, 'Compensatoire', 67);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `domaine`
--

CREATE TABLE `domaine` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `projectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `domaine`
--

INSERT INTO `domaine` (`id`, `createdAt`, `updatedAt`, `code`, `name`, `projectId`) VALUES
(1, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'DOM001', 'Domaine des Achats', 1);

-- --------------------------------------------------------

--
-- Table structure for table `entity`
--

CREATE TABLE `entity` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `projectId` int(11) NOT NULL,
  `parentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `entity`
--

INSERT INTO `entity` (`id`, `createdAt`, `updatedAt`, `name`, `code`, `projectId`, `parentId`) VALUES
(1, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Département des Achats', 'ENT001', 1, NULL),
(2, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Département Financier', 'ENT002', 1, NULL),
(3, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Département Juridique', 'ENT003', 1, NULL),
(4, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Département des Opérations', 'ENT004', 1, NULL),
(5, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Département Informatique', 'ENT005', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mitigation_status`
--

CREATE TABLE `mitigation_status` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mitigation_status`
--

INSERT INTO `mitigation_status` (`id`, `status`, `count`) VALUES
(1, 'Entièrement Atténué', 18),
(2, 'Partiellement Atténué', 22),
(3, 'En Révision', 8),
(4, 'Non Atténué', 7),
(5, 'Entièrement Atténué', 18),
(6, 'Partiellement Atténué', 22),
(7, 'En Révision', 8),
(8, 'Non Atténué', 7);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_downloads`
--

CREATE TABLE `monthly_downloads` (
  `id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `monthly_downloads`
--

INSERT INTO `monthly_downloads` (`id`, `month`, `downloads`) VALUES
(1, 'January', 45),
(2, 'February', 52),
(3, 'March', 38),
(4, 'April', 67),
(5, 'May', 73),
(6, 'June', 89);

-- --------------------------------------------------------

--
-- Table structure for table `operationalobjectif`
--

CREATE TABLE `operationalobjectif` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `strategicObjectiveId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `performanceindicator`
--

CREATE TABLE `performanceindicator` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `currentValue` decimal(10,2) NOT NULL,
  `targetValue` decimal(10,2) NOT NULL,
  `projectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

CREATE TABLE `process` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `domaineId` int(11) NOT NULL,
  `parentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `process`
--

INSERT INTO `process` (`id`, `createdAt`, `updatedAt`, `name`, `code`, `description`, `domaineId`, `parentId`) VALUES
(1, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Processus d\'Achats', 'PROC001', 'Processus principal d\'achats et d\'approvisionnement', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `clientId` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `createdAt`, `updatedAt`, `name`, `code`, `clientId`, `active`) VALUES
(1, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Projet de Gestion des Risques', 'PROJ001', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_risks`
--

CREATE TABLE `project_risks` (
  `id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `risks` int(11) NOT NULL DEFAULT '0',
  `high_priority` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project_risks`
--

INSERT INTO `project_risks` (`id`, `project_name`, `risks`, `high_priority`) VALUES
(1, 'Customer Portal', 12, 3),
(2, 'Mobile Banking', 8, 2),
(3, 'ERP Implementation', 15, 4),
(4, 'E-commerce Platform', 10, 2),
(5, 'Cloud Migration', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recent_activities`
--

CREATE TABLE `recent_activities` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `timestamp` datetime NOT NULL,
  `severity` varchar(20) NOT NULL,
  `user` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recent_activities`
--

INSERT INTO `recent_activities` (`id`, `type`, `title`, `description`, `timestamp`, `severity`, `user`) VALUES
(1, 'risk_created', 'New Risk Identified', 'Data Security Vulnerability in Customer Portal', '2025-06-15 14:30:00', 'high', 'John Doe'),
(2, 'control_updated', 'Control Effectiveness Updated', 'Authentication controls effectiveness increased to 95%', '2025-06-15 11:15:00', 'medium', 'Jane Smith'),
(3, 'report_generated', 'Monthly Report Generated', 'Risk Assessment Report for May 2025 completed', '2025-06-14 16:45:00', 'low', 'System'),
(4, 'risk_mitigated', 'Risk Successfully Mitigated', 'Budget overrun risk in Mobile Banking project resolved', '2025-06-14 09:20:00', 'medium', 'Mike Johnson'),
(5, 'audit_completed', 'Compliance Audit Completed', 'Q2 2025 compliance audit finished with 98% score', '2025-06-13 17:00:00', 'low', 'External Auditor');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `type` enum('Risk Assessment','Compliance','Financial','Security','Executive') NOT NULL,
  `status` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `description` text,
  `format` varchar(50) NOT NULL DEFAULT 'PDF',
  `file_path` varchar(255) DEFAULT NULL,
  `file_size` varchar(20) DEFAULT NULL,
  `download_count` int(11) DEFAULT '0',
  `generated_by` varchar(255) DEFAULT NULL,
  `entityId` int(11) DEFAULT NULL,
  `projectId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `createdAt`, `updatedAt`, `name`, `type`, `status`, `description`, `format`, `file_path`, `file_size`, `download_count`, `generated_by`, `entityId`, `projectId`) VALUES
(1, '2025-06-17 17:04:27', '2025-06-17 17:19:24', 'Évaluation des Risques Q2 2025', 'Risk Assessment', 'Published', 'Rapport trimestriel d\'évaluation des risques', 'PDF', NULL, '2.5 MB', 1, 'Système', NULL, NULL),
(2, '2025-06-17 17:04:27', '2025-06-17 17:04:27', 'Rapport de Conformité Annuel', 'Compliance', 'Published', 'Revue annuelle de la conformité réglementaire', 'PDF', NULL, '3.1 MB', 0, 'Système', NULL, NULL),
(3, '2025-06-17 17:04:27', '2025-06-17 17:04:27', 'Analyse Financière', 'Financial', 'Draft', 'Analyse des impacts financiers des risques', 'PDF', NULL, '1.8 MB', 0, 'Système', NULL, NULL),
(4, '2025-06-17 17:04:27', '2025-06-17 17:04:27', 'Audit de Sécurité', 'Security', 'Published', 'Évaluation de la sécurité des systèmes', 'PDF', NULL, '4.2 MB', 0, 'Système', NULL, NULL),
(5, '2025-06-17 17:04:27', '2025-06-17 17:04:27', 'Rapport Exécutif', 'Executive', 'Published', 'Synthèse pour la direction', 'PDF', NULL, '1.5 MB', 0, 'Système', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reportdownload`
--

CREATE TABLE `reportdownload` (
  `id` int(11) NOT NULL,
  `reportId` int(11) NOT NULL,
  `downloadedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `downloadedBy` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reportdownload`
--

INSERT INTO `reportdownload` (`id`, `reportId`, `downloadedAt`, `downloadedBy`) VALUES
(1, 1, '2025-06-17 17:04:27', 'Admin'),
(2, 1, '2025-06-17 17:04:27', 'User1'),
(3, 2, '2025-06-17 17:04:27', 'Admin'),
(4, 4, '2025-06-17 17:04:27', 'User2'),
(5, 5, '2025-06-17 17:04:27', 'Admin'),
(6, 1, '2025-06-17 17:19:24', 'Anonymous');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `generated_by` varchar(50) NOT NULL,
  `generated_at` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `file_size` varchar(20) NOT NULL,
  `download_count` int(11) NOT NULL DEFAULT '0',
  `format` varchar(20) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `name`, `type`, `description`, `project_name`, `generated_by`, `generated_at`, `status`, `file_size`, `download_count`, `format`, `category`) VALUES
(1, 'Monthly Risk Assessment Report', 'Risk Assessment', 'Comprehensive monthly analysis of all identified risks and their current status', 'Customer Portal Redesign', 'Risk Manager', '2025-06-01', 'Published', '2.4 MB', 15, 'PDF', 'Operational'),
(2, 'Control Effectiveness Review', 'Control Assessment', 'Quarterly review of control effectiveness and recommendations for improvement', 'Mobile Banking App', 'Compliance Officer', '2025-05-15', 'Published', '1.8 MB', 8, 'PDF', 'Compliance'),
(3, 'Project Risk Dashboard', 'Dashboard', 'Real-time dashboard showing current risk status across all active projects', 'All Projects', 'System', '2025-06-13', 'Live', 'N/A', 45, 'Interactive', 'Executive'),
(4, 'Compliance Audit Report', 'Compliance', 'Annual compliance audit findings and regulatory adherence assessment', 'ERP System Implementation', 'External Auditor', '2025-04-30', 'Published', '5.2 MB', 22, 'PDF', 'Regulatory'),
(5, 'Incident Response Summary', 'Incident Report', 'Summary of security incidents and response actions taken in Q2 2025', 'Security Audit System', 'Security Team', '2025-06-10', 'Draft', '1.1 MB', 3, 'PDF', 'Security'),
(6, 'Budget vs Risk Analysis', 'Financial', 'Analysis of risk mitigation costs versus potential financial impact', 'E-commerce Platform', 'Financial Analyst', '2025-05-28', 'Published', '3.1 MB', 12, 'Excel', 'Financial'),
(7, 'Risk Trend Analysis', 'Analytics', 'Quarterly analysis of risk trends and patterns across all business units', 'All Projects', 'Data Analyst', '2025-06-05', 'Published', '4.2 MB', 18, 'PDF', 'Strategic'),
(8, 'Executive Risk Summary', 'Executive Summary', 'High-level overview of key risks and mitigation strategies for executive review', 'All Projects', 'Chief Risk Officer', '2025-06-12', 'Published', '0.8 MB', 35, 'PDF', 'Executive');

-- --------------------------------------------------------

--
-- Table structure for table `report_types`
--

CREATE TABLE `report_types` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report_types`
--

INSERT INTO `report_types` (`id`, `type`, `count`) VALUES
(1, 'Risk Assessment', 3),
(2, 'Compliance', 2),
(3, 'Financial', 1),
(4, 'Security', 1),
(5, 'Executive', 1);

-- --------------------------------------------------------

--
-- Table structure for table `risk`
--

CREATE TABLE `risk` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `cause` text NOT NULL,
  `details` text,
  `frequency` int(11) NOT NULL,
  `existantDb` text,
  `financialImpact` int(11) NOT NULL,
  `legalImpact` int(11) NOT NULL,
  `reputationImpact` int(11) NOT NULL,
  `activityImpact` int(11) NOT NULL,
  `peopleImpact` int(11) NOT NULL,
  `brutCriticality` int(11) NOT NULL,
  `evaluation` int(11) NOT NULL,
  `netCriticality` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `activityId` int(11) NOT NULL,
  `entityId` int(11) NOT NULL,
  `operationalObjectiveId` int(11) DEFAULT NULL,
  `strategicObjectiveId` int(11) DEFAULT NULL,
  `riskFamilyId` int(11) DEFAULT NULL,
  `riskScore` int(11) GENERATED ALWAYS AS ((`frequency` * `brutCriticality`)) STORED,
  `residualScore` int(11) GENERATED ALWAYS AS ((`evaluation` * `netCriticality`)) STORED,
  `lastReviewDate` date DEFAULT NULL,
  `nextReviewDate` date DEFAULT NULL,
  `reviewFrequency` enum('weekly','monthly','quarterly','biannually','annually') DEFAULT 'annually'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `risk`
--

INSERT INTO `risk` (`id`, `createdAt`, `updatedAt`, `name`, `code`, `description`, `cause`, `details`, `frequency`, `existantDb`, `financialImpact`, `legalImpact`, `reputationImpact`, `activityImpact`, `peopleImpact`, `brutCriticality`, `evaluation`, `netCriticality`, `active`, `activityId`, `entityId`, `operationalObjectiveId`, `strategicObjectiveId`, `riskFamilyId`, `lastReviewDate`, `nextReviewDate`, `reviewFrequency`) VALUES
(1, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence d\'une procédure des achats et d\'approvisionnement', 'PROC-001', 'Manque de procédure formalisée pour les achats et l\'approvisionnement, entraînant des irrégularités', 'Défaut de gouvernance des processus achats', NULL, 4, NULL, 4, 5, 3, 5, 2, 5, 2, 3, 1, 7, 3, NULL, NULL, NULL, '2024-03-15', '2025-03-15', 'annually'),
(2, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence d\'une cartographie des achats', 'MAP-002', 'Manque de vision globale des flux d\'achats et des fournisseurs critiques', 'Non-identification des catégories d\'achats stratégiques', NULL, 3, NULL, 3, 2, 2, 4, 1, 4, 3, 2, 1, 7, 4, NULL, NULL, NULL, '2024-04-22', '2025-04-22', 'quarterly'),
(3, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence d\'un système de pilotage spécifique aux achats', 'CTRL-003', 'Défaut de suivi des indicateurs clés de performance achats', 'Manque d\'outils dédiés de reporting', NULL, 4, NULL, 4, 3, 3, 5, 2, 5, 1, 4, 1, 8, 2, NULL, NULL, NULL, '2024-01-30', '2024-07-30', 'biannually'),
(4, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence de système d\'information', 'IT-004', 'Manque de plateforme centralisée pour la gestion des achats', 'Investissements technologiques insuffisants', NULL, 5, NULL, 5, 4, 4, 5, 3, 5, 2, 4, 1, 9, 5, NULL, NULL, NULL, '2024-05-10', '2025-05-10', 'annually'),
(5, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence d\'une procédure de classement et d\'archivage', 'ARCH-005', 'Désorganisation des documents contractuels et risque de perte', 'Absence de politique de gestion documentaire', NULL, 3, NULL, 2, 4, 2, 4, 1, 4, 3, 2, 1, 5, 2, NULL, NULL, NULL, '2024-06-18', '2024-12-18', 'biannually'),
(6, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Incompétence des acteurs intervenant dans le processus', 'COMP-006', 'Manque de compétences nécessaires chez les responsables achats', 'Défaut de formation et de certification', NULL, 4, NULL, 3, 4, 4, 4, 5, 5, 2, 4, 1, 12, 3, NULL, NULL, NULL, '2024-02-14', '2025-02-14', 'annually'),
(7, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence de séparation des tâches', 'SEG-007', 'Concentration des pouvoirs d\'achat chez une seule personne', 'Structure organisationnelle déficiente', NULL, 3, NULL, 4, 5, 3, 4, 2, 5, 3, 3, 1, 6, 4, NULL, NULL, NULL, '2024-03-25', '2024-09-25', 'quarterly'),
(8, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence de mécanismes de prévision des achats', 'PREV-008', 'Incapacité à anticiper les besoins d\'approvisionnement', 'Manque de coordination avec les services utilisateurs', NULL, 4, NULL, 4, 2, 2, 5, 1, 5, 2, 4, 1, 8, 1, NULL, NULL, NULL, '2024-01-10', '2025-01-10', 'annually'),
(9, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Expression des besoins non formalisée', 'BES-009', 'Demandes d\'achat incomplètes ou imprécises', 'Absence de formulaires standardisés', NULL, 5, NULL, 3, 3, 2, 4, 1, 4, 4, 1, 1, 10, 3, NULL, NULL, NULL, '2024-05-05', '2024-11-05', 'biannually'),
(10, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Inadaptation ou inexactitude des besoins détectés par rapport aux besoins réels', 'ADAPT-010', 'Écart entre les besoins exprimés et les nécessités opérationnelles', 'Manque d\'analyse des besoins', NULL, 4, NULL, 4, 3, 3, 5, 2, 5, 3, 3, 1, 11, 2, NULL, NULL, NULL, '2024-04-30', '2025-04-30', 'annually'),
(11, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Rédaction et/ou validation des documents du marché par une personne non-habilitée', 'HAB-011', 'Documents contractuels préparés sans autorisation appropriée', 'Défaut de contrôle des habilitations', NULL, 2, NULL, 3, 5, 4, 3, 3, 5, 4, 2, 1, 7, 4, NULL, NULL, NULL, '2024-03-12', '2025-03-12', 'annually'),
(12, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non-adéquation entre le besoin exprimé et la nomenclature des prestations', 'NOM-012', 'Incompatibilité entre la commande et les spécifications techniques', 'Erreur dans le référencement des articles', NULL, 3, NULL, 4, 3, 3, 4, 1, 4, 3, 2, 1, 9, 1, NULL, NULL, NULL, '2024-02-28', '2024-08-28', 'quarterly'),
(13, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non disponibilité du crédit budgétaire', 'BUDG-013', 'Engagements financiers sans couverture budgétaire', 'Défaut de planification financière', NULL, 3, NULL, 5, 4, 4, 5, 2, 5, 2, 4, 1, 3, 5, NULL, NULL, NULL, '2024-01-20', '2025-01-20', 'annually'),
(14, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Choix d\'un type de marché inapproprié aux besoins ou à l\'objet de la prestation', 'TYPE-014', 'Sélection d\'un cadre contractuel non adapté', 'Méconnaissance des types de marchés', NULL, 2, NULL, 4, 4, 3, 4, 1, 4, 3, 2, 1, 4, 3, NULL, NULL, NULL, '2024-05-15', '2024-11-15', 'biannually'),
(15, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Détermination d\'une estimation des prix inappropriée par rapport à l\'objet de la prestation', 'PRIX-015', 'Évaluation financière erronée des projets', 'Absence de référentiel de prix', NULL, 3, NULL, 5, 3, 3, 4, 1, 5, 2, 4, 1, 5, 2, NULL, NULL, NULL, '2024-04-05', '2025-04-05', 'annually'),
(16, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non-respect des conditions de choix de la procédure de passation du marché', 'PASS-016', 'Utilisation de modes de passation non conformes', 'Méconnaissance des règles de passation', NULL, 2, NULL, 3, 5, 4, 3, 2, 5, 4, 2, 1, 6, 4, NULL, NULL, NULL, '2024-03-08', '2025-03-08', 'annually'),
(17, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non-respect des formes et délais de publicité', 'PUB-017', 'Manquements aux obligations légales de publicité', 'Défaut de suivi du calendrier', NULL, 3, NULL, 2, 5, 4, 3, 1, 5, 3, 3, 1, 7, 3, NULL, NULL, NULL, '2024-02-15', '2024-08-15', 'quarterly'),
(18, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Examen et jugement des offres par une commission non habilitée', 'COMM-018', 'Décisions d\'attribution prises par des personnes non autorisées', 'Absence de validation des membres de commission', NULL, 2, NULL, 3, 5, 4, 4, 2, 5, 4, 2, 1, 8, 2, NULL, NULL, NULL, '2024-01-25', '2025-01-25', 'annually'),
(19, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non application des critères de selection intialement retenu', 'CRIT-019', 'Déviation des critères d\'évaluation pendant l\'analyse', 'Pression hiérarchique ou manque d\'objectivité', NULL, 3, NULL, 4, 4, 4, 4, 3, 4, 2, 3, 1, 9, 1, NULL, NULL, NULL, '2024-06-01', '2024-12-01', 'biannually'),
(20, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Méthode indapatée de notation, de classement des offres ou de pondérations des critères', 'NOT-020', 'Système d\'évaluation biaisé ou inéquitable', 'Défaut de formation des évaluateurs', NULL, 3, NULL, 3, 4, 4, 4, 2, 4, 3, 2, 1, 10, 3, NULL, NULL, NULL, '2024-04-18', '2025-04-18', 'annually'),
(21, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non-respect des conditions de maintien de l\'offre anormalement basse', 'BAS-021', 'Acceptation d\'offres non viables financièrement', 'Pression pour réduire les coûts à court terme', NULL, 2, NULL, 5, 4, 3, 4, 2, 5, 4, 2, 1, 11, 4, NULL, NULL, NULL, '2024-03-03', '2025-03-03', 'annually'),
(22, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non-respect des délais et conditions d\'approbation du marché', 'APP-022', 'Retards dans les validations contractuelles', 'Circuit de validation trop complexe', NULL, 4, NULL, 3, 4, 3, 4, 1, 4, 2, 3, 1, 12, 2, NULL, NULL, NULL, '2024-02-22', '2024-08-22', 'quarterly'),
(23, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Engagement non établi ou non conforme au marché', 'ENG-023', 'Début des prestations sans engagement formel', 'Urgence opérationnelle mal gérée', NULL, 3, NULL, 4, 5, 4, 4, 2, 5, 3, 3, 1, 13, 3, NULL, NULL, NULL, '2024-01-12', '2025-01-12', 'annually'),
(24, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non-respect des délais et conditions de notification du marché', 'NOTIF-024', 'Retards dans les notifications officielles aux soumissionnaires', 'Processus manuel et dépendant de validations multiples', NULL, 3, NULL, 2, 5, 4, 3, 1, 5, 3, 3, 1, 12, 3, NULL, NULL, NULL, '2024-06-05', '2024-12-05', 'biannually'),
(25, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Retard dans l\'exécution, inexécution ou résiliation du marché', 'EXEC-025', 'Prestations non réalisées selon le calendrier contractuel', 'Défaut de suivi des fournisseurs', NULL, 4, NULL, 4, 4, 5, 5, 3, 5, 2, 4, 1, 14, 4, NULL, NULL, NULL, '2024-03-17', '2025-03-17', 'annually'),
(26, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non-respect des termes du marché dans l\'exécution des prestations', 'TERM-026', 'Écarts par rapport aux spécifications contractuelles', 'Défaut de contrôle qualité', NULL, 4, NULL, 4, 4, 4, 5, 2, 5, 3, 3, 1, 15, 2, NULL, NULL, NULL, '2024-04-25', '2025-04-25', 'annually'),
(27, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Retard de règlement entrainant des intérêts moratoires', 'PAY-027', 'Pénalités financières dues à des retards de paiement', 'Processus de paiement inefficace', NULL, 5, NULL, 5, 4, 4, 3, 1, 5, 2, 4, 1, 16, 5, NULL, NULL, NULL, '2024-02-10', '2024-08-10', 'quarterly'),
(28, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Dépassement du montant initial du marché', 'COST-028', 'Dépassements budgétaires non autorisés sur les contrats', 'Défaut de contrôle des engagements et variations', NULL, 2, NULL, 5, 4, 3, 4, 2, 5, 4, 3, 1, 15, 4, NULL, NULL, NULL, '2024-02-18', '2025-02-18', 'annually'),
(29, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence ou erreur dans le calcul des pénalités de retard', 'PEN-029', 'Manquement à l\'application des clauses pénales contractuelles', 'Défaut de suivi des délais d\'exécution', NULL, 3, NULL, 4, 4, 3, 3, 1, 4, 3, 2, 1, 17, 3, NULL, NULL, NULL, '2024-05-22', '2024-11-22', 'biannually'),
(30, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Réception non conforme', 'REC-030', 'Acceptation de prestations non conformes aux spécifications', 'Manque d\'expertise technique des réceptionnaires', NULL, 4, NULL, 4, 3, 4, 5, 2, 5, 2, 4, 1, 18, 2, NULL, NULL, NULL, '2024-03-28', '2025-03-28', 'annually'),
(31, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Non réception ou réception tardive d\'une facture', 'FACT-031', 'Délais anormaux dans le traitement comptable', 'Défaillance dans le circuit des factures', NULL, 4, NULL, 4, 2, 2, 3, 1, 4, 3, 2, 1, 19, 1, NULL, NULL, NULL, '2024-04-08', '2024-10-08', 'quarterly'),
(32, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Défaut d\'enregistrement de la date d\'arrivée des factures', 'DATE-032', 'Impossibilité de prouver les délais de traitement', 'Processus manuel non tracé', NULL, 5, NULL, 2, 3, 2, 3, 1, 3, 4, 1, 1, 20, 3, NULL, NULL, NULL, '2024-01-15', '2024-07-15', 'biannually'),
(33, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Double règlement d\'une facture ou dépassement du montant d\'une facture', 'DUPL-033', 'Erreurs de paiement entraînant des pertes financières', 'Défaut de rapprochement comptable', NULL, 2, NULL, 5, 4, 5, 3, 2, 5, 4, 2, 1, 16, 4, NULL, NULL, NULL, '2024-02-25', '2025-02-25', 'annually'),
(34, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence d\'un suivi des retenues de garantie', 'GRNT-034', 'Perte financière due à une gestion défaillante des garanties', 'Manque de système de suivi des échéances', NULL, 3, NULL, 4, 3, 2, 3, 1, 4, 3, 2, 1, 17, 2, NULL, NULL, NULL, '2024-05-30', '2024-11-30', 'biannually'),
(35, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence d\'un inventaire permanent des stocks', 'INV-035', 'Manque de traçabilité en temps réel des niveaux de stock', 'Processus manuel et non automatisé', NULL, 4, NULL, 3, 2, 2, 5, 1, 5, 2, 4, 1, 18, 2, NULL, NULL, NULL, '2024-03-22', '2024-09-22', 'quarterly'),
(36, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Absence de procédure d\'inventaire physique', 'INVPHY-036', 'Écarts non détectés entre stocks physiques et théoriques', 'Manque de planification des inventaires', NULL, 2, NULL, 4, 2, 2, 4, 1, 4, 3, 2, 1, 18, 3, NULL, NULL, NULL, '2024-04-12', '2025-04-12', 'annually'),
(37, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Défaillance du contrôle interne des achats', 'CTRLINT-037', 'Faiblesses dans les dispositifs de contrôle des processus achats', 'Absence d\'audit interne régulier', NULL, 3, NULL, 4, 4, 4, 4, 3, 4, 2, 3, 1, 1, 5, NULL, NULL, NULL, '2024-01-08', '2025-01-08', 'annually'),
(38, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Exposition aux conflits d\'intérêts', 'CONF-038', 'Décisions influencées par des intérêts personnels', 'Défaut de déclaration des liens d\'intérêts', NULL, 2, NULL, 3, 5, 5, 3, 4, 5, 4, 2, 1, 2, 4, NULL, NULL, NULL, '2024-02-20', '2025-02-20', 'annually'),
(39, '2025-06-17 15:57:17', '2025-06-17 15:57:17', 'Dépendance excessive à un fournisseur unique', 'DEP-039', 'Vulnérabilité opérationnelle due à une mono-source', 'Absence de stratégie de diversification', NULL, 3, NULL, 4, 2, 3, 5, 2, 5, 3, 3, 1, 3, 1, NULL, NULL, NULL, '2024-06-10', '2024-12-10', 'biannually');

-- --------------------------------------------------------

--
-- Table structure for table `riskcontrol`
--

CREATE TABLE `riskcontrol` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `riskId` int(11) NOT NULL,
  `meanIndicator` text,
  `meanOrganization` text,
  `meanManualPre` text,
  `meanManualPost` text,
  `meanIntegrated` text,
  `meanProgrammed` text,
  `meanReference` text,
  `evaluation` int(11) DEFAULT NULL,
  `proposedControl` text,
  `proposedControlDescription` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `riskfamily`
--

CREATE TABLE `riskfamily` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `projectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `risk_categories`
--

CREATE TABLE `risk_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `risk_categories`
--

INSERT INTO `risk_categories` (`id`, `category`, `count`) VALUES
(1, 'Opérationnel', 15),
(2, 'Financier', 8),
(3, 'Stratégique', 6),
(4, 'Conformité', 12),
(5, 'Technologique', 10),
(6, 'Réputationnel', 4),
(7, 'Opérationnel', 15),
(8, 'Financier', 8),
(9, 'Stratégique', 6),
(10, 'Conformité', 12),
(11, 'Technologique', 10),
(12, 'Réputationnel', 4);

-- --------------------------------------------------------

--
-- Table structure for table `risk_trends`
--

CREATE TABLE `risk_trends` (
  `id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `high` int(11) NOT NULL DEFAULT '0',
  `medium` int(11) NOT NULL DEFAULT '0',
  `low` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `risk_trends`
--

INSERT INTO `risk_trends` (`id`, `month`, `high`, `medium`, `low`) VALUES
(1, 'January', 5, 12, 8),
(2, 'February', 7, 15, 6),
(3, 'March', 4, 18, 9),
(4, 'April', 8, 14, 7),
(5, 'May', 6, 16, 11),
(6, 'June', 9, 13, 5),
(7, 'January', 5, 12, 8),
(8, 'February', 7, 15, 6),
(9, 'March', 4, 18, 9),
(10, 'April', 8, 14, 7),
(11, 'May', 6, 16, 11),
(12, 'June', 9, 13, 5);

-- --------------------------------------------------------

--
-- Table structure for table `risk_velocity`
--

CREATE TABLE `risk_velocity` (
  `id` int(11) NOT NULL,
  `week` varchar(20) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `risk_velocity`
--

INSERT INTO `risk_velocity` (`id`, `week`, `count`) VALUES
(1, 'Week 1', 3),
(2, 'Week 2', 5),
(3, 'Week 3', 2),
(4, 'Week 4', 7),
(5, 'Week 5', 4),
(6, 'Week 6', 6);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `createdAt`, `updatedAt`, `name`) VALUES
(1, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'Super Admin'),
(2, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'Admin'),
(3, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'Risk Manager'),
(4, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `securityevent`
--

CREATE TABLE `securityevent` (
  `id` int(11) NOT NULL,
  `eventType` varchar(100) NOT NULL,
  `severity` enum('low','medium','high','critical') DEFAULT 'medium',
  `userId` int(11) DEFAULT NULL,
  `ipAddress` varchar(45) DEFAULT NULL,
  `userAgent` text,
  `description` text,
  `metadata` json DEFAULT NULL,
  `resolved` tinyint(1) DEFAULT '0',
  `resolvedBy` int(11) DEFAULT NULL,
  `resolvedAt` datetime DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `createdAt`, `updatedAt`, `name`) VALUES
(1, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'Active'),
(2, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'Inactive'),
(3, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'Locked'),
(4, '2025-06-16 11:21:22', '2025-06-16 11:21:22', 'Pending Verification');

-- --------------------------------------------------------

--
-- Table structure for table `strategicobjectif`
--

CREATE TABLE `strategicobjectif` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `projectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `top_risks`
--

CREATE TABLE `top_risks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `trend` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `top_risks`
--

INSERT INTO `top_risks` (`id`, `name`, `score`, `trend`) VALUES
(1, 'Data Security Vulnerability', 22, 'up'),
(2, 'Regulatory Compliance Gap', 20, 'stable'),
(3, 'Budget Overrun Risk', 18, 'down'),
(4, 'Third-party Integration Failure', 16, 'up'),
(5, 'Key Personnel Unavailability', 15, 'stable');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `roleId` int(11) NOT NULL,
  `statusId` int(11) NOT NULL,
  `clientProfileId` int(11) DEFAULT NULL,
  `emailVerificationToken` varchar(255) DEFAULT NULL,
  `passwordResetToken` varchar(255) DEFAULT NULL,
  `passwordResetExpires` datetime DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `loginAttempts` int(11) DEFAULT '0',
  `lockoutUntil` datetime DEFAULT NULL,
  `twoFactorEnabled` tinyint(1) DEFAULT '0',
  `twoFactorSecret` varchar(255) DEFAULT NULL,
  `mustChangePassword` tinyint(1) DEFAULT '0',
  `lastPasswordChange` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `createdAt`, `updatedAt`, `email`, `firstName`, `lastName`, `phone`, `hash`, `roleId`, `statusId`, `clientProfileId`, `emailVerificationToken`, `passwordResetToken`, `passwordResetExpires`, `lastLogin`, `loginAttempts`, `lockoutUntil`, `twoFactorEnabled`, `twoFactorSecret`, `mustChangePassword`, `lastPasswordChange`) VALUES
(1, '2025-06-19 10:48:57', '2025-06-19 11:00:27', 'client@company.com', 'Test', 'Client', '0600000000', '$2y$10$FX2QcoBA2ELRg/ajM9t2.OeAKJ.pgVO9fbJVhqVwgvXWH/jrSMceW', 2, 1, 1, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, '2025-06-19 10:48:57'),
(2, '2025-06-19 10:48:57', '2025-06-19 10:48:57', 'manager@company.com', 'Risk', 'Manager', '0611111111', '866485796cfa8d7c0cf7111640205b83076433547577511d81f8030ae99ecea5', 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, '2025-06-19 10:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `usersession`
--

CREATE TABLE `usersession` (
  `id` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL,
  `ipAddress` varchar(45) DEFAULT NULL,
  `userAgent` text,
  `data` text,
  `lastActivity` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expiresAt` datetime NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actionplan`
--
ALTER TABLE `actionplan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_actionplan_risk` (`riskId`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_process_name` (`processId`,`name`),
  ADD KEY `fk_activity_parent` (`parentId`);

--
-- Indexes for table `auditlog`
--
ALTER TABLE `auditlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_auditlog_user` (`userId`),
  ADD KEY `idx_auditlog_entity` (`entity`,`entityId`,`createdAt`);

--
-- Indexes for table `clientprofile`
--
ALTER TABLE `clientprofile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `control_types`
--
ALTER TABLE `control_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `domaine`
--
ALTER TABLE `domaine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_project_name` (`projectId`,`name`);

--
-- Indexes for table `entity`
--
ALTER TABLE `entity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_project_name` (`projectId`,`name`),
  ADD KEY `fk_entity_parent` (`parentId`);

--
-- Indexes for table `mitigation_status`
--
ALTER TABLE `mitigation_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_downloads`
--
ALTER TABLE `monthly_downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operationalobjectif`
--
ALTER TABLE `operationalobjectif`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_strategic_name` (`strategicObjectiveId`,`name`);

--
-- Indexes for table `performanceindicator`
--
ALTER TABLE `performanceindicator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_project_name` (`projectId`,`name`);

--
-- Indexes for table `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_domaine_name` (`domaineId`,`name`),
  ADD KEY `fk_process_parent` (`parentId`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_client_name` (`clientId`,`name`);

--
-- Indexes for table `project_risks`
--
ALTER TABLE `project_risks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recent_activities`
--
ALTER TABLE `recent_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_entity` (`entityId`),
  ADD KEY `fk_report_project` (`projectId`);

--
-- Indexes for table `reportdownload`
--
ALTER TABLE `reportdownload`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_download_report` (`reportId`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_types`
--
ALTER TABLE `report_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `risk`
--
ALTER TABLE `risk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_risk_activity` (`activityId`),
  ADD KEY `fk_risk_entity` (`entityId`),
  ADD KEY `fk_risk_operational` (`operationalObjectiveId`),
  ADD KEY `fk_risk_strategic` (`strategicObjectiveId`),
  ADD KEY `fk_risk_family` (`riskFamilyId`),
  ADD KEY `idx_risk_score` (`riskScore`,`residualScore`),
  ADD KEY `idx_risk_active` (`active`);

--
-- Indexes for table `riskcontrol`
--
ALTER TABLE `riskcontrol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_risk_name` (`riskId`,`name`);

--
-- Indexes for table `riskfamily`
--
ALTER TABLE `riskfamily`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_riskfamily_project` (`projectId`);

--
-- Indexes for table `risk_categories`
--
ALTER TABLE `risk_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `risk_trends`
--
ALTER TABLE `risk_trends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `risk_velocity`
--
ALTER TABLE `risk_velocity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `securityevent`
--
ALTER TABLE `securityevent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_securityevent_user` (`userId`),
  ADD KEY `fk_securityevent_resolvedby` (`resolvedBy`),
  ADD KEY `idx_securityevent_resolved` (`resolved`,`createdAt`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `strategicobjectif`
--
ALTER TABLE `strategicobjectif`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_project_name` (`projectId`,`name`);

--
-- Indexes for table `top_risks`
--
ALTER TABLE `top_risks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `fk_user_role` (`roleId`),
  ADD KEY `fk_user_status` (`statusId`),
  ADD KEY `fk_user_client` (`clientProfileId`),
  ADD KEY `idx_user_email` (`email`);

--
-- Indexes for table `usersession`
--
ALTER TABLE `usersession`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usersession_user` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actionplan`
--
ALTER TABLE `actionplan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `auditlog`
--
ALTER TABLE `auditlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientprofile`
--
ALTER TABLE `clientprofile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `control_types`
--
ALTER TABLE `control_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `domaine`
--
ALTER TABLE `domaine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `entity`
--
ALTER TABLE `entity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mitigation_status`
--
ALTER TABLE `mitigation_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `monthly_downloads`
--
ALTER TABLE `monthly_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `operationalobjectif`
--
ALTER TABLE `operationalobjectif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performanceindicator`
--
ALTER TABLE `performanceindicator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process`
--
ALTER TABLE `process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_risks`
--
ALTER TABLE `project_risks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `recent_activities`
--
ALTER TABLE `recent_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reportdownload`
--
ALTER TABLE `reportdownload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `report_types`
--
ALTER TABLE `report_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `risk`
--
ALTER TABLE `risk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `riskcontrol`
--
ALTER TABLE `riskcontrol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riskfamily`
--
ALTER TABLE `riskfamily`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `risk_categories`
--
ALTER TABLE `risk_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `risk_trends`
--
ALTER TABLE `risk_trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `risk_velocity`
--
ALTER TABLE `risk_velocity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `securityevent`
--
ALTER TABLE `securityevent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `strategicobjectif`
--
ALTER TABLE `strategicobjectif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `top_risks`
--
ALTER TABLE `top_risks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actionplan`
--
ALTER TABLE `actionplan`
  ADD CONSTRAINT `fk_actionplan_risk` FOREIGN KEY (`riskId`) REFERENCES `risk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `fk_activity_parent` FOREIGN KEY (`parentId`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_activity_process` FOREIGN KEY (`processId`) REFERENCES `process` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auditlog`
--
ALTER TABLE `auditlog`
  ADD CONSTRAINT `fk_auditlog_user` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `domaine`
--
ALTER TABLE `domaine`
  ADD CONSTRAINT `fk_domaine_project` FOREIGN KEY (`projectId`) REFERENCES `project` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `entity`
--
ALTER TABLE `entity`
  ADD CONSTRAINT `fk_entity_parent` FOREIGN KEY (`parentId`) REFERENCES `entity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_entity_project` FOREIGN KEY (`projectId`) REFERENCES `project` (`id`);

--
-- Constraints for table `operationalobjectif`
--
ALTER TABLE `operationalobjectif`
  ADD CONSTRAINT `fk_operational_strategic` FOREIGN KEY (`strategicObjectiveId`) REFERENCES `strategicobjectif` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `performanceindicator`
--
ALTER TABLE `performanceindicator`
  ADD CONSTRAINT `fk_performance_project` FOREIGN KEY (`projectId`) REFERENCES `project` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `process`
--
ALTER TABLE `process`
  ADD CONSTRAINT `fk_process_domaine` FOREIGN KEY (`domaineId`) REFERENCES `domaine` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_process_parent` FOREIGN KEY (`parentId`) REFERENCES `process` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_client` FOREIGN KEY (`clientId`) REFERENCES `clientprofile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `fk_report_entity` FOREIGN KEY (`entityId`) REFERENCES `entity` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_report_project` FOREIGN KEY (`projectId`) REFERENCES `project` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reportdownload`
--
ALTER TABLE `reportdownload`
  ADD CONSTRAINT `fk_download_report` FOREIGN KEY (`reportId`) REFERENCES `report` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `risk`
--
ALTER TABLE `risk`
  ADD CONSTRAINT `fk_risk_activity` FOREIGN KEY (`activityId`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_risk_entity` FOREIGN KEY (`entityId`) REFERENCES `entity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_risk_family` FOREIGN KEY (`riskFamilyId`) REFERENCES `riskfamily` (`id`),
  ADD CONSTRAINT `fk_risk_operational` FOREIGN KEY (`operationalObjectiveId`) REFERENCES `operationalobjectif` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_risk_strategic` FOREIGN KEY (`strategicObjectiveId`) REFERENCES `strategicobjectif` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riskcontrol`
--
ALTER TABLE `riskcontrol`
  ADD CONSTRAINT `fk_riskcontrol_risk` FOREIGN KEY (`riskId`) REFERENCES `risk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riskfamily`
--
ALTER TABLE `riskfamily`
  ADD CONSTRAINT `fk_riskfamily_project` FOREIGN KEY (`projectId`) REFERENCES `project` (`id`);

--
-- Constraints for table `securityevent`
--
ALTER TABLE `securityevent`
  ADD CONSTRAINT `fk_securityevent_resolvedby` FOREIGN KEY (`resolvedBy`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_securityevent_user` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `strategicobjectif`
--
ALTER TABLE `strategicobjectif`
  ADD CONSTRAINT `fk_strategic_project` FOREIGN KEY (`projectId`) REFERENCES `project` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_client` FOREIGN KEY (`clientProfileId`) REFERENCES `clientprofile` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`roleId`) REFERENCES `role` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_status` FOREIGN KEY (`statusId`) REFERENCES `status` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `usersession`
--
ALTER TABLE `usersession`
  ADD CONSTRAINT `fk_usersession_user` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
