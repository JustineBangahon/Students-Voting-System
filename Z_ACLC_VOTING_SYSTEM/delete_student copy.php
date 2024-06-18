<?php

include 'config.php';

if(isset($_GET['deleteid'])) {
    $delete_id = $_GET['deleteid'];
    $conn->query("SET FOREIGN_KEY_CHECKS=0;");
   
    $check_query = "SELECT * FROM tblstudent WHERE student_id = $delete_id";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0) {
        $check_fk_query = "SELECT * FROM tbl_student_description WHERE student_id = $delete_id"; 
        $check_fk_result = mysqli_query($conn, $check_fk_query);
        if(mysqli_num_rows($check_fk_result) > 0) {        
            echo "<script>alert('Cannot delete the student record as it is referenced by other tables.');</script>";
            
        } else {
            $delete_query = "DELETE FROM tblstudent WHERE student_id = $delete_id";
            $delete_result = mysqli_query($conn, $delete_query);

            if($delete_result) {
                echo "<script>alert('Student record deleted successfully.');</script>";
                $conn->query("SET FOREIGN_KEY_CHECKS=1;");
            } else {
                echo "<script>alert('Failed to delete the student record.');</script>";
            }
        }
    } else {
        echo "<script>alert('Student record not found.');</script>";
    }
    echo "<script>window.location.href = 'student.php';</script>";
}
?>

