<?php
require 'dbcon.php';

// BSIT subjects enrollment
$query1 = "INSERT INTO class (student_id, subject_id, name, course)
           SELECT s.id, sub.subject_id, s.name, s.course
           FROM students s, subjects sub
           WHERE s.course = 'BSIT' AND sub.course = 'BSIT'
           AND NOT EXISTS (
               SELECT 1 FROM class c
               WHERE c.student_id = s.id
               AND c.subject_id = sub.subject_id
           )";

// BEED subjects enrollment
$query2 = "INSERT INTO class (student_id, subject_id, name, course)
           SELECT s.id, sub.subject_id, s.name, s.course
           FROM students s, subjects sub
           WHERE s.course = 'BEED' AND sub.course = 'BEED'
           AND NOT EXISTS (
               SELECT 1 FROM class c
               WHERE c.student_id = s.id
               AND c.subject_id = sub.subject_id
           )";

if (mysqli_query($con, $query1)) {
    $rows1 = mysqli_affected_rows($con);
    echo "Enrolled $rows1 students in BSIT subjects<br>";
}

if (mysqli_query($con, $query2)) {
    $rows2 = mysqli_affected_rows($con);
    echo "Enrolled $rows2 students in BEED subjects<br>";
}

mysqli_close($con);
?>