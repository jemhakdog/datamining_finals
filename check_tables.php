<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbcon.php');

// Function to describe table
function describe_table($con, $table_name) {
    echo "\nStructure of $table_name table:\n";
    $result = mysqli_query($con, "DESCRIBE $table_name");
    if (!$result) {
        echo "Error getting structure: " . mysqli_error($con) . "\n";
        return;
    }
    while ($row = mysqli_fetch_assoc($result)) {
        echo "{$row['Field']} - {$row['Type']} - {$row['Key']}\n";
    }
}

// Check referenced tables
describe_table($con, 'class');
describe_table($con, 'students');
describe_table($con, 'subjects');
describe_table($con, 'teachers');

mysqli_close($con);
?>