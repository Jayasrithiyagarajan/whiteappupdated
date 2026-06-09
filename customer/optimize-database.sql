-- Database Optimization Script for Customer List Performance
-- Add indexes on frequently searched columns

-- Check if indexes exist and create them if they don't
-- Index on customer_name for faster name searches
CREATE INDEX IF NOT EXISTS idx_customer_name ON customers(customer_name);

-- Index on email for faster email searches
CREATE INDEX IF NOT EXISTS idx_email ON customers(email);

-- Index on mobile for faster phone number searches
CREATE INDEX IF NOT EXISTS idx_mobile ON customers(mobile);

-- Index on city for faster city-based filtering
CREATE INDEX IF NOT EXISTS idx_city ON customers(city);

-- Index on date_of_adding for faster date-based sorting
CREATE INDEX IF NOT EXISTS idx_date_of_adding ON customers(date_of_adding);

-- Index on rep_name for faster representative searches
CREATE INDEX IF NOT EXISTS idx_rep_name ON customers(rep_name);

-- Composite index for common search patterns
CREATE INDEX IF NOT EXISTS idx_customer_search ON customers(customer_name, email, mobile);

-- Show all indexes on customers table
SHOW INDEX FROM customers;
