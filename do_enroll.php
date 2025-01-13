<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'dbcon.php';

// Disable foreign key checks temporarily
mysqli_query($con, "SET FOREIGN_KEY_CHECKS=0");

// Clear existing enrollments
mysqli_query($con, "TRUNCATE TABLE class");

// BSIT Enrollments
$bsit_sql = "INSERT INTO class (student_id, subject_id, name, course)
             SELECT s.id, sub.subject_id, s.name, s.course
             FROM students s, subjects sub
             WHERE s.course = 'BSIT' AND sub.course = 'BSIT'";

$bsit_result = mysqli_query($con, $bsit_sql);
$bsit_count = mysqli_affected_rows($con);

// BEED Enrollments
$beed_sql = "INSERT INTO class (student_id, subject_id, name, course)
             SELECT s.id, sub.subject_id, s.name, s.course
             FROM students s, subjects sub
             WHERE s.course = 'BEED' AND sub.course = 'BEED'";

$beed_result = mysqli_query($con, $beed_sql);
$beed_count = mysqli_affected_rows($con);

// Re-enable foreign key checks
mysqli_query($con, "SET FOREIGN_KEY_CHECKS=1");

// Output results
echo "BSIT Students Enrolled: $bsit_count<br>";
echo "BEED Students Enrolled: $beed_count<br>";

// Show current enrollments
$check_sql = "SELECT s.name, s.course, sub.subject_name 
              FROM class c 
              JOIN students s ON c.student_id = s.id 
              JOIN subjects sub ON c.subject_id = sub.subject_id 
              ORDER BY s.course, s.name, sub.subject_name";

$check_result = mysqli_query($con, $check_sql);

echo "<h3>Current Enrollments:</h3>";
while ($row = mysqli_fetch_assoc($check_result)) {
    echo "{$row['name']} ({$row['course']}) - {$row['subject_name']}<br>";
}

mysqli_close($con);
ob_end_flush();
?>