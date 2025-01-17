<?php
session_start();
require 'dbcon.php';

// Handle student enrollment
if(isset($_POST['enroll_student'])) {
    $subject_id = mysqli_real_escape_string($con, $_POST['enroll_student']);
    $student_id = mysqli_real_escape_string($con, $_POST['enroll_student_id']);
    $student_name = mysqli_real_escape_string($con, $_POST['enroll_student_name']);
    $student_course = mysqli_real_escape_string($con, $_POST['enroll_student_course']);

    // Check if student is already enrolled
    $check_query = "SELECT * FROM class WHERE student_id='$student_id' AND subject_id='$subject_id'";
    $check_result = mysqli_query($con, $check_query);

    if(mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "Student is already enrolled in this subject";
    } else {
        // Insert into class table
        $query = "INSERT INTO class (student_id, subject_id, name, course)
                  VALUES ('$student_id', '$subject_id', '$student_name', '$student_course')";
        $query_run = mysqli_query($con, $query);

        if($query_run) {
            $_SESSION['message'] = "Student enrolled successfully";
        } else {
            $_SESSION['message'] = "Error enrolling student: " . mysqli_error($con);
        }
    }

    header("Location: class-view.php?id=" . $subject_id);
    exit(0);
}

// Handle removing enrolled student
if(isset($_POST['delete_enrolledstudent'])) {
    $class_id = mysqli_real_escape_string($con, $_POST['delete_enrolledstudent']);
    
    // Get subject_id before deleting for redirect
    $query = "SELECT subject_id FROM class WHERE class_id='$class_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $subject_id = $row['subject_id'];

    // Delete enrollment
    $query = "DELETE FROM class WHERE class_id='$class_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Student removed from class successfully";
    } else {
        $_SESSION['message'] = "Error removing student: " . mysqli_error($con);
    }

    header("Location: class-view.php?id=" . $subject_id);
    exit(0);
}

// Save individual grade
if(isset($_POST['save_grade'])) {
    $grade_id = isset($_POST['grade_id']) ? mysqli_real_escape_string($con, $_POST['grade_id']) : null;
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
    $grade_value = mysqli_real_escape_string($con, $_POST['grade_value']);
    $term = mysqli_real_escape_string($con, $_POST['term']);
    $academic_year = mysqli_real_escape_string($con, $_POST['academic_year']);

    // Get class_id from student's current class
    $class_query = "SELECT class_id FROM class WHERE student_id = '$student_id' AND subject_id = '$subject_id' LIMIT 1";
    $class_result = mysqli_query($con, $class_query);
    
    if ($class_row = mysqli_fetch_assoc($class_result)) {
        $class_id = $class_row['class_id'];
        
        if($grade_id) {
            // Update existing grade
            $query = "UPDATE grades SET
                      grade_value = '$grade_value',
                      term = '$term',
                      academic_year = '$academic_year',
                      updated_at = NOW()
                      WHERE grade_id = '$grade_id'";
        } else {
            // Insert new grade with class_id
            $query = "INSERT INTO grades (student_id, subject_id, class_id, grade_value, term, academic_year, created_at, updated_at)
                      VALUES ('$student_id', '$subject_id', '$class_id', '$grade_value', '$term', '$academic_year', NOW(), NOW())";
        }
    } else {
        $_SESSION['message'] = "Error: Student is not enrolled in this subject";
        header("Location: view_subject_students.php?id=" . $subject_id);
        exit(0);
    }

    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Grade saved successfully";
    } else {
        $_SESSION['message'] = "Error saving grade: " . mysqli_error($con);
    }

    header("Location: view_subject_students.php?id=" . $subject_id);
    exit(0);
}

// Save bulk grades
if(isset($_POST['save_bulk_grades'])) {
    $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
    $term = mysqli_real_escape_string($con, $_POST['bulk_term']);
    $academic_year = mysqli_real_escape_string($con, $_POST['bulk_academic_year']);
    $grades = $_POST['grades'];

    $success = true;
    $error_message = "";

    foreach($grades as $student_id => $grade_value) {
        if($grade_value === '') continue; // Skip empty grades

        $student_id = mysqli_real_escape_string($con, $student_id);
        $grade_value = mysqli_real_escape_string($con, $grade_value);

        // Check if grade exists
        $check_query = "SELECT grade_id FROM grades
                       WHERE student_id = '$student_id'
                       AND subject_id = '$subject_id'";
        $check_result = mysqli_query($con, $check_query);

        if(mysqli_num_rows($check_result) > 0) {
            // Update existing grade
            $grade = mysqli_fetch_assoc($check_result);
            $query = "UPDATE grades SET
                      grade_value = '$grade_value',
                      term = '$term',
                      academic_year = '$academic_year',
                      updated_at = NOW()
                      WHERE grade_id = '{$grade['grade_id']}'";
        } else {
            // Get class_id from student's current class
            $class_query = "SELECT class_id FROM class WHERE student_id = '$student_id' AND subject_id = '$subject_id' LIMIT 1";
            $class_result = mysqli_query($con, $class_query);
            
            if ($class_row = mysqli_fetch_assoc($class_result)) {
                $class_id = $class_row['class_id'];
                // Insert new grade with class_id
                $query = "INSERT INTO grades (student_id, subject_id, class_id, grade_value, term, academic_year, created_at, updated_at)
                          VALUES ('$student_id', '$subject_id', '$class_id', '$grade_value', '$term', '$academic_year', NOW(), NOW())";
            } else {
                $success = false;
                $error_message = "Error: Some students are not enrolled in this subject";
                break;
            }
        }

        $query_run = mysqli_query($con, $query);
        if(!$query_run) {
            $success = false;
            $error_message = mysqli_error($con);
            break;
        }
    }

    if($success) {
        $_SESSION['message'] = "Grades saved successfully";
    } else {
        $_SESSION['message'] = "Error saving grades: " . $error_message;
    }

    header("Location: view_subject_students.php?id=" . $subject_id);
    exit(0);
}

if(isset($_POST['delete_student']))
{
    $student_id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM students WHERE id='$student_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Student Deleted Successfully";
        header("Location: home.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Student Not Deleted";
        header("Location: home.php");
        exit(0);
    }
}

if(isset($_POST['update_student']))
{
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $course = mysqli_real_escape_string($con, $_POST['course']);

    $query = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course' WHERE id='$student_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Student Updated Successfully";
        header("Location: home.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Student Not Updated";
        header("Location: home.php");
        exit(0);
    }

}


if(isset($_POST['save_student']))
{
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $course = mysqli_real_escape_string($con, $_POST['course']);

    $query = "INSERT INTO students (name,email,phone,course) VALUES ('$name','$email','$phone','$course')";

    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Student Created Successfully";
        header("Location: student-create.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Student Not Created";
        header("Location: student-create.php");
        exit(0);
    }
}

//Part where the teacher will be deleted, updated, saved.


if(isset($_POST['delete_teacher']))
{
    $teacher_id = mysqli_real_escape_string($con, $_POST['delete_teacher']);

    $query = "DELETE FROM teachers WHERE id='$teacher_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Teacher Deleted Successfully";
        header("Location: teachers.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Teacher Not Deleted";
        header("Location: teachers.php");
        exit(0);
    }
}

if(isset($_POST['update_teacher']))
{
    $teacher_id = mysqli_real_escape_string($con, $_POST['teacher_id']);

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $department = mysqli_real_escape_string($con, $_POST['department']);

    $query = "UPDATE teachers SET name='$name', email='$email', phone='$phone', department='$department' WHERE id='$teacher_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Teacher Updated Successfully";
        $_SESSION['show_swal'] = true;
        header("Location: teachers.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Teacher Not Updated";
        $_SESSION['show_swal'] = true;
        header("Location: teachers.php");
        exit(0);
    }

}


if(isset($_POST['save_teacher']))
{
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $department = mysqli_real_escape_string($con, $_POST['department']);

    $query = "INSERT INTO teachers (name,email,phone,department) VALUES ('$name','$email','$phone','$department')";

    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Teacher Created Successfully";
        $_SESSION['show_swal'] = true;
        header("Location: teacher-create.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Teacher Not Created";
        $_SESSION['show_swal'] = true;
        header("Location: teacher-create.php?error=true");
        exit(0);
    }
}

//Part where the subject will be deleted, updated, saved.


if(isset($_POST['delete_subject']))
{
    $subject_id = mysqli_real_escape_string($con, $_POST['delete_subject']);

    $query = "DELETE FROM subjects WHERE subject_id='$subject_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Subject Deleted Successfully";
        $_SESSION['show_swal'] = true;
        header("Location: subjects.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Subject Not Deleted";
        $_SESSION['show_swal'] = true;
        header("Location: subjects.php");
        exit(0);
    }
}

if(isset($_POST['update_subject']))
{
    $subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);

    $subject_name = mysqli_real_escape_string($con, $_POST['subject_name']);
    $units = mysqli_real_escape_string($con, $_POST['units']);
    $course = mysqli_real_escape_string($con, $_POST['course']);
    

    $query = "UPDATE subjects SET subject_name='$subject_name', units='$units', course='$course' WHERE subject_id='$subject_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Subject Updated Successfully";
        $_SESSION['show_swal'] = true;
        header("Location: subjects.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Subject Not Updated";
        $_SESSION['show_swal'] = true;
        header("Location: subjects.php");
        exit(0);
    }

}


if(isset($_POST['save_subject']))
{
    $subject_name = mysqli_real_escape_string($con, $_POST['subject_name']);
    $units = mysqli_real_escape_string($con, $_POST['units']);
    $course = mysqli_real_escape_string($con, $_POST['course']);

    $query = "INSERT INTO subjects (subject_name,units,course) VALUES ('$subject_name','$units','$course')";

    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Subject Created Successfully";
        $_SESSION['show_swal'] = true;
        header("Location: subjects.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Subject Not Created";
        $_SESSION['show_swal'] = true;
        header("Location: subjects.php");
        exit(0);
    }
}

//Part where a student is enrolled on the subject.
if(isset($_POST['enroll_student']))
{
	$subject_id = mysqli_real_escape_string($con, $_POST['enroll_student']);
	$subject_name = mysqli_real_escape_string($con, $_POST['enroll_subject_name']);
	$id = mysqli_real_escape_string($con, $_POST['enroll_student_id']);
    $name = mysqli_real_escape_string($con, $_POST['enroll_student_name']);
    $course = mysqli_real_escape_string($con, $_POST['enroll_student_course']);

   
    $query = "INSERT INTO class (subject_id,student_id,name,course) VALUES ('$subject_id','$id','$name','$course')";
    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Student Enrolled to $subject_name Successfully";
        header("Location: class.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Student Not Enrolled";
        header("Location: class.php");
        exit(0);
    }
}

if(isset($_POST['delete_enrolledstudent']))
{
    $class_id = mysqli_real_escape_string($con, $_POST['delete_enrolledstudent']);

    $query = "DELETE FROM class WHERE class_id='$class_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Student Removed Successfully";
        header("Location: class.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Student Not Removed";
        header("Location: class.php");
        exit(0);
    }
}

?>
