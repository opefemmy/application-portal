-- Add missing columns to interviews table
-- Run this SQL in your MySQL database (using phpMyAdmin or similar)

-- Check if columns exist first, then add if they don't
-- For MySQL

-- Add interview_time column if it doesn't exist
ALTER TABLE `interviews` ADD COLUMN `interview_time` TIME NULL AFTER `interview_date`;

-- Add panel column if it doesn't exist
ALTER TABLE `interviews` ADD COLUMN `panel` VARCHAR(255) NULL AFTER `interview_time`;

-- Add meeting_link column if it doesn't exist
ALTER TABLE `interviews` ADD COLUMN `meeting_link` VARCHAR(500) NULL AFTER `panel`;

-- If you get error that column already exists, run individual statements:
-- ALTER TABLE `interviews` ADD COLUMN `interview_time` TIME NULL AFTER `interview_date`;
-- ALTER TABLE `interviews` ADD COLUMN `panel` VARCHAR(255) NULL;
-- ALTER TABLE `interviews` ADD COLUMN `meeting_link` VARCHAR(500) NULL;