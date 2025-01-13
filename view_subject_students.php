<?php
session_start();
require 'dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: grades.php');
    exit;
}

$subject_id = mysqli_real_escape_string($con, $_GET['id']);
$query = "SELECT * FROM subjects WHERE subject_id = '$subject_id'";
$result = mysqli_query($con, $query);
$subject = mysqli_fetch_assoc($result);

if (!$subject) {
    header('Location: grades.php');
    exit;
}

$page_title = "Grade Management - " . $subject['subject_name'];
include('header.php');
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-journal-text text-primary me-2"></i>
                <?= htmlspecialchars($subject['subject_name']) ?>
            </h4>
            <p class="text-muted mb-0 mt-1">
                Manage student grades for this subject
            </p>
        </div>
        <div>
            <a href="grades.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back to Subjects
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkGradeModal">
                <i class="bi bi-plus-circle"></i> Bulk Add Grades
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">#ID</th>
                        <th width="20%">Student Name</th>
                        <th width="15%">Grade</th>
                        <th width="15%">Term</th>
                        <th width="15%">Academic Year</th>
                        <th width="15%">Last Updated</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT DISTINCT s.*,
                                    c.class_id,
                                    g.grade_id, g.grade_value, g.term, g.academic_year, g.updated_at,
                                    CASE WHEN c.class_id IS NOT NULL THEN 'Enrolled' ELSE 'Not Enrolled' END as enrollment_status
                             FROM students s
                             LEFT JOIN class c ON s.id = c.student_id AND c.subject_id = '$subject_id'
                             LEFT JOIN grades g ON s.id = g.student_id AND g.subject_id = '$subject_id'
                             ORDER BY s.name";
                    $result = mysqli_query($con, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td class="align-middle"><?= $row['id'] ?></td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle text-secondary me-2"></i>
                                    <?= htmlspecialchars($row['name']) ?>
                                </div>
                            </td>
                            <td class="align-middle">
                                <?php if ($row['enrollment_status'] === 'Enrolled'): ?>
                                    <?php if (isset($row['grade_value'])): ?>
                                        <span class="badge bg-success"><?= htmlspecialchars($row['grade_value']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Not graded</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not enrolled</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <?php if ($row['enrollment_status'] === 'Enrolled'): ?>
                                    <?= !empty($row['term']) ? htmlspecialchars($row['term']) : '<span class="text-muted">Not set</span>' ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <?php if ($row['enrollment_status'] === 'Enrolled'): ?>
                                    <?= !empty($row['academic_year']) ? htmlspecialchars($row['academic_year']) : '<span class="text-muted">Not set</span>' ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <?php if ($row['enrollment_status'] === 'Enrolled'): ?>
                                    <?= !empty($row['updated_at']) ? date('M d, Y', strtotime($row['updated_at'])) : '<span class="text-muted">Not set</span>' ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <?php if ($row['enrollment_status'] === 'Enrolled'): ?>
                                    <?php if (isset($row['grade_id'])): ?>
                                        <div class="btn-group" role="group">
                                            <button type="button"
                                                    class="btn btn-sm btn-primary edit-grade"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editGradeModal"
                                                    data-student-id="<?= $row['id'] ?>"
                                                    data-student-name="<?= htmlspecialchars($row['name']) ?>"
                                                    data-grade-id="<?= $row['grade_id'] ?>"
                                                    data-grade="<?= htmlspecialchars($row['grade_value']) ?>"
                                                    data-term="<?= htmlspecialchars($row['term']) ?>"
                                                    data-year="<?= htmlspecialchars($row['academic_year']) ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger delete-grade"
                                                    data-grade-id="<?= $row['grade_id'] ?>"
                                                    data-student-name="<?= htmlspecialchars($row['name']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <button type="button"
                                                class="btn btn-sm btn-success edit-grade"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editGradeModal"
                                                data-student-id="<?= $row['id'] ?>"
                                                data-student-name="<?= htmlspecialchars($row['name']) ?>">
                                            <i class="bi bi-plus-circle"></i> Add Grade
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="class-view.php?id=<?= $subject_id ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-person-plus"></i> Manage Enrollment
                                    </a>
                                <?php endif; ?>
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

<!-- Edit/Add Grade Modal -->
<div class="modal fade" id="editGradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> <span id="modalTitle">Add Grade</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="gradeForm" action="code.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="grade_id" id="gradeId">
                    <input type="hidden" name="student_id" id="studentId">
                    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="studentName" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Grade</label>
                        <input type="number" class="form-control" name="grade_value" id="gradeValue" required step="0.01" min="0" max="100">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Term</label>
                        <select class="form-select" name="term" id="term" required>
                            <option value="First">First</option>
                            <option value="Second">Second</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" class="form-control" name="academic_year" id="academicYear" required 
                               pattern="\d{4}-\d{4}" placeholder="YYYY-YYYY">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="save_grade">Save Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Grade Modal -->
<div class="modal fade" id="bulkGradeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Bulk Add Grades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkGradeForm" action="code.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Term</label>
                            <select class="form-select" name="bulk_term" required>
                                <option value="First">First</option>
                                <option value="Second">Second</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Academic Year</label>
                            <input type="text" class="form-control" name="bulk_academic_year" 
                                   pattern="\d{4}-\d{4}" placeholder="YYYY-YYYY" required>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th width="150px">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get enrolled students with their current grades
                                $enrolled_query = "SELECT DISTINCT s.id, s.name, g.grade_value,
                                                        CASE WHEN c.class_id IS NOT NULL THEN 'Enrolled' ELSE 'Not Enrolled' END as enrollment_status
                                                 FROM students s
                                                 LEFT JOIN class c ON s.id = c.student_id AND c.subject_id = '$subject_id'
                                                 LEFT JOIN grades g ON s.id = g.student_id AND g.subject_id = '$subject_id'
                                                 ORDER BY s.name";
                                $enrolled_result = mysqli_query($con, $enrolled_query);
                                
                                while ($row = mysqli_fetch_assoc($enrolled_result)) {
                                    ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle text-secondary me-2"></i>
                                                <?= htmlspecialchars($row['name']) ?>
                                                <?php if ($row['enrollment_status'] !== 'Enrolled'): ?>
                                                    <span class="badge bg-secondary ms-2">Not enrolled</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($row['enrollment_status'] === 'Enrolled'): ?>
                                                <input type="number" class="form-control form-control-sm"
                                                       name="grades[<?= $row['id'] ?>]"
                                                       value="<?= isset($row['grade_value']) ? $row['grade_value'] : '' ?>"
                                                       step="0.01" min="0" max="100">
                                            <?php else: ?>
                                                <input type="number" class="form-control form-control-sm" disabled
                                                       placeholder="Not enrolled">
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="save_bulk_grades">Save All Grades</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit Grade Modal
    const editGradeModal = document.getElementById('editGradeModal');
    editGradeModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const studentId = button.dataset.studentId;
        const studentName = button.dataset.studentName;
        const gradeId = button.dataset.gradeId;
        const grade = button.dataset.grade;
        const term = button.dataset.term;
        const year = button.dataset.year;
        
        document.getElementById('modalTitle').textContent = gradeId ? 'Edit Grade' : 'Add Grade';
        document.getElementById('gradeId').value = gradeId || '';
        document.getElementById('studentId').value = studentId;
        document.getElementById('studentName').value = studentName;
        document.getElementById('gradeValue').value = grade || '';
        document.getElementById('term').value = term || 'First';
        document.getElementById('academicYear').value = year || '';
    });

    // Delete Grade
    const deleteButtons = document.querySelectorAll('.delete-grade');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const gradeId = this.dataset.gradeId;
            const studentName = this.dataset.studentName;
            
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
                    window.location.href = `grade-delete.php?id=${gradeId}&subject_id=<?= $subject_id ?>`;
                }
            });
        });
    });
});
</script>

<?php include('footer.php'); ?>