<?php
// Include the database connection code
include 'config.php';


// Check if the course ID is provided in the URL
if (isset($_GET['deleteid'])) {
    $courseId = $_GET['deleteid'];
    $conn->query("SET foreign_key_checks = 0");
    // Retrieve the course details from the database
    $selectQuery = "SELECT * FROM tblcourse WHERE course_id = '$courseId'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);

    $courseInitial = $row['course_initial'];
    $courseName = $row['course_name'];

    // Check if the user confirms the deletion
    if (isset($_GET['confirm'])) {
        // Delete the course from the database
        $deleteQuery = "DELETE FROM tblcourse WHERE course_id = '$courseId'";
        mysqli_query($conn, $deleteQuery);

        $conn->query("SET FOREIGN_KEY_CHECKS=1;");

        // Check if the delete was successful
        if (mysqli_affected_rows($conn) > 0) {
            // Display a success message and redirect back to course.php
            header('Location: course.php');
            exit();
        } else {
            // Display an error message if the delete failed
            echo '<script>alert("Failed to delete the course. Please try again."); window.location.href = "course.php";</script>';
            exit();
        }
    } else {
        // Display the confirmation message with the course details
        echo '<script>
            if (confirm("Are you sure you want to delete the course: ' . $courseInitial . ' - ' . $courseName . '?")) {
                window.location.href = "delete_course.php?deleteid=' . $courseId . '&confirm=true";
            } else {
                window.location.href = "course.php";
            }
        </script>';
        exit();
    }
} else {
    // If the course ID is not provided, redirect back to course.php
    header("Location: course.php");
    exit();
}
?>
