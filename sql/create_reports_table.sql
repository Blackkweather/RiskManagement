-- Add Reports table
CREATE TABLE IF NOT EXISTS Report (
  id INT AUTO_INCREMENT PRIMARY KEY,
  createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
  updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  name VARCHAR(255) NOT NULL,
  type ENUM('Risk Assessment', 'Compliance', 'Financial', 'Security', 'Executive') NOT NULL,
  status ENUM('Draft', 'Published') NOT NULL DEFAULT 'Draft',
  description TEXT,
  format VARCHAR(50) NOT NULL DEFAULT 'PDF',
  file_path VARCHAR(255),
  file_size VARCHAR(20),
  download_count INT DEFAULT 0,
  generated_by VARCHAR(255),
  entityId INT,
  projectId INT,
  CONSTRAINT fk_report_entity FOREIGN KEY (entityId) REFERENCES Entity(id) ON DELETE SET NULL,
  CONSTRAINT fk_report_project FOREIGN KEY (projectId) REFERENCES Project(id) ON DELETE SET NULL
);

-- Create table for tracking report downloads
CREATE TABLE IF NOT EXISTS ReportDownload (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reportId INT NOT NULL,
  downloadedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
  downloadedBy VARCHAR(255),
  CONSTRAINT fk_download_report FOREIGN KEY (reportId) REFERENCES Report(id) ON DELETE CASCADE
);
