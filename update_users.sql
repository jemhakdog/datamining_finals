-- Drop and recreate users table with role support
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id int(5) NOT NULL AUTO_INCREMENT,
    user_name varchar(20) NOT NULL,
    password varchar(20) NOT NULL,
    role ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'admin',
    ref_id INT NULL,
    PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Add admin user
INSERT INTO users (user_name, password, role) VALUES ('admin', 'admin123', 'admin');

-- Add teacher users
INSERT INTO users (user_name, password, role, ref_id)
SELECT 
    LOWER(SUBSTRING_INDEX(name, ' ', 1)), -- username from first name
    CONCAT(LOWER(SUBSTRING_INDEX(name, ' ', 1)), '123'), -- password as firstname123
    'teacher',
    id
FROM teachers;

-- Add student users
INSERT INTO users (user_name, password, role, ref_id)
SELECT 
    LOWER(SUBSTRING_INDEX(name, ' ', 1)), -- username from first name
    CONCAT(LOWER(SUBSTRING_INDEX(name, ' ', 1)), '123'), -- password as firstname123
    'student',
    id
FROM students;