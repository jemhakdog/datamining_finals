@echo off
echo Checking system setup...

REM Set default paths for XAMPP and WAMP
set XAMPP_MYSQL="C:\xampp\mysql\bin\mysql.exe"
set XAMPP_PHP="C:\xampp\php\php.exe"
set WAMP_MYSQL="C:\wamp64\bin\mysql\mysql5.7.31\bin\mysql.exe"
set WAMP_PHP="C:\wamp64\bin\php\php7.3.21\php.exe"

REM Initialize MySQL and PHP paths
set MYSQL_CMD=mysql
set PHP_CMD=php
set MYSQL_PWD=

REM Check for MySQL in XAMPP
if exist %XAMPP_MYSQL% (
    set MYSQL_CMD=%XAMPP_MYSQL%
    echo Found MySQL in XAMPP
    goto :mysql_found
)

REM Check for MySQL in WAMP
if exist %WAMP_MYSQL% (
    set MYSQL_CMD=%WAMP_MYSQL%
    echo Found MySQL in WAMP
    goto :mysql_found
)

REM Check system PATH for MySQL
mysql --version > nul 2>&1
if %errorlevel% equ 0 (
    echo Found MySQL in system PATH
    goto :mysql_found
) else (
    echo MySQL not found in common locations or system PATH
    echo Please install MySQL or add it to system PATH
    pause
    exit /b 1
)

:mysql_found
REM Check if MySQL is running
%MYSQL_CMD% --version > nul 2>&1
if %errorlevel% neq 0 (
    echo MySQL is not running.
    echo Please start MySQL server first.
    pause
    exit /b 1
)

REM Try to connect with and without password
echo Testing MySQL connection...
%MYSQL_CMD% -u root -e "SELECT 'Connection successful'" > nul 2>&1
if %errorlevel% neq 0 (
    echo Trying with default XAMPP password...
    %MYSQL_CMD% -u root -p"" -e "SELECT 'Connection successful'" > nul 2>&1
    if %errorlevel% equ 0 (
        set MYSQL_PWD=-p""
        echo Connected with default password
    ) else (
        echo Failed to connect to MySQL
        echo Please check if MySQL is running and credentials are correct
        pause
        exit /b 1
    )
)

echo Preparing database...
REM Drop database if exists and create new one
%MYSQL_CMD% -u root %MYSQL_PWD% -e "DROP DATABASE IF EXISTS students"
if %errorlevel% neq 0 (
    echo Failed to drop existing database
    pause
    exit /b 1
)

echo Creating fresh database...
%MYSQL_CMD% -u root %MYSQL_PWD% -e "CREATE DATABASE students"
if %errorlevel% neq 0 (
    echo Failed to create database
    pause
    exit /b 1
)

REM Disable foreign key checks and import database
echo Disabling foreign key checks...
%MYSQL_CMD% -u root %MYSQL_PWD% -e "SET GLOBAL FOREIGN_KEY_CHECKS=0;"

echo Importing database from students.sql...
echo Command: %MYSQL_CMD% -u root %MYSQL_PWD% students ^< "students.sql"
%MYSQL_CMD% -u root %MYSQL_PWD% students < "students.sql" 2> import_error.log

if %errorlevel% equ 0 (
    echo Re-enabling foreign key checks...
    %MYSQL_CMD% -u root %MYSQL_PWD% -e "SET GLOBAL FOREIGN_KEY_CHECKS=1;"
    echo Database imported successfully from students.sql
    if exist import_error.log del import_error.log
) else (
    echo Failed to import database from students.sql
    echo Checking error log...
    if exist import_error.log (
        type import_error.log
        del import_error.log
    )
    pause
    exit /b 1
)

REM Check for PHP in XAMPP
if exist %XAMPP_PHP% (
    set PHP_CMD=%XAMPP_PHP%
    echo Found PHP in XAMPP
    goto :php_found
)

REM Check for PHP in WAMP
if exist %WAMP_PHP% (
    set PHP_CMD=%WAMP_PHP%
    echo Found PHP in WAMP
    goto :php_found
)

REM Check system PATH for PHP
php -v > nul 2>&1
if %errorlevel% equ 0 (
    echo Found PHP in system PATH
    goto :php_found
) else (
    echo PHP not found in common locations or system PATH
    echo Please install PHP or add it to system PATH
    pause
    exit /b 1
)

:php_found
echo Starting PHP development server...
start http://localhost:8000
%PHP_CMD% -S localhost:8000

pause