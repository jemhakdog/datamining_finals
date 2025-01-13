<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$page_title = "View Grade";
include('dbcon.php');
include('header.php');

if (!isset($_GET['id'])) {
    header('Location: grades.php');
    exit;
}

$grade_id = mysqli_real_escape_string($con, $_GET['id']);

// Get grade data with related information
$query = "SELECT g.*, s.name as student_name, s.email as student_email,
          sub.subject_name, sub.units, c.name as class_name
          FROM grades g
          JOIN students s ON g.student_id = s.id
          JOIN subjects sub ON g.subject_id = sub.subject_id
          JOIN class c ON g.class_id = c.class_id
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-eye"></i> Grade Details
            </h4>
            <div>
                <a href="grade-edit.php?id=<?= $grade_id ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="grades.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Student Information</h5>
                    <table class="table">
                        <tr>
                            <th>Name:</th>
                            <td><?= htmlspecialchars($grade['student_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= htmlspecialchars($grade['student_email']) ?></td>
                        </tr>
                        <tr>
                            <th>Class:</th>
                            <td><?= htmlspecialchars($grade['class_name']) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Subject Information</h5>
                    <table class="table">
                        <tr>
                            <th>Subject:</th>
                            <td><?= htmlspecialchars($grade['subject_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Units:</th>
                            <td><?= htmlspecialchars($grade['units']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="card-title">Grade Information</h5>
                    <table class="table">
                        <tr>
                            <th>Grade Value:</th>
                            <td><?= htmlspecialchars($grade['grade_value']) ?></td>
                        </tr>
                        <tr>
                            <th>Term:</th>
                            <td><?= htmlspecialchars($grade['term']) ?> Term</td>
                        </tr>
                        <tr>
                            <th>Academic Year:</th>
                            <td><?= htmlspecialchars($grade['academic_year']) ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td><?= date('F j, Y g:i A', strtotime($grade['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>