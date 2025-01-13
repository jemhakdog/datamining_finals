<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$output = fopen('database_test_results.txt', 'w');

function log_message($message) {
    global $output;
    fwrite($output, $message . "\n");
}

include('dbcon.php');

// Check database connection
if (mysqli_connect_errno()) {
    log_message("Database connection failed: " . mysqli_connect_error());
    die();
}
log_message("Database connection successful");

// Show all tables
$result = mysqli_query($con, "SHOW TABLES");
if (!$result) {
    log_message("Error listing tables: " . mysqli_error($con));
    die();
}

log_message("\nTables in database:");
while ($row = mysqli_fetch_row($result)) {
    log_message("- " . $row[0]);
}

// Check grades table structure
$result = mysqli_query($con, "DESCRIBE grades");
if (!$result) {
    log_message("\nError describing grades table: " . mysqli_error($con));
} else {
    log_message("\nGrades table structure:");
    while ($row = mysqli_fetch_assoc($result)) {
        log_message("- " . $row['Field'] . " " . $row['Type']);
    }
}

// Check schedules table structure
$result = mysqli_query($con, "DESCRIBE schedules");
if (!$result) {
    log_message("\nError describing schedules table: " . mysqli_error($con));
} else {
    log_message("\nSchedules table structure:");
    while ($row = mysqli_fetch_assoc($result)) {
        log_message("- " . $row['Field'] . " " . $row['Type']);
    }
}

mysqli_close($con);
fclose($output);
?>