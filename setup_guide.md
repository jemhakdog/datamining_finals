# School Management System - Setup Guide

## System Requirements

1. PHP 7.3 or higher
2. MySQL/MariaDB 5.7 or higher
3. Web server (Apache/PHP built-in server)
4. Web browser (Chrome/Firefox/Edge)

## Prerequisites Installation

1. Install XAMPP/WAMP/MAMP or standalone:
   - PHP (7.3+)
   - MySQL/MariaDB (5.7+)
   - Apache (optional if using PHP built-in server)

2. Verify installations:
   ```bash
   php -v
   mysql --version
   ```

## Database Setup

1. Create MySQL database:
   ```sql
   CREATE DATABASE students;
   ```

2. Database credentials (default):
   - Host: localhost
   - Username: root
   - Password: (empty)
   - Database: students

## Running the Application

1. Using run.bat (Recommended):
   - Double click run.bat
   - Script will:
     - Check database connection
     - Import database if needed
     - Start PHP development server
     - Open application in browser

2. Manual Setup:
   - Import database:
     ```bash
     mysql -u root students < school.sql
     ```
   - Start PHP server:
     ```bash
     php -S localhost:8000
     ```
   - Access application:
     ```
     http://localhost:8000
     ```

## Default Login

- Username: admin
- Password: admin123

## System Features

1. User Management
   - Admin login
   - User roles (admin)

2. Student Management
   - Add/Edit/Delete students
   - View student details
   - Course enrollment

3. Teacher Management
   - Add/Edit/Delete teachers
   - Department assignment
   - Subject assignment

4. Subject Management
   - Add/Edit/Delete subjects
   - Course assignment
   - Unit configuration

5. Class Management
   - Class enrollment
   - Subject-student mapping

## Troubleshooting

1. Database Connection Issues:
   - Verify MySQL is running
   - Check credentials in dbcon.php
   - Ensure database 'students' exists

2. PHP Server Issues:
   - Check PHP installation
   - Verify port 8000 is available
   - Check error logs

3. Access Issues:
   - Verify URL is correct
   - Check file permissions
   - Clear browser cache

For additional support, check error logs or contact system administrator.