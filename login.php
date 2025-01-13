<?php 
session_start(); 
include "dbcon.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	if (empty($uname)) {
		header("Location: index.php?error=User Name is required");
	    exit();
	}else if(empty($pass)){
        header("Location: index.php?error=Password is required");
	    exit();
	}else{
		$sql = "SELECT u.*, 
                CASE 
                    WHEN u.role = 'teacher' THEN t.name 
                    WHEN u.role = 'student' THEN s.name 
                    ELSE 'Administrator'
                END as display_name
                FROM users u 
                LEFT JOIN teachers t ON u.ref_id = t.id AND u.role = 'teacher'
                LEFT JOIN students s ON u.ref_id = s.id AND u.role = 'student'
                WHERE u.user_name='$uname' AND u.password='$pass'";

		$result = mysqli_query($con, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['user_name'] === $uname && $row['password'] === $pass) {
            	$_SESSION['user_name'] = $row['user_name'];
            	$_SESSION['name'] = $row['display_name'];
            	$_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['ref_id'] = $row['ref_id'];

                // Redirect based on role
                switch($row['role']) {
                    case 'admin':
                        header("Location: home.php");
                        break;
                    case 'teacher':
                        header("Location: teacher_dashboard.php");
                        break;
                    case 'student':
                        header("Location: student_dashboard.php");
                        break;
                    default:
                        header("Location: home.php");
                }
		        exit();
            }else{
				header("Location: index.php?error=Incorrect username or password");
		        exit();
			}
		}else{
			header("Location: index.php?error=Incorrect username or password");
	        exit();
		}
	}
	
}else{
	header("Location: index.php");
	exit();
}
