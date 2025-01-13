-- Add role column to users table
ALTER TABLE users ADD COLUMN role ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'admin';
ALTER TABLE users ADD COLUMN ref_id INT NULL;

-- Update existing admin user
UPDATE users SET role = 'admin' WHERE id = 1;

-- Add sample teacher logins (using existing teacher data)
INSERT INTO users (user_name, password, role, ref_id) 
SELECT 
    LOWER(SUBSTRING_INDEX(email, '@', 1)), -- username from email
    CONCAT(LOWER(SUBSTRING_INDEX(name, ' ', 1)), '123'), -- password as firstname123
    'teacher',
    id
FROM teachers;

-- Add sample student logins (using existing student data)
INSERT INTO users (user_name, password, role, ref_id)
SELECT 
    LOWER(SUBSTRING_INDEX(email, '@', 1)), -- username from email
    CONCAT(LOWER(SUBSTRING_INDEX(name, ' ', 1)), '123'), -- password as firstname123
    'student',
    id
FROM students;