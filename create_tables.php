<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbcon.php');

// Check database connection
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "Connected to database successfully<br>";

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

// Show current tables
$show_tables = "SHOW TABLES";
$tables_result = mysqli_query($con, $show_tables);
echo "<br>Current tables in database:<br>";
while ($table = mysqli_fetch_array($tables_result)) {
    echo $table[0] . "<br>";
}

// Execute table creation
echo "<br>Creating grades table...<br>";
if (mysqli_query($con, $create_grades)) {
    echo "Grades table created successfully<br>";
} else {
    echo "Error creating grades table: " . mysqli_error($con) . "<br>";
}

echo "<br>Creating schedules table...<br>";
if (mysqli_query($con, $create_schedules)) {
    echo "Schedules table created successfully<br>";
} else {
    echo "Error creating schedules table: " . mysqli_error($con) . "<br>";
}

// Create indexes
$create_indexes = [
    "CREATE INDEX IF NOT EXISTS idx_grades_student ON grades(student_id)",
    "CREATE INDEX IF NOT EXISTS idx_grades_subject ON grades(subject_id)",
    "CREATE INDEX IF NOT EXISTS idx_schedules_teacher ON schedules(teacher_id)",
    "CREATE INDEX IF NOT EXISTS idx_schedules_subject ON schedules(subject_id)",
    "CREATE INDEX IF NOT EXISTS idx_schedules_day_time ON schedules(day_of_week, start_time, end_time)"
];

// Execute index creation
echo "<br>Creating indexes...<br>";
foreach ($create_indexes as $index_query) {
    if (mysqli_query($con, $index_query)) {
        echo "Index created successfully<br>";
    } else {
        echo "Error creating index: " . mysqli_error($con) . "<br>";
    }
}

// Show tables again to confirm creation
$tables_result = mysqli_query($con, $show_tables);
echo "<br>Updated tables in database:<br>";
while ($table = mysqli_fetch_array($tables_result)) {
    echo $table[0] . "<br>";
}

// Show table structures
echo "<br>Grades table structure:<br>";
$desc_grades = mysqli_query($con, "DESCRIBE grades");
while ($row = mysqli_fetch_array($desc_grades)) {
    echo $row['Field'] . " " . $row['Type'] . "<br>";
}

echo "<br>Schedules table structure:<br>";
$desc_schedules = mysqli_query($con, "DESCRIBE schedules");
while ($row = mysqli_fetch_array($desc_schedules)) {
    echo $row['Field'] . " " . $row['Type'] . "<br>";
}

mysqli_close($con);
?>