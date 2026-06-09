-- Add missing index to reports table for performance optimization
-- This will dramatically improve pagination speed on checklist pages

-- Check if index already exists
SELECT COUNT(*) as index_exists 
FROM information_schema.statistics 
WHERE table_schema = '3rdparty' 
  AND table_name = 'reports' 
  AND index_name = 'idx_project_no';

-- Create the index if it doesn't exist
-- Uncomment the line below to execute:
-- CREATE INDEX idx_project_no ON reports(project_no);

-- Verify the index was created
SHOW INDEX FROM reports WHERE Key_name = 'idx_project_no';
