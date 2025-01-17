<?php
session_start();
require 'dbcon.php';

// Check if user is logged in and is a student
if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['role'] === 'student') {
    $page_title = "Student Dashboard";
    include('header.php');
    $student_id = $_SESSION['ref_id'];
?>
    <div class="container py-4">
        <?php include('message.php'); ?>

        <!-- Student Info Card -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Dashboard</h4>
                        </div>
                        <?php
                            $student_query = "SELECT * FROM students WHERE id = $student_id";
                            $student_result = mysqli_query($con, $student_query);
                            if ($student = mysqli_fetch_assoc($student_result)) {
                                ?>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary">
                                        <i class="bi bi-mortarboard me-1"></i>
                                        <?= htmlspecialchars($student['course']) ?>
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-person-badge me-1"></i>
                                        ID: <?= $student_id ?>
                                    </span>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Subjects Section -->
        <div id="subjects">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">My Subjects</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Units</th>
                                        <th>Teacher</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subjects_query = "SELECT c.*, sub.*, t.name as teacher_name, t.email as teacher_email 
                                                     FROM class c
                                                     INNER JOIN subjects sub ON c.subject_id = sub.subject_id
                                                     LEFT JOIN schedules sch ON c.subject_id = sch.subject_id 
                                                     AND sch.academic_year = (SELECT MAX(academic_year) FROM schedules)
                                                     AND sch.semester = (
                                                         SELECT semester 
                                                         FROM schedules 
                                                         WHERE academic_year = (SELECT MAX(academic_year) FROM schedules)
                                                         ORDER BY schedule_id DESC 
                                                         LIMIT 1
                                                     )
                                                     LEFT JOIN teachers t ON t.id = sch.teacher_id
                                                     WHERE c.student_id = $student_id";
                                    $subjects_result = mysqli_query($con, $subjects_query);

                                    if(mysqli_num_rows($subjects_result) > 0) {
                                        while($subject = mysqli_fetch_assoc($subjects_result)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-book text-secondary me-2"></i>
                                                        <?= htmlspecialchars($subject['subject_name']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-clock me-1"></i>
                                                        <?= $subject['units'] ?> Units
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-person-workspace text-secondary me-2"></i>
                                                        <?= htmlspecialchars($subject['teacher_name']) ?>
                                                        <a href="mailto:<?= htmlspecialchars($subject['teacher_email']) ?>" 
                                                           class="btn btn-sm btn-link">
                                                            <i class="bi bi-envelope"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Enrolled
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No subjects enrolled yet</p>
                                                <a href="class-view.php" class="btn btn-sm btn-primary mt-2">
                                                    <i class="bi bi-plus-lg"></i> Enroll in Subjects
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grades Section -->
        <div id="grades">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">My Grades</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Term</th>
                                        <th>Academic Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $grades_query = "SELECT c.*, s.subject_name, g.grade_value, g.term, g.academic_year 
                                                   FROM class c
                                                   INNER JOIN subjects s ON c.subject_id = s.subject_id
                                                   LEFT JOIN grades g ON c.subject_id = g.subject_id AND c.student_id = g.student_id
                                                   WHERE c.student_id = $student_id
                                                   ORDER BY s.subject_name";
                                    $grades_result = mysqli_query($con, $grades_query);

                                    if(mysqli_num_rows($grades_result) > 0) {
                                        while($grade = mysqli_fetch_assoc($grades_result)) {
                                            $grade_color = 'secondary';
                                            $grade_display = 'Not Set';
                                            
                                            if(isset($grade['grade_value'])) {
                                                $grade_display = number_format($grade['grade_value'], 2);
                                                $grade_color = 'success';
                                                if($grade['grade_value'] < 75) {
                                                    $grade_color = 'danger';
                                                } elseif($grade['grade_value'] < 80) {
                                                    $grade_color = 'warning';
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-book text-secondary me-2"></i>
                                                        <?= htmlspecialchars($grade['subject_name']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $grade_color ?>">
                                                        <?= $grade_display ?>
                                                    </span>
                                                </td>
                                                <td><?= isset($grade['term']) ? htmlspecialchars($grade['term']) : 'Not Set' ?></td>
                                                <td><?= isset($grade['academic_year']) ? htmlspecialchars($grade['academic_year']) : 'Not Set' ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No subjects enrolled yet</p>
                                                <a href="class-view.php" class="btn btn-sm btn-primary mt-2">
                                                    <i class="bi bi-plus-lg"></i> Enroll in Subjects
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Section -->
        <div id="schedule">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">My Schedule</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $schedule_query = "SELECT c.*, s.subject_name, t.name as teacher_name,
                                                            sch.day_of_week, sch.start_time, sch.end_time, sch.room, 
                                                            sch.semester, sch.academic_year
                                                     FROM class c
                                                     INNER JOIN subjects s ON c.subject_id = s.subject_id
                                                     LEFT JOIN schedules sch ON c.subject_id = sch.subject_id 
                                                     AND sch.academic_year = (SELECT MAX(academic_year) FROM schedules)
                                                     AND sch.semester = (
                                                         SELECT semester 
                                                         FROM schedules 
                                                         WHERE academic_year = (SELECT MAX(academic_year) FROM schedules)
                                                         ORDER BY schedule_id DESC 
                                                         LIMIT 1
                                                     )
                                                     LEFT JOIN teachers t ON t.id = sch.teacher_id
                                                     WHERE c.student_id = $student_id
                                                     ORDER BY COALESCE(sch.day_of_week, 999), COALESCE(sch.start_time, '99:99:99')";
                                    $schedule_result = mysqli_query($con, $schedule_query);

                                    if(mysqli_num_rows($schedule_result) > 0) {
                                        while($schedule = mysqli_fetch_assoc($schedule_result)) {
                                            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                            $day_display = 'Not Set';
                                            $time_display = 'Not Set';
                                            $room_display = 'Not Set';
                                            $day_class = 'secondary';
                                            $time_class = 'secondary';
                                            $room_class = 'secondary';

                                            // Process day
                                            if(isset($schedule['day_of_week']) && $schedule['day_of_week'] !== null) {
                                                $day_index = (int)$schedule['day_of_week'];
                                                // Handle both 0-based and 1-based indexing
                                                if($day_index >= 0 && $day_index <= 6) {
                                                    $day_display = $days[$day_index];
                                                } elseif($day_index >= 1 && $day_index <= 7) {
                                                    $day_display = $days[$day_index - 1];
                                                }
                                                $day_class = 'info';
                                            }

                                            // Process time
                                            if(isset($schedule['start_time']) && isset($schedule['end_time'])) {
                                                $time_display = date('h:i A', strtotime($schedule['start_time'])) . ' - ' . 
                                                              date('h:i A', strtotime($schedule['end_time']));
                                                $time_class = 'light text-dark';
                                            }

                                            // Process room
                                            if(isset($schedule['room']) && !empty($schedule['room'])) {
                                                $room_display = $schedule['room'];
                                                $room_class = 'light text-dark';
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-book text-secondary me-2"></i>
                                                        <?= htmlspecialchars($schedule['subject_name']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $day_class ?>">
                                                        <?= $day_display ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $time_class ?>">
                                                        <?= $time_display ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $room_class ?>">
                                                        <?php if($room_display !== 'Not Set'): ?>
                                                            <i class="bi bi-door-closed me-1"></i>
                                                        <?php endif; ?>
                                                        <?= $room_display ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No subjects enrolled yet</p>
                                                <a href="class-view.php" class="btn btn-sm btn-primary mt-2">
                                                    <i class="bi bi-plus-lg"></i> Enroll in Subjects
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
<?php 
} else {
    header("Location: index.php");
    exit();
}
?>
