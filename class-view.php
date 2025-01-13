<?php
session_start();
require 'dbcon.php';

// Initialize variables
$subject = null;
$subject_id = null;

if(!isset($_GET['id'])) {
    $_SESSION['message'] = "No Subject ID Provided";
    header("Location: class.php");
    exit(0);
}

$subject_id = mysqli_real_escape_string($con, $_GET['id']);
$query = "SELECT * FROM subjects WHERE subject_id='$subject_id'";
$query_run = mysqli_query($con, $query);

if(mysqli_num_rows($query_run) == 0) {
    $_SESSION['message'] = "No Such Subject ID Found";
    header("Location: class.php");
    exit(0);
}

$subject = mysqli_fetch_array($query_run);
$page_title = "Class View - " . $subject['subject_name'];
include('header.php');
?>

<div class="container mt-5">

<div class="container mt-4">

        <?php include('message.php'); ?>
		      <?php include('message.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-people-fill text-primary me-2"></i>
                                Students Enrolled
                            </h4>
                            <?php if ($subject): ?>
                                <p class="text-muted mb-0 mt-1">
                                    <?= htmlspecialchars($subject['subject_name']); ?> -
                                    <?= htmlspecialchars($subject['course']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
					


                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
									<th>Phone</th>
                                    <th>Email</th>
									<th>Course</th>
									<th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "SELECT class.class_id, class.student_id, class.name AS name, students.email, students.phone, class.course FROM class INNER JOIN students ON class.student_id = students.id WHERE class.subject_id = '$subject_id'";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $class)
                                        {
                                            ?>
                                            <tr>
                                                <td><?= $class['student_id']; ?></td>
                                                <td><?= $class['name']; ?></td>
												<td><?= $class['phone']; ?></td>
												<td><?= $class['email']; ?></td>
                                                <td><?= $class['course']; ?></td>
                                                <td>
                                                    <form action="code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_enrolledstudent" value="<?=$class['class_id'];?>" class="btn btn-danger btn-sm">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
								
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
			&nbsp;
			<div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-person-plus-fill text-primary me-2"></i>
                                Available Students
                            </h4>
                            <p class="text-muted mb-0 mt-1">
                                Select students to enroll in this subject
                            </p>
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Course</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "SELECT * FROM students";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $student)
                                        {
                                            ?>
                                            <tr>
                                                <td><?= $student['id']; ?></td>
                                                <td><?= $student['name']; ?></td>
                                                <td><?= $student['email']; ?></td>
                                                <td><?= $student['phone']; ?></td>
                                                <td><?= $student['course']; ?></td>
                                               <td>
                                                   <form action="code.php" method="POST" class="d-inline">
                                                       <input type="hidden" name="enroll_subject_name" value="<?= htmlspecialchars($subject['subject_name']); ?>">
                                                       <input type="hidden" name="enroll_student_id" value="<?= htmlspecialchars($student['id']); ?>">
                                                       <input type="hidden" name="enroll_student_name" value="<?= htmlspecialchars($student['name']); ?>">
                                                       <input type="hidden" name="enroll_student_course" value="<?= htmlspecialchars($student['course']); ?>">
                                                       <button type="submit" name="enroll_student" value="<?= htmlspecialchars($subject['subject_id']); ?>"
                                                               class="btn btn-primary">
                                                           <i class="bi bi-person-plus"></i> Enroll Student
                                                       </button>
                                                   </form>
                                               </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
		          <div class="mt-3">
		              <a href="class.php" class="btn btn-secondary">
		                  <i class="bi bi-arrow-left"></i> Back to Class List
		              </a>
		          </div>
		      </div>
		  </div>

<?php include('footer.php'); ?>