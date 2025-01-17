`   <?php
session_start();
require 'dbcon.php';

// Check if user is logged in and is a teacher
if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['role'] === 'teacher') {
    $page_title = "My Subjects";
    include('header.php');
    $teacher_id = $_SESSION['ref_id'];
?>
    <div class="container py-4">
        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="bi bi-journal-text text-primary me-2"></i>
                            My Subjects
                        </h4>
                        <p class="text-muted mb-0 mt-1">Subjects assigned to you</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Units</th>
                                        <th>Course</th>
                                        <th>Academic Year</th>
                                        <th>Semester</th>
                                        <th>Enrolled Students</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get subjects assigned to this teacher with schedule information
                                    $subject_query = "SELECT s.*, sch.academic_year, sch.semester,
                                                    (SELECT COUNT(*) FROM class c WHERE c.subject_id = s.subject_id) as student_count
                                                    FROM subjects s 
                                                    INNER JOIN schedules sch ON s.subject_id = sch.subject_id
                                                    WHERE sch.teacher_id = $teacher_id
                                                    ORDER BY sch.academic_year DESC, sch.semester DESC, s.subject_name";
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
                                                <td><?= htmlspecialchars($subject['academic_year']) ?></td>
                                                <td><?= htmlspecialchars($subject['semester']) ?></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-people me-1"></i>
                                                        <?= $subject['student_count'] ?> Students
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="view_subject_students.php?subject_id=<?= $subject['subject_id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-people"></i> View Students
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
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
    </div>
</body>
</html>
<?php 
} else {
    header("Location: index.php");
    exit();
}
?>
