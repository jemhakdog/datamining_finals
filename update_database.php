<?php
require 'dbcon.php';

$sql = file_get_contents('update_users.sql');

// Split SQL into individual queries
$queries = explode(';', $sql);

// Execute each query
foreach($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if (!mysqli_query($con, $query)) {
            die("Error executing query: " . mysqli_error($con));
        }
    }
}

echo "Database updated successfully!";
?>