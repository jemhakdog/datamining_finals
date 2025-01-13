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
                            <h4 class="mb-0">
                                <i class="bi bi-person-circle text-primary me-2"></i>
                                Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
                            </h4>
                            <p class="text-muted mb-0 mt-1">Student Dashboard</p>
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
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-journal-text text-primary me-2"></i>
                                My Enrolled Subjects
                            </h4>
                            <p class="text-muted mb-0 mt-1">Current semester subjects</p>
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
                                    $subjects_query = "SELECT s.*, sub.*, t.name as teacher_name, t.email as teacher_email 
                                                     FROM class s
                                                     INNER JOIN subjects sub ON s.subject_id = sub.subject_id
                                                     INNER JOIN teachers t ON sub.course = t.department
                                                     WHERE s.student_id = $student_id";
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

        <!-- Available Subjects Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-journal-plus text-primary me-2"></i>
                                Available Subjects
                            </h4>
                            <p class="text-muted mb-0 mt-1">Subjects you can enroll in</p>
                        </div>
                        <a href="class-view.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Enroll Now
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Units</th>
                                        <th>Course</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get student's course
                                    $course_query = "SELECT course FROM students WHERE id = $student_id";
                                    $course_result = mysqli_query($con, $course_query);
                                    $course_row = mysqli_fetch_assoc($course_result);
                                    $student_course = $course_row['course'];

                                    // Get available subjects
                                    $available_query = "SELECT s.* 
                                                      FROM subjects s 
                                                      WHERE s.course = '$student_course'
                                                      AND s.subject_id NOT IN (
                                                          SELECT subject_id FROM class WHERE student_id = $student_id
                                                      )";
                                    $available_result = mysqli_query($con, $available_query);

                                    if(mysqli_num_rows($available_result) > 0) {
                                        while($subject = mysqli_fetch_assoc($available_result)) {
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
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-mortarboard me-1"></i>
                                                        <?= htmlspecialchars($subject['course']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="class-view.php?subject_id=<?= $subject['subject_id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-plus-lg"></i> Enroll
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No available subjects for enrollment</p>
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