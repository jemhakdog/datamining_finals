<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'dbcon.php';

$sql = "
-- Enroll BSIT students in BSIT subjects
INSERT IGNORE INTO class (student_id, subject_id, name, course)
SELECT s.id, sub.subject_id, s.name, s.course
FROM students s, subjects sub
WHERE s.course = 'BSIT' AND sub.course = 'BSIT';

-- Enroll BEED students in BEED subjects
INSERT IGNORE INTO class (student_id, subject_id, name, course)
SELECT s.id, sub.subject_id, s.name, s.course
FROM students s, subjects sub
WHERE s.course = 'BEED' AND sub.course = 'BEED';
";

if (mysqli_multi_query($con, $sql)) {
    do {
        if ($result = mysqli_store_result($con)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($con));
    
    echo "Enrollment completed successfully";
} else {
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con);
?>