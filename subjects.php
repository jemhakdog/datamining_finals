<?php
    session_start();
    require 'dbcon.php';
    if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
        $page_title = "Subject Management";
        include('header.php');
?>
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
                                <i class="bi bi-book-fill text-primary me-2"></i>
                                Subject Management
                            </h4>
                            <p class="text-muted mb-0 mt-1">Manage and track subject information</p>
                        </div>
                        <a href="subject-create.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add Subject
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">#ID</th>
                                        <th width="25%">Subject Name</th>
                                        <th width="20%">Units</th>
                                        <th width="20%">Course</th>
                                        <th width="25%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $query = "SELECT * FROM subjects";
                                        $query_run = mysqli_query($con, $query);

                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            foreach($query_run as $subject)
                                            {
                                                ?>
                                                <tr>
                                                    <td class="align-middle"><?= $subject['subject_id']; ?></td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-journal-text text-secondary me-2"></i>
                                                            <?= $subject['subject_name']; ?>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-clock me-1"></i>
                                                            <?= $subject['units']; ?> Units
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-mortarboard me-1"></i>
                                                            <?= $subject['course']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="btn-group" role="group">
                                                            <a href="subject-view.php?id=<?= $subject['subject_id']; ?>" 
                                                               class="btn btn-sm btn-outline-info" 
                                                               title="View Details">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="class-view.php?id=<?= $subject['subject_id']; ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="Enroll Students">
                                                                <i class="bi bi-person-plus"></i>
                                                            </a>
                                                            <a href="subject-edit.php?id=<?= $subject['subject_id']; ?>" 
                                                               class="btn btn-sm btn-outline-success" 
                                                               title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="code.php" method="POST" class="d-inline" 
                                                                  id="delete-form-subject-<?= $subject['subject_id']; ?>">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        onclick="confirmDelete('delete-form-subject-<?= $subject['subject_id']; ?>')"
                                                                        title="Delete">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                                <input type="hidden" name="delete_subject" value="<?=$subject['subject_id'];?>">
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
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="bi bi-inbox text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                                    <p class="text-muted mb-0">No subjects found</p>
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
