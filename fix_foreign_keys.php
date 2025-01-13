<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbcon.php');

// Create grades table without foreign keys first
$create_grades = "CREATE TABLE IF NOT EXISTS grades (
    grade_id INT(6) PRIMARY KEY AUTO_INCREMENT,
    class_id INT(6),
    student_id INT(6),
    subject_id INT(6),
    grade_value DECIMAL(5,2),
    term VARCHAR(20),
    academic_year VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (class_id),
    INDEX (student_id),
    INDEX (subject_id)
) ENGINE=InnoDB";

echo "Attempting to create grades table...\n";
if (mysqli_query($con, $create_grades)) {
    echo "Grades table created successfully\n";
    
    // Add foreign keys separately
    $add_foreign_keys = [
        "ALTER TABLE grades ADD FOREIGN KEY (class_id) REFERENCES class(class_id)",
        "ALTER TABLE grades ADD FOREIGN KEY (student_id) REFERENCES students(id)",
        "ALTER TABLE grades ADD FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)"
    ];
    
    foreach ($add_foreign_keys as $query) {
        if (mysqli_query($con, $query)) {
            echo "Foreign key added successfully: $query\n";
        } else {
            echo "Error adding foreign key: " . mysqli_error($con) . "\n";
        }
    }
} else {
    echo "Error creating grades table: " . mysqli_error($con) . "\n";
}

// Create schedules table without foreign keys first
$create_schedules = "CREATE TABLE IF NOT EXISTS schedules (
    schedule_id INT(6) PRIMARY KEY AUTO_INCREMENT,
    subject_id INT(6),
    teacher_id INT(6),
    day_of_week INT(1),
    start_time TIME,
    end_time TIME,
    room VARCHAR(50),
    academic_year VARCHAR(10),
    semester VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (subject_id),
    INDEX (teacher_id),
    INDEX (day_of_week, start_time, end_time)
) ENGINE=InnoDB";

echo "\nAttempting to create schedules table...\n";
if (mysqli_query($con, $create_schedules)) {
    echo "Schedules table created successfully\n";
    
    // Add foreign keys separately
    $add_foreign_keys = [
        "ALTER TABLE schedules ADD FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)",
        "ALTER TABLE schedules ADD FOREIGN KEY (teacher_id) REFERENCES teachers(id)"
    ];
    
    foreach ($add_foreign_keys as $query) {
        if (mysqli_query($con, $query)) {
            echo "Foreign key added successfully: $query\n";
        } else {
            echo "Error adding foreign key: " . mysqli_error($con) . "\n";
        }
    }
} else {
    echo "Error creating schedules table: " . mysqli_error($con) . "\n";
}

mysqli_close($con);
?>