-- Written Exam Module - Database Updates
-- Add these changes to the existing operator assessment database

-- Step 1: Add exam-related columns to operator_assessments table
ALTER TABLE `operator_assessments` 
ADD COLUMN `exam_status` ENUM('NOT_STARTED', 'IN_PROGRESS', 'PASSED', 'FAILED') DEFAULT 'NOT_STARTED' AFTER `status`,
ADD COLUMN `exam_score` INT(3) DEFAULT NULL AFTER `exam_status`,
ADD COLUMN `exam_taken_at` TIMESTAMP NULL DEFAULT NULL AFTER `exam_score`,
ADD COLUMN `exam_attempts` INT(2) DEFAULT 0 AFTER `exam_taken_at`;

-- Step 2: Create table for storing individual exam answers
CREATE TABLE IF NOT EXISTS `operator_exam_answers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` INT(11) NOT NULL,
  `question_number` INT(2) NOT NULL,
  `selected_answer` VARCHAR(1) NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
  `marks_obtained` INT(1) NOT NULL DEFAULT 0,
  `answered_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assessment_id` (`assessment_id`),
  KEY `idx_question_number` (`question_number`),
  CONSTRAINT `fk_exam_answers_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `operator_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Step 3: Add index for exam status queries
ALTER TABLE `operator_assessments` 
ADD INDEX `idx_exam_status` (`exam_status`);
