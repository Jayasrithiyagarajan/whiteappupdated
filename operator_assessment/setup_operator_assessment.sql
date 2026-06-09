-- Operator Assessment Module Database Setup
-- This script creates all necessary tables for the operator assessment system

-- Table 1: operator_assessments (Main assessment table)
CREATE TABLE IF NOT EXISTS `operator_assessments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `assessment_no` VARCHAR(50) NOT NULL UNIQUE,
  `date` DATE NOT NULL,
  `operator_name` VARCHAR(255) NOT NULL,
  `operator_id_passport` VARCHAR(100) NOT NULL,
  `client_id` VARCHAR(50) DEFAULT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `operating_location` ENUM('ONSHORE', 'OFFSHORE') NOT NULL,
  `no_of_equipment` INT(1) NOT NULL DEFAULT 1,
  `inspector_id` VARCHAR(50) DEFAULT NULL,
  `status` ENUM('PENDING', 'IN_PROGRESS', 'COMPLETED') NOT NULL DEFAULT 'PENDING',
  `license_number` VARCHAR(100) DEFAULT NULL,
  `date_of_assessment` DATE DEFAULT NULL,
  `date_of_expiry` DATE DEFAULT NULL,
  `created_by` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assessment_no` (`assessment_no`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_inspector_id` (`inspector_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: operator_equipment (Equipment details table)
CREATE TABLE IF NOT EXISTS `operator_equipment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` INT(11) NOT NULL,
  `equipment_number` INT(1) NOT NULL,
  `equipment_type` VARCHAR(255) DEFAULT NULL,
  `manufacturer` VARCHAR(255) DEFAULT NULL,
  `model` VARCHAR(255) DEFAULT NULL,
  `capacity` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assessment_id` (`assessment_id`),
  CONSTRAINT `fk_equipment_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `operator_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 3: operator_documents (Document uploads table)
CREATE TABLE IF NOT EXISTS `operator_documents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` INT(11) NOT NULL,
  `document_type` ENUM('IQAMA_PASSPORT', 'LICENSE', 'PHOTO', 'MEDICAL_CERT', 'PREVIOUS_CERT', 'ADDITIONAL') NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `original_filename` VARCHAR(255) DEFAULT NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assessment_id` (`assessment_id`),
  KEY `idx_document_type` (`document_type`),
  CONSTRAINT `fk_documents_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `operator_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional - for testing)
-- You can uncomment these lines to insert test data
-- INSERT INTO `operator_assessments` (`assessment_no`, `date`, `operator_name`, `operator_id_passport`, `client_id`, `location`, `operating_location`, `no_of_equipment`, `inspector_id`, `status`) 
-- VALUES ('CIMS-OAT-001', '2026-01-29', 'Test Operator', 'PASS123456', 'CUS001', 'Riyadh', 'ONSHORE', 2, 'INS001', 'PENDING');
