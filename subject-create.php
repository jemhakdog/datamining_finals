<?php
session_start();
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

    <title>Subject Create</title>
</head>
<body>
  
    <div class="container mt-5">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Subject Add 
                            <a href="subjects.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">

                            <div class="mb-3">
                                <label>Subject Name</label>
                                <input type="text" name="subject_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Subject Units</label>
                                <input type="text" name="units" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Subject Course</label>
                                <input type="text" name="course" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="save_subject" class="btn btn-primary">Save Subject</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sweet-alert.js"></script>
</body>
</html>
<?php 
}else{
     header("Location: index.php");
     exit();
}
 ?>
