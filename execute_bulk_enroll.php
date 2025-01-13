<?php
require 'dbcon.php';

// Read the SQL file
$sql = file_get_contents('bulk_enroll.sql');

// Execute the query
if (mysqli_multi_query($con, $sql)) {
    echo "Bulk enrollment completed successfully";
} else {
    echo "Error executing query: " . mysqli_error($con);
}

mysqli_close($con);
?>