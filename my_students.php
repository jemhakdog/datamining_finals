<?php
session_start();
require 'dbcon.php';

// Check if user is logged in and is a teacher
if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['role'] === 'teacher') {
    $page_title = "My Students";
    include('header.php');
    $teacher_id = $_SESSION['ref_id'];
?>
    <div class="container py-4">
        <?php include('message.php'); ?>

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
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
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
                                                     ORDER BY sub.subject_name, s.name";
                                    $students_result = mysqli_query($con, $students_query);

                                    if(mysqli_num_rows($students_result) > 0) {
                                        $current_subject = '';
                                        while($student = mysqli_fetch_assoc($students_result)) {
                                            // Add subject header if it's a new subject
                                            if ($current_subject != $student['subject_name']) {
                                                $current_subject = $student['subject_name'];
                                                ?>
                                                <tr class="table-light">
                                                    <td colspan="6" class="fw-bold">
                                                        <i class="bi bi-book-fill me-2"></i>
                                                        <?= htmlspecialchars($student['subject_name']) ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-person-circle text-secondary me-2"></i>
                                                        <?= htmlspecialchars($student['name']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-journal-text me-1"></i>
                                                        <?= htmlspecialchars($student['subject_name']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-mortarboard me-1"></i>
                                                        <?= htmlspecialchars($student['course']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?= htmlspecialchars($student['email']) ?>" 
                                                       class="text-decoration-none">
                                                        <i class="bi bi-envelope text-muted me-1"></i>
                                                        <?= htmlspecialchars($student['email']) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="tel:<?= htmlspecialchars($student['phone']) ?>" 
                                                       class="text-decoration-none">
                                                        <i class="bi bi-telephone text-muted me-1"></i>
                                                        <?= htmlspecialchars($student['phone']) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="mailto:<?= htmlspecialchars($student['email']) ?>" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Send Email">
                                                            <i class="bi bi-envelope"></i>
                                                        </a>
                                                        <a href="tel:<?= htmlspecialchars($student['phone']) ?>" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Call">
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
                                            <td colspan="6" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No students enrolled in your subjects</p>
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