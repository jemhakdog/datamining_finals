<?php
require 'dbcon.php';

// Start transaction
mysqli_begin_transaction($con);

try {
    // Clear existing enrollments
    mysqli_query($con, "DELETE FROM class");
    
    // Get all BSIT subjects
    $bsit_subjects = mysqli_query($con, "SELECT subject_id FROM subjects WHERE course = 'BSIT'");
    $bsit_students = mysqli_query($con, "SELECT id, name FROM students WHERE course = 'BSIT'");
    
    // Enroll BSIT students
    while ($student = mysqli_fetch_assoc($bsit_students)) {
        mysqli_data_seek($bsit_subjects, 0);
        while ($subject = mysqli_fetch_assoc($bsit_subjects)) {
            $student_id = $student['id'];
            $subject_id = $subject['subject_id'];
            $name = mysqli_real_escape_string($con, $student['name']);
            
            mysqli_query($con, "INSERT INTO class (student_id, subject_id, name, course) 
                               VALUES ($student_id, $subject_id, '$name', 'BSIT')");
        }
    }
    
    // Get all BEED subjects
    $beed_subjects = mysqli_query($con, "SELECT subject_id FROM subjects WHERE course = 'BEED'");
    $beed_students = mysqli_query($con, "SELECT id, name FROM students WHERE course = 'BEED'");
    
    // Enroll BEED students
    while ($student = mysqli_fetch_assoc($beed_students)) {
        mysqli_data_seek($beed_subjects, 0);
        while ($subject = mysqli_fetch_assoc($beed_subjects)) {
            $student_id = $student['id'];
            $subject_id = $subject['subject_id'];
            $name = mysqli_real_escape_string($con, $student['name']);
            
            mysqli_query($con, "INSERT INTO class (student_id, subject_id, name, course) 
                               VALUES ($student_id, $subject_id, '$name', 'BEED')");
        }
    }
    
    // Commit transaction
    mysqli_commit($con);
    echo "All enrollments completed successfully";
    
} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($con);
    echo "Error: " . $e->getMessage();
}

// Show current enrollments
$result = mysqli_query($con, "SELECT s.name, s.course, sub.subject_name 
                             FROM class c 
                             JOIN students s ON c.student_id = s.id 
                             JOIN subjects sub ON c.subject_id = sub.subject_id 
                             ORDER BY s.course, s.name, sub.subject_name");

echo "<h3>Current Enrollments:</h3>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "{$row['name']} ({$row['course']}) - {$row['subject_name']}<br>";
}

mysqli_close($con);
?>