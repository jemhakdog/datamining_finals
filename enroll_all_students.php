<?php
session_start();
require 'dbcon.php';

// Single query to enroll all students in their matching course subjects
$query = "INSERT INTO class (student_id, subject_id, name, course)
          SELECT 
              s.id as student_id,
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

$result = mysqli_query($con, $query);

if ($result) {
    $rows_affected = mysqli_affected_rows($con);
    if ($rows_affected > 0) {
        $_SESSION['message'] = "Successfully enrolled $rows_affected new students in their course subjects";
    } else {
        $_SESSION['message'] = "No new enrollments needed. All students are already enrolled in their course subjects.";
    }
} else {
    $_SESSION['message'] = "Error enrolling students: " . mysqli_error($con);
}

header("Location: class.php");
exit(0);
?>