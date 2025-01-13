<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: schedules.php');
    exit;
}

require_once('dbcon.php');
$schedule_id = mysqli_real_escape_string($con, $_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
    $teacher_id = mysqli_real_escape_string($con, $_POST['teacher_id']);
    $day_of_week = mysqli_real_escape_string($con, $_POST['day_of_week']);
    $start_time = mysqli_real_escape_string($con, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($con, $_POST['end_time']);
    $room = mysqli_real_escape_string($con, $_POST['room']);
    $semester = mysqli_real_escape_string($con, $_POST['semester']);
    $academic_year = mysqli_real_escape_string($con, $_POST['academic_year']);

    // Check for schedule conflicts excluding current schedule
    $conflict_query = "SELECT s.*, sub.subject_name, t.name as teacher_name
                      FROM schedules s
                      JOIN subjects sub ON s.subject_id = sub.subject_id
                      JOIN teachers t ON s.teacher_id = t.id
                      WHERE s.day_of_week = '$day_of_week'
                      AND s.schedule_id != '$schedule_id'
                      AND (
                          (s.start_time BETWEEN '$start_time' AND '$end_time')
                          OR (s.end_time BETWEEN '$start_time' AND '$end_time')
                          OR ('$start_time' BETWEEN s.start_time AND s.end_time)
                      )
                      AND (s.room = '$room' OR s.teacher_id = '$teacher_id')";
    
    $conflict_result = mysqli_query($con, $conflict_query);

    if (mysqli_num_rows($conflict_result) > 0) {
        $conflict = mysqli_fetch_assoc($conflict_result);
        $conflict_message = "Schedule conflicts with: " . 
                          htmlspecialchars($conflict['subject_name']) . 
                          " by " . htmlspecialchars($conflict['teacher_name']);
        
        $page_title = "Edit Schedule";
        include('header.php');
        echo "<script>
                Swal.fire({
                    title: 'Schedule Conflict!',
                    text: '$conflict_message',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    } else {
        $query = "UPDATE schedules SET 
                  subject_id = '$subject_id',
                  teacher_id = '$teacher_id',
                  day_of_week = '$day_of_week',
                  start_time = '$start_time',
                  end_time = '$end_time',
                  room = '$room',
                  semester = '$semester',
                  academic_year = '$academic_year'
                  WHERE schedule_id = '$schedule_id'";

        if (mysqli_query($con, $query)) {
            ob_clean();
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Schedule updated successfully',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = 'schedules.php';
                    });
                </script>
            </body>
            </html>
            <?php
            exit;
        } else {
            $page_title = "Edit Schedule";
            include('header.php');
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error updating schedule: " . mysqli_error($con) . "',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    }
}

$page_title = "Edit Schedule";
include('header.php');

// Get current schedule data
$query = "SELECT s.*, sub.subject_name, t.name as teacher_name 
          FROM schedules s
          JOIN subjects sub ON s.subject_id = sub.subject_id
          JOIN teachers t ON s.teacher_id = t.id
          WHERE s.schedule_id = '$schedule_id'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) === 0) {
    header('Location: schedules.php');
    exit;
}

$schedule = mysqli_fetch_assoc($result);
?>

<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="bi bi-pencil"></i> Edit Schedule
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select" name="subject_id" required>
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

                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Teacher</label>
                    <select class="form-select" name="teacher_id" required>
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

                <div class="mb-3">
                    <label for="day_of_week" class="form-label">Day</label>
                    <select class="form-select" name="day_of_week" required>
                        <option value="">Select Day</option>
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        foreach ($days as $index => $day) {
                            $selected = ($index == $schedule['day_of_week']) ? 'selected' : '';
                            echo "<option value='$index' $selected>$day</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" name="start_time" 
                               value="<?= htmlspecialchars($schedule['start_time']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" name="end_time" 
                               value="<?= htmlspecialchars($schedule['end_time']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="room" class="form-label">Room</label>
                    <input type="text" class="form-control" name="room" 
                           value="<?= htmlspecialchars($schedule['room']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select" name="semester" required>
                        <option value="">Select Semester</option>
                        <?php
                        $semesters = ['First', 'Second', 'Summer'];
                        foreach ($semesters as $sem) {
                            $selected = ($schedule['semester'] === $sem) ? 'selected' : '';
                            echo "<option value='$sem' $selected>$sem Semester</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <input type="text" class="form-control" name="academic_year" 
                           value="<?= htmlspecialchars($schedule['academic_year']) ?>"
                           pattern="\d{4}-\d{4}" placeholder="YYYY-YYYY" required>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="schedules.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const startTime = document.querySelector('input[name="start_time"]').value;
            const endTime = document.querySelector('input[name="end_time"]').value;
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
            } else if (startTime >= endTime) {
                event.preventDefault();
                Swal.fire({
                    title: 'Invalid Time!',
                    text: 'End time must be after start time',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }, false)
    })
})()
</script>
<?php include('footer.php'); ?>