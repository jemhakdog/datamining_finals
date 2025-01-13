<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$page_title = "Edit Grade";
include('dbcon.php');
include('header.php');

if (!isset($_GET['id'])) {
    header('Location: grades.php');
    exit;
}

$grade_id = mysqli_real_escape_string($con, $_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade_value = mysqli_real_escape_string($con, $_POST['grade_value']);
    $term = mysqli_real_escape_string($con, $_POST['term']);
    $academic_year = mysqli_real_escape_string($con, $_POST['academic_year']);

    $query = "UPDATE grades SET 
              grade_value = '$grade_value',
              term = '$term',
              academic_year = '$academic_year'
              WHERE grade_id = '$grade_id'";

    if (mysqli_query($con, $query)) {
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Grade updated successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'grades.php';
                    }
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Error updating grade: " . mysqli_error($con) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}

// Get current grade data
$query = "SELECT g.*, s.name as student_name, sub.subject_name 
          FROM grades g
          JOIN students s ON g.student_id = s.id
          JOIN subjects sub ON g.subject_id = sub.subject_id
          WHERE g.grade_id = '$grade_id'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) === 0) {
    header('Location: grades.php');
    exit;
}

$grade = mysqli_fetch_assoc($result);
?>

<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="bi bi-pencil"></i> Edit Grade
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($grade['student_name']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($grade['subject_name']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="grade_value" class="form-label">Grade</label>
                    <input type="number" class="form-control" name="grade_value" 
                           value="<?= htmlspecialchars($grade['grade_value']) ?>"
                           step="0.01" min="0" max="100" required>
                </div>

                <div class="mb-3">
                    <label for="term" class="form-label">Term</label>
                    <select class="form-select" name="term" required>
                        <option value="">Select Term</option>
                        <?php
                        $terms = ['First', 'Second', 'Third', 'Fourth'];
                        foreach ($terms as $term) {
                            $selected = ($grade['term'] === $term) ? 'selected' : '';
                            echo "<option value='$term' $selected>$term Term</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <input type="text" class="form-control" name="academic_year" 
                           value="<?= htmlspecialchars($grade['academic_year']) ?>"
                           pattern="\d{4}-\d{4}" placeholder="YYYY-YYYY" required>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="grades.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Grade</button>
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
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
</body>
</html>