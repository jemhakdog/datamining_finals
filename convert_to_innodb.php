<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbcon.php');

// Convert existing tables to InnoDB
$tables = ['class', 'students', 'subjects', 'teachers'];

foreach ($tables as $table) {
    $query = "ALTER TABLE $table ENGINE = InnoDB";
    echo "Converting $table to InnoDB...\n";
    if (mysqli_query($con, $query)) {
        echo "$table converted successfully\n";
    } else {
        echo "Error converting $table: " . mysqli_error($con) . "\n";
    }
}

// Verify conversion
foreach ($tables as $table) {
    $result = mysqli_query($con, "SHOW CREATE TABLE $table");
    $row = mysqli_fetch_assoc($result);
    echo "\nVerifying $table engine:\n";
    echo $row['Create Table'] . "\n";
}

mysqli_close($con);
?>