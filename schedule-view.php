<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$page_title = "View Schedule";
include('dbcon.php');
include('header.php');

if (!isset($_GET['id'])) {
    header('Location: schedules.php');
    exit;
}

$schedule_id = mysqli_real_escape_string($con, $_GET['id']);

// Get schedule data with related information
$query = "SELECT s.*, sub.subject_name, sub.units, sub.course,
          t.name as teacher_name, t.email as teacher_email, t.department
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
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
?>

<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-calendar3"></i> Schedule Details
            </h4>
            <div>
                <a href="schedule-edit.php?id=<?= $schedule_id ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="schedules.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Subject Information</h5>
                    <table class="table">
                        <tr>
                            <th>Subject Name:</th>
                            <td><?= htmlspecialchars($schedule['subject_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Units:</th>
                            <td><?= htmlspecialchars($schedule['units']) ?></td>
                        </tr>
                        <tr>
                            <th>Course:</th>
                            <td><?= htmlspecialchars($schedule['course']) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Teacher Information</h5>
                    <table class="table">
                        <tr>
                            <th>Name:</th>
                            <td><?= htmlspecialchars($schedule['teacher_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= htmlspecialchars($schedule['teacher_email']) ?></td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td><?= htmlspecialchars($schedule['department']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="card-title">Schedule Information</h5>
                    <table class="table">
                        <tr>
                            <th>Day:</th>
                            <td><?= $days[$schedule['day_of_week']] ?></td>
                        </tr>
                        <tr>
                            <th>Time:</th>
                            <td>
                                <?= date('h:i A', strtotime($schedule['start_time'])) ?> - 
                                <?= date('h:i A', strtotime($schedule['end_time'])) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Room:</th>
                            <td><?= htmlspecialchars($schedule['room']) ?></td>
                        </tr>
                        <tr>
                            <th>Semester:</th>
                            <td><?= htmlspecialchars($schedule['semester']) ?> Semester</td>
                        </tr>
                        <tr>
                            <th>Academic Year:</th>
                            <td><?= htmlspecialchars($schedule['academic_year']) ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td><?= date('F j, Y g:i A', strtotime($schedule['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php
            // Get enrolled students in this subject
            $students_query = "SELECT s.name, s.email, s.course
                             FROM students s
                             JOIN class c ON s.id = c.student_id
                             WHERE c.subject_id = '{$schedule['subject_id']}'
                             ORDER BY s.name";
            $students_result = mysqli_query($con, $students_query);
            
            if (mysqli_num_rows($students_result) > 0):
            ?>
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="card-title">Enrolled Students</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['name']) ?></td>
                                    <td><?= htmlspecialchars($student['email']) ?></td>
                                    <td><?= htmlspecialchars($student['course']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>