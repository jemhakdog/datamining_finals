<?php
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

    <title>Subject View</title>
</head>
<body>

    <div class="container mt-5">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Subject View Details 
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
                                
                                    <div class="mb-3">
                                        <label>Subject Name</label>
                                        <p class="form-control">
                                            <?=$subject['subject_name'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Units</label>
                                        <p class="form-control">
                                            <?=$subject['units'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Course</label>
                                        <p class="form-control">
                                            <?=$subject['course'];?>
                                        </p>
                                    </div>
                                  

                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Subject ID Found</h4>";
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