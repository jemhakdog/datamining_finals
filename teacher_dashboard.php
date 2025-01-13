<?php
session_start();
require 'dbcon.php';

// Check if user is logged in and is a teacher
if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['role'] === 'teacher') {
    $page_title = "Teacher Dashboard";
    include('header.php');
    $teacher_id = $_SESSION['ref_id'];
?>
    <div class="container py-4">
        <?php include('message.php'); ?>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-person-workspace text-primary me-2"></i>
                                Welcome, <?= htmlspecialchars($_SESSION['name']) ?>
                            </h4>
                            <p class="text-muted mb-0 mt-1">Teacher Dashboard</p>
                        </div>
                        <div>
                            <span class="badge bg-primary">
                                <i class="bi bi-building me-1"></i>
                                <?php
                                    $dept_query = "SELECT department FROM teachers WHERE id = $teacher_id";
                                    $dept_result = mysqli_query($con, $dept_query);
                                    if ($dept_row = mysqli_fetch_assoc($dept_result)) {
                                        echo htmlspecialchars($dept_row['department']);
                                    }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-journal-text text-primary me-2"></i>
                                My Subjects
                            </h4>
                            <p class="text-muted mb-0 mt-1">Subjects assigned to you</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Units</th>
                                        <th>Course</th>
                                        <th>Enrolled Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get subjects for teacher's department
                                    $subject_query = "SELECT s.*, 
                                                    (SELECT COUNT(*) FROM class c WHERE c.subject_id = s.subject_id) as student_count
                                                    FROM subjects s 
                                                    INNER JOIN teachers t ON s.course = t.department 
                                                    WHERE t.id = $teacher_id";
                                    $subject_result = mysqli_query($con, $subject_query);

                                    if(mysqli_num_rows($subject_result) > 0) {
                                        while($subject = mysqli_fetch_assoc($subject_result)) {
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
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-people me-1"></i>
                                                        <?= $subject['student_count'] ?> Students
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
                                                <p class="text-muted mb-0">No subjects assigned yet</p>
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

        <!-- Students Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-people-fill text-primary me-2"></i>
                                My Students
                            </h4>
                            <p class="text-muted mb-0 mt-1">Students enrolled in your subjects</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Subject</th>
                                        <th>Course</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $students_query = "SELECT DISTINCT s.*, c.subject_id, sub.subject_name
                                                     FROM students s
                                                     INNER JOIN class c ON s.id = c.student_id
                                                     INNER JOIN subjects sub ON c.subject_id = sub.subject_id
                                                     INNER JOIN teachers t ON sub.course = t.department
                                                     WHERE t.id = $teacher_id
                                                     ORDER BY s.name";
                                    $students_result = mysqli_query($con, $students_query);

                                    if(mysqli_num_rows($students_result) > 0) {
                                        while($student = mysqli_fetch_assoc($students_result)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-person-circle text-secondary me-2"></i>
                                                        <?= htmlspecialchars($student['name']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-journal-text text-secondary me-2"></i>
                                                        <?= htmlspecialchars($student['subject_name']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-mortarboard me-1"></i>
                                                        <?= htmlspecialchars($student['course']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="mailto:<?= htmlspecialchars($student['email']) ?>" 
                                                           class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-envelope"></i>
                                                        </a>
                                                        <a href="tel:<?= htmlspecialchars($student['phone']) ?>" 
                                                           class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-telephone"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No students enrolled yet</p>
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