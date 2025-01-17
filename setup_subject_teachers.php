<?php
require 'dbcon.php';

// Read the SQL file content
$sql = file_get_contents('create_subject_teachers.sql');

// Execute the SQL commands
if(mysqli_multi_query($con, $sql)) {
    echo "Subject teachers table created successfully!";
    
    // Clear out any remaining results
    while(mysqli_next_result($con)) {
        if($result = mysqli_store_result($con)) {
            mysqli_free_result($result);
        }
    }
} else {
    echo "Error creating table: " . mysqli_error($con);
}

mysqli_close($con);
?>
