<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbcon.php');

// Create grades table
$create_grades = "CREATE TABLE IF NOT EXISTS grades (
    grade_id int(6) NOT NULL AUTO_INCREMENT,
    class_id int(6) NOT NULL,
    student_id int(6) NOT NULL,
    subject_id int(6) NOT NULL,
    grade_value DECIMAL(5,2) NOT NULL,
    term VARCHAR(20) NOT NULL,
    academic_year VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (grade_id),
    KEY class_id (class_id),
    KEY student_id (student_id),
    KEY subject_id (subject_id),
    CONSTRAINT fk_grade_class FOREIGN KEY (class_id) REFERENCES class (class_id),
    CONSTRAINT fk_grade_student FOREIGN KEY (student_id) REFERENCES students (id),
    CONSTRAINT fk_grade_subject FOREIGN KEY (subject_id) REFERENCES subjects (subject_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";

echo "Attempting to create grades table...\n";
if (mysqli_query($con, $create_grades)) {
    echo "Grades table created successfully\n";
} else {
    echo "Error creating grades table: " . mysqli_error($con) . "\n";
}

// Create schedules table
$create_schedules = "CREATE TABLE IF NOT EXISTS schedules (
    schedule_id int(6) NOT NULL AUTO_INCREMENT,
    subject_id int(6) NOT NULL,
    teacher_id int(6) NOT NULL,
    day_of_week int(1) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room VARCHAR(50) NOT NULL,
    academic_year VARCHAR(10) NOT NULL,
    semester VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (schedule_id),
    KEY subject_id (subject_id),
    KEY teacher_id (teacher_id),
    KEY idx_schedule_time (day_of_week, start_time, end_time),
    CONSTRAINT fk_schedule_subject FOREIGN KEY (subject_id) REFERENCES subjects (subject_id),
    CONSTRAINT fk_schedule_teacher FOREIGN KEY (teacher_id) REFERENCES teachers (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";

echo "\nAttempting to create schedules table...\n";
if (mysqli_query($con, $create_schedules)) {
    echo "Schedules table created successfully\n";
} else {
    echo "Error creating schedules table: " . mysqli_error($con) . "\n";
}

// Verify the tables were created with correct structure
$tables = ['grades', 'schedules'];
foreach ($tables as $table) {
    $result = mysqli_query($con, "SHOW CREATE TABLE $table");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "\nVerifying $table structure:\n";
        echo $row['Create Table'] . "\n";
    } else {
        echo "\nError verifying $table: " . mysqli_error($con) . "\n";
    }
}

mysqli_close($con);
?>