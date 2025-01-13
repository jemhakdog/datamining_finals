<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'dbcon.php';

// First, let's see what students and subjects we have
echo "<h3>Current Status:</h3>";

$students = mysqli_query($con, "SELECT * FROM students");
echo "<h4>Students:</h4>";
while ($row = mysqli_fetch_assoc($students)) {
    echo "{$row['name']} - {$row['course']}<br>";
}

$subjects = mysqli_query($con, "SELECT * FROM subjects");
echo "<h4>Subjects:</h4>";
while ($row = mysqli_fetch_assoc($subjects)) {
    echo "{$row['subject_name']} - {$row['course']}<br>";
}

// Now let's enroll BSIT students
$bsit_query = "INSERT IGNORE INTO class (student_id, subject_id, name, course)
               SELECT s.id, sub.subject_id, s.name, s.course
               FROM students s
               CROSS JOIN subjects sub
               WHERE s.course = 'BSIT' 
               AND sub.course = 'BSIT'";

if (mysqli_query($con, $bsit_query)) {
    $rows = mysqli_affected_rows($con);
    echo "<p>Enrolled $rows BSIT students</p>";
} else {
    echo "<p>Error enrolling BSIT students: " . mysqli_error($con) . "</p>";
}

// Now let's enroll BEED students
$beed_query = "INSERT IGNORE INTO class (student_id, subject_id, name, course)
               SELECT s.id, sub.subject_id, s.name, s.course
               FROM students s
               CROSS JOIN subjects sub
               WHERE s.course = 'BEED' 
               AND sub.course = 'BEED'";

if (mysqli_query($con, $beed_query)) {
    $rows = mysqli_affected_rows($con);
    echo "<p>Enrolled $rows BEED students</p>";
} else {
    echo "<p>Error enrolling BEED students: " . mysqli_error($con) . "</p>";
}

// Show current enrollments
$enrollments = mysqli_query($con, "
    SELECT s.name as student_name, s.course, sub.subject_name
    FROM class c
    JOIN students s ON c.student_id = s.id
    JOIN subjects sub ON c.subject_id = sub.subject_id
    ORDER BY s.course, s.name, sub.subject_name
");

echo "<h4>Current Enrollments:</h4>";
while ($row = mysqli_fetch_assoc($enrollments)) {
    echo "{$row['student_name']} ({$row['course']}) - {$row['subject_name']}<br>";
}

mysqli_close($con);
?>