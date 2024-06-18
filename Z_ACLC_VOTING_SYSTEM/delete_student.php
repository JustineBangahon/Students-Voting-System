<?php
include 'config.php';

// Check if the student ID is provided
if (isset($_GET['deleteid'])) {
    $delete_id = $_GET['deleteid'];

    // Delete the student record from the tblstudent table
    $sql = "DELETE FROM tblstudent WHERE student_id = $delete_id";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Student record deleted successfully."); window.location.href = "student.php";</script>';
    } else {
        echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // No student ID provided, display an error message
    echo '<script>alert("Invalid student ID."); window.location.href = "student.php";</script>';
    exit;
}
?>
