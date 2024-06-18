<?php

include 'config.php';
// Check if the student_id parameter is passed in the URL
if (isset($_GET['student_id'])) {
    $studentId = $_GET['student_id'];

    // Retrieve student details based on the student_id
    $sql = "SELECT * FROM tblstudent WHERE student_id = $studentId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $studentName = $row['first_name'] . ' ' . $row['last_name'];
        $courseId = $row['course_id'];

        // Retrieve the course name based on the course_id
        $sql = "SELECT course_name FROM tblcourse WHERE course_id = $courseId";
        $courseResult = $conn->query($sql);
        if ($courseResult->num_rows > 0) {
            $courseRow = $courseResult->fetch_assoc();
            $courseName = $courseRow['course_name'];
        } else {
            $courseName = "Unknown Course";
        }

        // Display the student's details
        echo "Welcome, $studentName!";
        echo "<br>";
        echo "Course: $courseName";
        // Add any other information you want to display for the logged-in student
    } else {
        echo "Invalid student ID.";
    }
} else {
    echo "Student ID not provided.";
}
?>
