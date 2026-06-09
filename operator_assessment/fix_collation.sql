-- Fix Collation Mismatch
-- This script converts all operator assessment tables to use utf8mb4_general_ci
-- Run this BEFORE running add_exam_module.sql

-- Convert operator_assessments table
ALTER TABLE `operator_assessments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Convert operator_equipment table
ALTER TABLE `operator_equipment` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Convert operator_documents table
ALTER TABLE `operator_documents` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
