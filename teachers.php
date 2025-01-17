<?php
    session_start();
    require 'dbcon.php';
    if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
        $page_title = "Teacher Management";
        include('header.php');
?>
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweet-alert.js"></script>
    <script>
    function confirmDelete(formId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
    </script>
    <div class="container py-4">
        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-person-workspace text-primary me-2"></i>
                                Teacher Management
                            </h4>
                            <p class="text-muted mb-0 mt-1">Manage and track teacher information</p>
                        </div>
                        <a href="teacher-create.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add Teacher
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
                                        <th width="15%">Department</th>
                                        <th width="25%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $query = "SELECT * FROM teachers";
                                        $query_run = mysqli_query($con, $query);

                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            foreach($query_run as $teacher)
                                            {
                                                ?>
                                                <tr>
                                                    <td class="align-middle"><?= $teacher['id']; ?></td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-person-badge text-secondary me-2"></i>
                                                            <?= $teacher['name']; ?>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="mailto:<?= $teacher['email']; ?>" class="text-decoration-none">
                                                            <i class="bi bi-envelope text-muted me-1"></i>
                                                            <?= $teacher['email']; ?>
                                                        </a>
                                                    </td>
                                                    <td class="align-middle">
                                                        <i class="bi bi-telephone text-muted me-1"></i>
                                                        <?= $teacher['phone']; ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-building me-1"></i>
                                                            <?= $teacher['department']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="btn-group" role="group">
                                                            <a href="teacher-view.php?id=<?= $teacher['id']; ?>" 
                                                               class="btn btn-sm btn-outline-info" 
                                                               title="View Details">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="teacher-edit.php?id=<?= $teacher['id']; ?>" 
                                                               class="btn btn-sm btn-outline-success" 
                                                               title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="code.php" method="POST" class="d-inline" 
                                                                  id="delete-form-teacher-<?= $teacher['id']; ?>">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        onclick="confirmDelete('delete-form-teacher-<?= $teacher['id']; ?>')"
                                                                        title="Delete">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                                <input type="hidden" name="delete_teacher" value="<?=$teacher['id'];?>">
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
                                                    <p class="text-muted mb-0">No teachers found</p>
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
