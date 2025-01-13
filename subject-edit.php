<?php
session_start();
require 'dbcon.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Subject Edit</title>
</head>
<body>
  
    <div class="container mt-5">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Subject Edit 
                            <a href="subjects.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $subject_id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM subjects WHERE subject_id='$subject_id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $subject = mysqli_fetch_array($query_run);
                                ?>
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="subject_id" value="<?= $subject['subject_id']; ?>">

                                    <div class="mb-3">
                                        <label>Subject Name</label>
                                        <input type="text" name="subject_name" value="<?=$subject['subject_name'];?>" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Subject Units</label>
                                        <input type="text" name="units" value="<?=$subject['units'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Subject Course</label>
                                        <input type="text" name="course" value="<?=$subject['course'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_subject" class="btn btn-primary">
                                            Update subject
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>