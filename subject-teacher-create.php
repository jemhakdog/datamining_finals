<?php
session_start();
require 'dbcon.php';

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$is_admin = $_SESSION['role'] === 'admin';
$is_teacher = $_SESSION['role'] === 'teacher';

if (!$is_admin && !$is_teacher) {
    header("Location: index.php");
    exit();
}

$page_title = $is_admin ? "Assign Teacher to Subject" : "Request Subject";
include('header.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
    $teacher_id = mysqli_real_escape_string($con, $_POST['teacher_id']);
    
    // For admin: full schedule creation
    if ($is_admin && isset($_POST['create_schedule'])) {
        $academic_year = mysqli_real_escape_string($con, $_POST['academic_year']);
        $semester = mysqli_real_escape_string($con, $_POST['semester']);
        $day_of_week = mysqli_real_escape_string($con, $_POST['day_of_week']);
        $start_time = mysqli_real_escape_string($con, $_POST['start_time']);
        $end_time = mysqli_real_escape_string($con, $_POST['end_time']);
        $room = mysqli_real_escape_string($con, $_POST['room']);

        // Check if schedule exists
        $check_query = "SELECT schedule_id FROM schedules 
                       WHERE subject_id = '$subject_id' 
                       AND academic_year = '$academic_year' 
                       AND semester = '$semester'";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['message'] = "This subject already has a schedule for the selected academic year and semester.";
            $_SESSION['message_type'] = "danger";
        } else {
            // Begin transaction
            mysqli_begin_transaction($con);
            
            try {
                // Insert into subject_teachers with approved status
                $st_query = "INSERT INTO subject_teachers (subject_id, teacher_id, status) 
                            VALUES ('$subject_id', '$teacher_id', 'approved')";
                mysqli_query($con, $st_query);

                // Create schedule
                $schedule_query = "INSERT INTO schedules (subject_id, teacher_id, academic_year, semester, day_of_week, start_time, end_time, room) 
                                 VALUES ('$subject_id', '$teacher_id', '$academic_year', '$semester', '$day_of_week', '$start_time', '$end_time', '$room')";
                mysqli_query($con, $schedule_query);

                mysqli_commit($con);
                $_SESSION['message'] = "Teacher assigned to subject successfully";
                $_SESSION['message_type'] = "success";
                header("Location: subject_teachers.php");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($con);
                $_SESSION['message'] = "Error assigning teacher to subject";
                $_SESSION['message_type'] = "danger";
            }
        }
    }
    // For teacher: subject request
    elseif ($is_teacher && isset($_POST['request_subject'])) {
        // Check if request already exists
        $check_query = "SELECT id FROM subject_teachers 
                       WHERE subject_id = '$subject_id' 
                       AND teacher_id = '$teacher_id'
                       AND status = 'pending'";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['message'] = "You have already requested this subject.";
            $_SESSION['message_type'] = "warning";
        } else {
            $query = "INSERT INTO subject_teachers (subject_id, teacher_id, status) 
                     VALUES ('$subject_id', '$teacher_id', 'pending')";
            $result = mysqli_query($con, $query);

            if ($result) {
                $_SESSION['message'] = "Subject request submitted successfully";
                $_SESSION['message_type'] = "success";
                header("Location: my_subjects.php");
                exit();
            } else {
                $_SESSION['message'] = "Error submitting subject request";
                $_SESSION['message_type'] = "danger";
            }
        }
    }
}
?>

<div class="container py-4">
    <?php include('message.php'); ?>

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <?= $is_admin ? "Assign Teacher to Subject" : "Request Subject" ?>
            </h4>
        </div>
        <div class="card-body">
            <form action="" method="POST" class="needs-validation" novalidate>
                <div class="row">
                    <?php if ($is_admin): ?>
                        <div class="col-md-6 mb-3">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select name="subject_id" class="form-select" required>
                                <option value="">Select Subject</option>
                                <?php
                                $subjects_query = "SELECT * FROM subjects ORDER BY subject_name";
                                $subjects_result = mysqli_query($con, $subjects_query);
                                while ($subject = mysqli_fetch_assoc($subjects_result)) {
                                    echo "<option value='" . $subject['subject_id'] . "'>" . 
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
                                    echo "<option value='" . $teacher['id'] . "'>" . 
                                         htmlspecialchars($teacher['name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="academic_year" class="form-label">Academic Year</label>
                            <input type="text" name="academic_year" class="form-control" 
                                   placeholder="e.g., 2023-2024" required
                                   pattern="\d{4}-\d{4}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="">Select Semester</option>
                                <option value="First">First</option>
                                <option value="Second">Second</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="day_of_week" class="form-label">Day</label>
                            <select name="day_of_week" class="form-select" required>
                                <option value="">Select Day</option>
                                <option value="0">Sunday</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="room" class="form-label">Room</label>
                            <input type="text" name="room" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="teacher_id" value="<?= $_SESSION['ref_id'] ?>">
                        <input type="hidden" name="subject_id" value="<?= htmlspecialchars($_POST['subject_id'] ?? '') ?>">
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <?php if ($is_admin): ?>
                        <button type="submit" name="create_schedule" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Assign Teacher
                        </button>
                        <a href="subject_teachers.php" class="btn btn-secondary">Cancel</a>
                    <?php else: ?>
                        <button type="submit" name="request_subject" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Submit Request
                        </button>
                        <a href="my_subjects.php" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
