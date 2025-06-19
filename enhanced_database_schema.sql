-- Additional tables for enhanced report functionality

-- Table for tracking report downloads
CREATE TABLE IF NOT EXISTS `reportdownload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reportId` int(11) NOT NULL,
  `downloadedBy` varchar(255) DEFAULT NULL,
  `downloadedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `ipAddress` varchar(45) DEFAULT NULL,
  `userAgent` text,
  `format` varchar(10) DEFAULT 'PDF',
  PRIMARY KEY (`id`),
  KEY `idx_report_id` (`reportId`),
  KEY `idx_downloaded_at` (`downloadedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Enhanced report table with additional fields
ALTER TABLE `report` 
ADD COLUMN IF NOT EXISTS `template_id` int(11) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `auto_generate` tinyint(1) DEFAULT 0,
ADD COLUMN IF NOT EXISTS `schedule_frequency` enum('daily','weekly','monthly','quarterly','yearly') DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `last_generated` datetime DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `recipients` text DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `tags` varchar(500) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `security_level` enum('public','internal','confidential','restricted') DEFAULT 'internal';

-- Table for report templates
CREATE TABLE IF NOT EXISTS `report_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('Risk Assessment','Compliance','Financial','Security','Executive','Custom') NOT NULL,
  `description` text,
  `template_content` longtext,
  `parameters` json DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert default report templates
INSERT INTO `report_templates` (`name`, `type`, `description`, `template_content`, `parameters`) VALUES
('Standard Risk Assessment', 'Risk Assessment', 'Template standard pour l\'évaluation des risques', 
'{"sections": ["summary", "risk_details", "mitigation_plans", "recommendations"]}', 
'{"include_charts": true, "risk_threshold": 10, "include_controls": true}'),

('Compliance Report', 'Compliance', 'Rapport de conformité réglementaire', 
'{"sections": ["compliance_status", "control_effectiveness", "gaps_analysis", "action_plans"]}', 
'{"frameworks": ["ISO27001", "SOX", "GDPR"], "include_evidence": true}'),

('Executive Dashboard', 'Executive', 'Tableau de bord pour la direction', 
'{"sections": ["executive_summary", "key_metrics", "top_risks", "strategic_recommendations"]}', 
'{"summary_only": true, "include_trends": true, "max_risks": 10}'),

('Financial Impact Analysis', 'Financial', 'Analyse d\'impact financier des risques', 
'{"sections": ["financial_summary", "cost_analysis", "roi_calculations", "budget_impact"]}', 
'{"currency": "EUR", "include_projections": true, "time_horizon": 12}'),

('Security Assessment', 'Security', 'Évaluation de la sécurité informatique', 
'{"sections": ["security_posture", "vulnerabilities", "threat_analysis", "security_controls"]}', 
'{"include_technical_details": true, "severity_levels": ["critical", "high", "medium", "low"]}');

-- Table for report sharing and collaboration
CREATE TABLE IF NOT EXISTS `report_shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `shared_by` varchar(255) NOT NULL,
  `shared_with` varchar(255) NOT NULL,
  `permission_level` enum('view','download','edit') DEFAULT 'view',
  `shared_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime DEFAULT NULL,
  `access_count` int(11) DEFAULT 0,
  `last_accessed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_report_id` (`report_id`),
  KEY `idx_shared_with` (`shared_with`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table for report comments and annotations
CREATE TABLE IF NOT EXISTS `report_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `section` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_resolved` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_report_id` (`report_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table for report generation queue (for async processing)
CREATE TABLE IF NOT EXISTS `report_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `requested_by` varchar(255) NOT NULL,
  `format` varchar(10) DEFAULT 'PDF',
  `parameters` json DEFAULT NULL,
  `status` enum('pending','processing','completed','failed') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Update existing reports with sample data
UPDATE `report` SET 
  `security_level` = 'internal',
  `tags` = 'quarterly,risk-assessment,compliance'
WHERE `type` = 'Risk Assessment';

UPDATE `report` SET 
  `security_level` = 'confidential',
  `tags` = 'annual,compliance,regulatory'
WHERE `type` = 'Compliance';

UPDATE `report` SET 
  `security_level` = 'restricted',
  `tags` = 'executive,strategic,monthly'
WHERE `type` = 'Executive';

-- Insert sample download records
INSERT INTO `reportdownload` (`reportId`, `downloadedBy`, `downloadedAt`, `ipAddress`, `format`) VALUES
(1, 'admin@example.com', '2025-06-17 10:30:00', '192.168.1.100', 'PDF'),
(1, 'manager@example.com', '2025-06-17 14:15:00', '192.168.1.101', 'Excel'),
(2, 'auditor@example.com', '2025-06-16 09:45:00', '192.168.1.102', 'PDF'),
(4, 'security@example.com', '2025-06-15 16:20:00', '192.168.1.103', 'Word'),
(5, 'ceo@example.com', '2025-06-14 11:00:00', '192.168.1.104', 'PDF');

