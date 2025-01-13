<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$page_title = "Create Grade";
include('dbcon.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
    $grade_value = mysqli_real_escape_string($con, $_POST['grade_value']);
    $term = mysqli_real_escape_string($con, $_POST['term']);
    $academic_year = mysqli_real_escape_string($con, $_POST['academic_year']);

    // Get class_id from student and subject
    $class_query = "SELECT class_id FROM class WHERE student_id = '$student_id' AND subject_id = '$subject_id'";
    $class_result = mysqli_query($con, $class_query);
    
    if (mysqli_num_rows($class_result) > 0) {
        $class_row = mysqli_fetch_assoc($class_result);
        $class_id = $class_row['class_id'];

        $query = "INSERT INTO grades (class_id, student_id, subject_id, grade_value, term, academic_year) 
                 VALUES ('$class_id', '$student_id', '$subject_id', '$grade_value', '$term', '$academic_year')";
        
        if (mysqli_query($con, $query)) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Grade added successfully',
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
                        text: 'Error adding grade: " . mysqli_error($con) . "',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Student is not enrolled in this subject',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>

<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="bi bi-plus-circle"></i> Add New Grade
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-select" name="student_id" required>
                        <option value="">Select Student</option>
                        <?php
                        $students_query = "SELECT * FROM students ORDER BY name";
                        $students_result = mysqli_query($con, $students_query);
                        while ($student = mysqli_fetch_assoc($students_result)) {
                            echo "<option value='" . $student['id'] . "'>" . htmlspecialchars($student['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select" name="subject_id" required>
                        <option value="">Select Subject</option>
                        <?php
                        $subjects_query = "SELECT * FROM subjects ORDER BY subject_name";
                        $subjects_result = mysqli_query($con, $subjects_query);
                        while ($subject = mysqli_fetch_assoc($subjects_result)) {
                            echo "<option value='" . $subject['subject_id'] . "'>" . htmlspecialchars($subject['subject_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="grade_value" class="form-label">Grade</label>
                    <input type="number" class="form-control" name="grade_value" step="0.01" min="0" max="100" required>
                </div>

                <div class="mb-3">
                    <label for="term" class="form-label">Term</label>
                    <select class="form-select" name="term" required>
                        <option value="">Select Term</option>
                        <option value="First">First Term</option>
                        <option value="Second">Second Term</option>
                        <option value="Third">Third Term</option>
                        <option value="Fourth">Fourth Term</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <input type="text" class="form-control" name="academic_year" 
                           pattern="\d{4}-\d{4}" placeholder="YYYY-YYYY" required>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="grades.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Grade</button>
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