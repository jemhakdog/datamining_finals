<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbcon.php');

// Create grades table
$create_grades = "CREATE TABLE IF NOT EXISTS grades (
    grade_id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT,
    student_id INT,
    subject_id INT,
    grade_value DECIMAL(5,2),
    term VARCHAR(20),
    academic_year VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES class(class_id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
) ENGINE=InnoDB";

echo "Attempting to create grades table...\n";
if (mysqli_query($con, $create_grades)) {
    echo "Grades table created successfully\n";
} else {
    echo "Error creating grades table: " . mysqli_error($con) . "\n";
}

// Create schedules table
$create_schedules = "CREATE TABLE IF NOT EXISTS schedules (
    schedule_id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT,
    teacher_id INT,
    day_of_week INT,
    start_time TIME,
    end_time TIME,
    room VARCHAR(50),
    academic_year VARCHAR(10),
    semester VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
) ENGINE=InnoDB";

echo "\nAttempting to create schedules table...\n";
if (mysqli_query($con, $create_schedules)) {
    echo "Schedules table created successfully\n";
} else {
    echo "Error creating schedules table: " . mysqli_error($con) . "\n";
}

// Create indexes
$create_indexes = [
    "CREATE INDEX idx_grades_student ON grades(student_id)",
    "CREATE INDEX idx_grades_subject ON grades(subject_id)",
    "CREATE INDEX idx_schedules_teacher ON schedules(teacher_id)",
    "CREATE INDEX idx_schedules_subject ON schedules(subject_id)",
    "CREATE INDEX idx_schedules_day_time ON schedules(day_of_week, start_time, end_time)"
];

echo "\nAttempting to create indexes...\n";
foreach ($create_indexes as $index_query) {
    if (mysqli_query($con, $index_query)) {
        echo "Index created successfully: $index_query\n";
    } else {
        echo "Error creating index: " . mysqli_error($con) . "\n";
    }
}

// Verify tables were created
$result = mysqli_query($con, "SHOW TABLES");
echo "\nCurrent tables in database:\n";
while ($row = mysqli_fetch_row($result)) {
    echo "- " . $row[0] . "\n";
}

mysqli_close($con);
?>