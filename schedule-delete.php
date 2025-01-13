<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include('dbcon.php');

if (isset($_GET['id'])) {
    $schedule_id = mysqli_real_escape_string($con, $_GET['id']);
    
    $query = "DELETE FROM schedules WHERE schedule_id = '$schedule_id'";
    
    if (mysqli_query($con, $query)) {
        header('Location: schedules.php');
    } else {
        echo "<script>
                alert('Error deleting schedule: " . mysqli_error($con) . "');
                window.location.href = 'schedules.php';
              </script>";
    }
} else {
    header('Location: schedules.php');
}
?>