<?php
session_start();

// Check if the student is not logged in
if (!isset($_SESSION['votingCode'])) {
    header("Location: studentlogin.php");
    exit();
}

// Include database connection
include_once 'include/config.php';

// Get the candidate ID from the request
$candidateId = $_GET['candidateId'];

// Check if the candidate ID is valid
$query = "SELECT * FROM tblcandidate WHERE candidate_id = $candidateId";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Update the voting status for the student
    $votingCode = $_SESSION['votingCode'];
    $updateQuery = "UPDATE tblstudent SET vote_status = 0 WHERE voting_code = '$votingCode'";
    mysqli_query($conn, $updateQuery);
} else {
    echo "Invalid candidate ID.";
}
?>
