<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$page_title = "Grade Management";
include('dbcon.php');
include('header.php');
?>

<div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-award-fill"></i> Grade Management
            </h4>
            <a href="grade-create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Grade
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="10%">#ID</th>
                            <th width="25%">Subject Name</th>
                            <th width="20%">Course</th>
                            <th width="20%">Grading Progress</th>
                            <th width="25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT s.*,
                                        (SELECT COUNT(*) FROM students st
                                         JOIN grades g ON st.id = g.student_id
                                         WHERE g.subject_id = s.subject_id) as graded_students,
                                        (SELECT COUNT(*) FROM students) as total_students
                                 FROM subjects s
                                 ORDER BY s.subject_name";
                        $result = mysqli_query($con, $query);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $progress = ($row['total_students'] > 0)
                                    ? round(($row['graded_students'] / $row['total_students']) * 100)
                                    : 0;
                                ?>
                                <tr>
                                    <td class="align-middle"><?= $row['subject_id'] ?></td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-journal-text text-secondary me-2"></i>
                                            <?= htmlspecialchars($row['subject_name']) ?>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            <?= htmlspecialchars($row['course']) ?>
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-success"
                                                     role="progressbar"
                                                     style="width: <?= $progress ?>%"
                                                     aria-valuenow="<?= $progress ?>"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="ms-2 small">
                                                <?= $row['graded_students'] ?>/<?= $row['total_students'] ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="view_subject_students.php?id=<?= $row['subject_id'] ?>"
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-people-fill me-1"></i> View Students
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-grade');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const gradeId = this.dataset.id;
            const studentName = this.dataset.student;
            
            Swal.fire({
                title: 'Delete Grade?',
                text: `Are you sure you want to delete the grade for ${studentName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `grade-delete.php?id=${gradeId}`;
                }
            });
        });
    });
});
</script>

<?php include('footer.php'); ?>