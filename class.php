<?php
    session_start();
    require 'dbcon.php';
    if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
        $page_title = "Class Management";
        include('header.php');
?>
    <div class="container py-4">
        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
                                Class Management
                            </h4>
                            <p class="text-muted mb-0 mt-1">Manage student enrollments in subjects</p>
                        </div>
                        <div>
                            <a href="enroll_all_students.php" class="btn btn-success me-2">
                                <i class="bi bi-people-fill"></i> Enroll All Students
                            </a>
                            <a href="subjects.php" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill"></i> Enroll Student in Subject
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">Class ID</th>
                                        <th width="15%">Subject</th>
                                        <th width="15%">Student ID</th>
                                        <th width="25%">Student Name</th>
                                        <th width="20%">Course</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $query = "SELECT c.*, s.subject_name 
                                                 FROM class c 
                                                 LEFT JOIN subjects s ON c.subject_id = s.subject_id";
                                        $query_run = mysqli_query($con, $query);

                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            foreach($query_run as $class)
                                            {
                                                ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <span class="badge bg-light text-dark">
                                                            #<?= $class['class_id']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-journal-text text-secondary me-2"></i>
                                                            <?= $class['subject_name'] ?? $class['subject_id']; ?>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-light text-dark">
                                                            #<?= $class['student_id']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-person-circle text-secondary me-2"></i>
                                                            <?= $class['name']; ?>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-mortarboard me-1"></i>
                                                            <?= $class['course']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <form action="code.php" method="POST" class="d-inline" 
                                                              id="delete-form-class-<?= $class['class_id']; ?>">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-danger" 
                                                                    onclick="confirmDelete('delete-form-class-<?= $class['class_id']; ?>')"
                                                                    title="Remove from Class">
                                                                <i class="bi bi-person-dash"></i> Remove
                                                            </button>
                                                            <input type="hidden" name="delete_enrolledstudent" value="<?=$class['class_id'];?>">
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                    <p class="text-muted mb-0">No enrollments found</p>
                                                    <a href="subjects.php" class="btn btn-sm btn-outline-primary mt-2">
                                                        <i class="bi bi-person-plus"></i> Enroll Students in Subject
                                                    </a>
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
}else{
     header("Location: index.php");
     exit();
}
?>
