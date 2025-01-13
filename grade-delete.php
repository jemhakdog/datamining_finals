<?php
session_start();
require 'dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $grade_id = mysqli_real_escape_string($con, $_GET['id']);
    $subject_id = isset($_GET['subject_id']) ? mysqli_real_escape_string($con, $_GET['subject_id']) : null;

    $query = "DELETE FROM grades WHERE grade_id='$grade_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Grade deleted successfully";
    } else {
        $_SESSION['message'] = "Error deleting grade: " . mysqli_error($con);
    }

    // Redirect back to subject view if subject_id is provided, otherwise to grades page
    if ($subject_id) {
        header("Location: view_subject_students.php?id=" . $subject_id);
    } else {
        header("Location: grades.php");
    }
    exit(0);
} else {
    header("Location: grades.php");
    exit(0);
}
?>