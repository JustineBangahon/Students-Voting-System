<?php
// Step 1: Retrieve student information
$studentId = $_POST['student_id']; // Assuming the student ID is submitted via a form
$votingCode = $_POST['voting_code']; // Assuming the voting code is submitted via a form

include 'config.php';
// Check if the student exists and the voting code is correct
$query = "SELECT * FROM tblstudent WHERE student_id = $studentId AND voting_code = '$votingCode'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $student = mysqli_fetch_assoc($result);

    // Check if the student has already voted
    if ($student['vote_status'] == '0') {
        die("You have already cast your vote."); // You can display an error message or redirect the user if they have already voted
    }

    // Step 2: Identify the candidate or party
    $candidateId = $_POST['candidate_id']; // Assuming the candidate ID is submitted via a form

    // Step 3: Insert a new row into the tblvote table
    $currentDate = date('Y-m-d');
    $insertQuery = "INSERT INTO tblvote (candidate_id, date_record) VALUES ($candidateId, '$currentDate')";
    mysqli_query($conn, $insertQuery);

    // Step 4: Update the vote status of the student
    $updateQuery = "UPDATE tblstudent SET vote_status = '0' WHERE student_id = $studentId";
    mysqli_query($conn, $updateQuery);

    echo "Your vote has been successfully cast. Thank you for voting!";
} else {
    die("Invalid student ID or voting code."); // You can display an error message or redirect the user if the student ID or voting code is incorrect
}

// Close the database connection
mysqli_close($conn);
?>


