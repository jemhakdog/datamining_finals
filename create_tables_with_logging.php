<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$output = fopen('table_creation_log.txt', 'w');

function log_message($message) {
    global $output;
    fwrite($output, $message . "\n");
}

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

log_message("Attempting to create grades table...");
if (mysqli_query($con, $create_grades)) {
    log_message("Grades table created successfully");
} else {
    log_message("Error creating grades table: " . mysqli_error($con));
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

log_message("\nAttempting to create schedules table...");
if (mysqli_query($con, $create_schedules)) {
    log_message("Schedules table created successfully");
} else {
    log_message("Error creating schedules table: " . mysqli_error($con));
}

// Create indexes
$create_indexes = [
    "CREATE INDEX idx_grades_student ON grades(student_id)",
    "CREATE INDEX idx_grades_subject ON grades(subject_id)",
    "CREATE INDEX idx_schedules_teacher ON schedules(teacher_id)",
    "CREATE INDEX idx_schedules_subject ON schedules(subject_id)",
    "CREATE INDEX idx_schedules_day_time ON schedules(day_of_week, start_time, end_time)"
];

log_message("\nAttempting to create indexes...");
foreach ($create_indexes as $index_query) {
    if (mysqli_query($con, $index_query)) {
        log_message("Index created successfully: " . $index_query);
    } else {
        log_message("Error creating index: " . mysqli_error($con));
    }
}

// Verify tables were created
$result = mysqli_query($con, "SHOW TABLES");
log_message("\nCurrent tables in database:");
while ($row = mysqli_fetch_row($result)) {
    log_message("- " . $row[0]);
}

mysqli_close($con);
fclose($output);
?>