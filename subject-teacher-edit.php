<?php
session_start();
require 'dbcon.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$page_title = "Edit Subject Teacher Assignment";
include('header.php');

// Get the schedule record
if (isset($_GET['id'])) {
    $schedule_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT sch.*, s.subject_name, t.name as teacher_name 
              FROM schedules sch
              INNER JOIN subjects s ON sch.subject_id = s.subject_id
              INNER JOIN teachers t ON sch.teacher_id = t.id
              WHERE sch.schedule_id = '$schedule_id'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $schedule = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = "No such schedule found";
        $_SESSION['message_type'] = "danger";
        header("Location: subject_teachers.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request";
    $_SESSION['message_type'] = "danger";
    header("Location: subject_teachers.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
    $teacher_id = mysqli_real_escape_string($con, $_POST['teacher_id']);
    $academic_year = mysqli_real_escape_string($con, $_POST['academic_year']);
    $semester = mysqli_real_escape_string($con, $_POST['semester']);
    $day_of_week = mysqli_real_escape_string($con, $_POST['day_of_week']);
    $start_time = mysqli_real_escape_string($con, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($con, $_POST['end_time']);
    $room = mysqli_real_escape_string($con, $_POST['room']);

    // Check if another schedule exists for this subject in the same semester (excluding current record)
    $check_query = "SELECT schedule_id FROM schedules 
                   WHERE subject_id = '$subject_id' 
                   AND academic_year = '$academic_year' 
                   AND semester = '$semester'
                   AND schedule_id != '$schedule_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "This subject already has a different schedule for the selected academic year and semester.";
        $_SESSION['message_type'] = "danger";
    } else {
        $query = "UPDATE schedules SET 
                 subject_id = '$subject_id',
                 teacher_id = '$teacher_id',
                 academic_year = '$academic_year',
                 semester = '$semester',
                 day_of_week = '$day_of_week',
                 start_time = '$start_time',
                 end_time = '$end_time',
                 room = '$room'
                 WHERE schedule_id = '$schedule_id'";
        $result = mysqli_query($con, $query);

        if ($result) {
            $_SESSION['message'] = "Subject teacher assignment updated successfully";
            $_SESSION['message_type'] = "success";
            header("Location: subject_teachers.php");
            exit();
        } else {
            $_SESSION['message'] = "Error updating subject teacher assignment";
            $_SESSION['message_type'] = "danger";
        }
    }
}
?>

<div class="container py-4">
    <?php include('message.php'); ?>

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Subject Teacher Assignment</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            <?php
                            $subjects_query = "SELECT * FROM subjects ORDER BY subject_name";
                            $subjects_result = mysqli_query($con, $subjects_query);
                            while ($subject = mysqli_fetch_assoc($subjects_result)) {
                                $selected = ($subject['subject_id'] == $schedule['subject_id']) ? 'selected' : '';
                                echo "<option value='" . $subject['subject_id'] . "' $selected>" . 
                                     htmlspecialchars($subject['subject_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">Select Teacher</option>
                            <?php
                            $teachers_query = "SELECT * FROM teachers ORDER BY name";
                            $teachers_result = mysqli_query($con, $teachers_query);
                            while ($teacher = mysqli_fetch_assoc($teachers_result)) {
                                $selected = ($teacher['id'] == $schedule['teacher_id']) ? 'selected' : '';
                                echo "<option value='" . $teacher['id'] . "' $selected>" . 
                                     htmlspecialchars($teacher['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <input type="text" name="academic_year" class="form-control" 
                               value="<?= htmlspecialchars($schedule['academic_year']) ?>"
                               placeholder="e.g., 2023-2024" required
                               pattern="\d{4}-\d{4}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="">Select Semester</option>
                            <?php
                            $semesters = ['First', 'Second', 'Summer'];
                            foreach ($semesters as $sem) {
                                $selected = ($sem == $schedule['semester']) ? 'selected' : '';
                                echo "<option value='$sem' $selected>$sem</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="day_of_week" class="form-label">Day</label>
                        <select name="day_of_week" class="form-select" required>
                            <option value="">Select Day</option>
                            <?php
                            $days = [
                                0 => 'Sunday',
                                1 => 'Monday',
                                2 => 'Tuesday',
                                3 => 'Wednesday',
                                4 => 'Thursday',
                                5 => 'Friday',
                                6 => 'Saturday'
                            ];
                            foreach ($days as $value => $day) {
                                $selected = ($value == $schedule['day_of_week']) ? 'selected' : '';
                                echo "<option value='$value' $selected>$day</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="room" class="form-label">Room</label>
                        <input type="text" name="room" class="form-control" 
                               value="<?= htmlspecialchars($schedule['room']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" 
                               value="<?= htmlspecialchars($schedule['start_time']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" 
                               value="<?= htmlspecialchars($schedule['end_time']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Assignment
                    </button>
                    <a href="subject_teachers.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
