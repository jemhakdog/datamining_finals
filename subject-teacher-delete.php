<?php
session_start();
require 'dbcon.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $schedule_id = mysqli_real_escape_string($con, $_GET['id']);

    // First, get the schedule details for the message
    $details_query = "SELECT s.subject_name, t.name as teacher_name, sch.academic_year, sch.semester
                     FROM schedules sch
                     INNER JOIN subjects s ON sch.subject_id = s.subject_id
                     INNER JOIN teachers t ON sch.teacher_id = t.id
                     WHERE sch.schedule_id = '$schedule_id'";
    $details_result = mysqli_query($con, $details_query);
    
    if (mysqli_num_rows($details_result) > 0) {
        $schedule = mysqli_fetch_assoc($details_result);
        
        // Check if there are any grades associated with this schedule
        $grades_query = "SELECT COUNT(*) as grade_count 
                        FROM grades g
                        INNER JOIN schedules sch ON g.subject_id = sch.subject_id 
                        AND g.academic_year = sch.academic_year 
                        AND g.semester = sch.semester
                        WHERE sch.schedule_id = '$schedule_id'";
        $grades_result = mysqli_query($con, $grades_query);
        $grades_count = mysqli_fetch_assoc($grades_result)['grade_count'];

        if ($grades_count > 0) {
            $_SESSION['message'] = "Cannot delete this assignment because there are grades associated with it.";
            $_SESSION['message_type'] = "danger";
        } else {
            // Proceed with deletion
            $query = "DELETE FROM schedules WHERE schedule_id = '$schedule_id'";
            $result = mysqli_query($con, $query);

            if ($result) {
                $_SESSION['message'] = sprintf(
                    "Successfully removed %s from %s (%s, %s)",
                    htmlspecialchars($schedule['teacher_name']),
                    htmlspecialchars($schedule['subject_name']),
                    htmlspecialchars($schedule['academic_year']),
                    htmlspecialchars($schedule['semester'])
                );
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error deleting subject teacher assignment";
                $_SESSION['message_type'] = "danger";
            }
        }
    } else {
        $_SESSION['message'] = "Schedule not found";
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid request";
    $_SESSION['message_type'] = "danger";
}

header("Location: subject_teachers.php");
exit();
?>
