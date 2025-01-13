<?php
require 'dbcon.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Open log file
$log = fopen("enrollment_log.txt", "w");

$query = "INSERT INTO class (student_id, subject_id, name, course) 
          SELECT s.id as student_id,
                 sub.subject_id,
                 s.name,
                 s.course
          FROM students s
          CROSS JOIN subjects sub
          WHERE s.course = sub.course
          AND NOT EXISTS (
              SELECT 1 
              FROM class c 
              WHERE c.student_id = s.id 
              AND c.subject_id = sub.subject_id
          )";

fwrite($log, "Executing query:\n" . $query . "\n\n");

if (mysqli_query($con, $query)) {
    $rows = mysqli_affected_rows($con);
    $message = "Successfully enrolled $rows students";
    fwrite($log, $message . "\n");
    echo $message;
} else {
    $error = "Error: " . mysqli_error($con);
    fwrite($log, $error . "\n");
    echo $error;
}

// Log current enrollments
$check_query = "SELECT s.name, s.course, sub.subject_name 
                FROM class c 
                JOIN students s ON c.student_id = s.id 
                JOIN subjects sub ON c.subject_id = sub.subject_id 
                ORDER BY s.name, sub.subject_name";

$result = mysqli_query($con, $check_query);
fwrite($log, "\nCurrent enrollments:\n");
while ($row = mysqli_fetch_assoc($result)) {
    fwrite($log, "{$row['name']} ({$row['course']}) - {$row['subject_name']}\n");
}

mysqli_close($con);
fclose($log);
?>