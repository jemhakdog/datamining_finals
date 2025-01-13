<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbcon.php');

// Function to show create table statement
function show_create_table($con, $table_name) {
    echo "\nCreate statement for $table_name:\n";
    $result = mysqli_query($con, "SHOW CREATE TABLE $table_name");
    if (!$result) {
        echo "Error getting create statement: " . mysqli_error($con) . "\n";
        return;
    }
    $row = mysqli_fetch_assoc($result);
    echo $row['Create Table'] . "\n";
}

// Check all relevant tables
$tables = ['class', 'students', 'subjects', 'teachers'];
foreach ($tables as $table) {
    show_create_table($con, $table);
}

// Show all existing tables
echo "\nAll tables in database:\n";
$result = mysqli_query($con, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    echo "- " . $row[0] . "\n";
}

mysqli_close($con);
?>