-- Hand Signals Test Module - Database Migration
-- Add Step 4: Hand Signals Test to operator assessment workflow

-- Step 1: Add hand signals columns to operator_assessments table
ALTER TABLE `operator_assessments` 
ADD COLUMN `signals_status` ENUM('NOT_STARTED', 'IN_PROGRESS', 'PASSED', 'FAILED') DEFAULT 'NOT_STARTED' AFTER `exam_attempts`,
ADD COLUMN `signals_score` DECIMAL(5,2) DEFAULT NULL COMMENT 'Percentage score' AFTER `signals_status`,
ADD COLUMN `signals_passed` INT(2) DEFAULT 0 COMMENT 'Number of signals passed' AFTER `signals_score`,
ADD COLUMN `signals_failed` INT(2) DEFAULT 0 COMMENT 'Number of signals failed' AFTER `signals_passed`,
ADD COLUMN `signals_tested_at` TIMESTAMP NULL DEFAULT NULL AFTER `signals_failed`,
ADD COLUMN `signals_attempts` INT(2) DEFAULT 0 AFTER `signals_tested_at`;

-- Step 2: Create table for storing individual hand signal results
CREATE TABLE IF NOT EXISTS `operator_hand_signals` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` INT(11) NOT NULL,
  `signal_number` INT(2) NOT NULL COMMENT 'Signal number 1-18',
  `signal_name` VARCHAR(100) NOT NULL COMMENT 'Name of the signal',
  `result` ENUM('PASS', 'FAIL') NOT NULL,
  `tested_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assessment_id` (`assessment_id`),
  KEY `idx_signal_number` (`signal_number`),
  CONSTRAINT `fk_signals_assessment` FOREIGN KEY (`assessment_id`) 
    REFERENCES `operator_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Step 3: Add index for signals status queries
ALTER TABLE `operator_assessments` 
ADD INDEX `idx_signals_status` (`signals_status`);
