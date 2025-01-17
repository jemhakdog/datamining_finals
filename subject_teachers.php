<?php
session_start();
require 'dbcon.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$page_title = "Manage Subject Teachers";
include('header.php');
?>

<div class="container py-4">
    <?php include('message.php'); ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Subject Teachers</h4>
            <a href="subject-teacher-create.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Assign Teacher to Subject
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT s.subject_id, s.subject_name, t.id as teacher_id, t.name as teacher_name, 
                                       sch.schedule_id, sch.academic_year, sch.semester
                                FROM schedules sch
                                INNER JOIN subjects s ON sch.subject_id = s.subject_id
                                INNER JOIN teachers t ON sch.teacher_id = t.id
                                ORDER BY sch.academic_year DESC, sch.semester DESC, s.subject_name";
                        $result = mysqli_query($con, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-book text-secondary me-2"></i>
                                            <?= htmlspecialchars($row['subject_name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-workspace text-secondary me-2"></i>
                                            <?= htmlspecialchars($row['teacher_name']) ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['academic_year']) ?></td>
                                    <td><?= htmlspecialchars($row['semester']) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="subject-teacher-edit.php?id=<?= $row['schedule_id'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete(<?= $row['schedule_id'] ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0">No subject-teacher assignments found</p>
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

<script>
function confirmDelete(scheduleId) {
    if (confirm('Are you sure you want to remove this subject-teacher assignment?')) {
        window.location.href = 'subject-teacher-delete.php?id=' + scheduleId;
    }
}
</script>

</body>
</html>
