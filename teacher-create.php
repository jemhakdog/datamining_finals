<?php
session_start();
require 'dbcon.php';
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Teacher Create</title>
</head>
<body>
  
    <div class="container mt-5">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Teacher Add 
                            <a href="teachers.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">

                            <div class="mb-3">
                                <label>Teacher Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Teacher Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Teacher Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Teacher Department</label>
                                <input type="text" name="department" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="save_teacher" class="btn btn-primary">Save Teacher</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sweet-alert.js"></script>
    <?php if(isset($_SESSION['message']) && isset($_SESSION['show_swal'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: '<?= $_SESSION['message'] ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'teachers.php';
                }
            });
        });
    </script>
    <?php
    unset($_SESSION['message']);
    unset($_SESSION['show_swal']);
    endif;
    ?>
</body>
</html>
<?php 
}else{
     header("Location: index.php");
     exit();
}
 ?>