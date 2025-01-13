<?php
    session_start();
    require 'dbcon.php';
    if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
        $page_title = "Student Management";
        include('header.php');
?>
    <?php include('message.php'); ?>

    <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                                Student Management
                            </h4>
                            <p class="text-muted mb-0 mt-1">Manage and track student information</p>
                        </div>
                        <a href="student-create.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add Student
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#ID</th>
                                        <th width="20%">Name</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Phone</th>
                                        <th width="15%">Course</th>
                                        <th width="25%">Actions</th>
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
                                                    <td class="align-middle"><?= $student['id']; ?></td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-person-circle text-secondary me-2"></i>
                                                            <?= $student['name']; ?>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="mailto:<?= $student['email']; ?>" class="text-decoration-none">
                                                            <i class="bi bi-envelope text-muted me-1"></i>
                                                            <?= $student['email']; ?>
                                                        </a>
                                                    </td>
                                                    <td class="align-middle">
                                                        <i class="bi bi-telephone text-muted me-1"></i>
                                                        <?= $student['phone']; ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-book me-1"></i>
                                                            <?= $student['course']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="btn-group" role="group">
                                                            <a href="student-view.php?id=<?= $student['id']; ?>" 
                                                               class="btn btn-sm btn-outline-info" 
                                                               title="View Details">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="student-edit.php?id=<?= $student['id']; ?>" 
                                                               class="btn btn-sm btn-outline-success" 
                                                               title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="code.php" method="POST" class="d-inline" 
                                                                  id="delete-form-<?= $student['id']; ?>">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        onclick="confirmDelete('delete-form-<?= $student['id']; ?>')"
                                                                        title="Delete">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                                <input type="hidden" name="delete_student" value="<?=$student['id'];?>">
                                                            </form>
                                                        </div>
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
                                                    <p class="text-muted mb-0">No students found</p>
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

    <?php
        include('footer.php');
    } else {
        header("Location: index.php");
        exit();
    }
    ?>