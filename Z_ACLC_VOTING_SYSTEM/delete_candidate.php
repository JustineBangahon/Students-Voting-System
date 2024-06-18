<?php
include 'config.php';

// Check if the candidate ID is provided
if (isset($_GET['deleteid'])) {
    $candidateID = $_GET['deleteid'];

    // Disable foreign key checks
    $conn->query("SET foreign_key_checks = 0");

    // Check if the candidate exists
    $checkQuery = "SELECT * FROM tblcandidate WHERE candidate_id = $candidateID";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Candidate exists, show confirmation dialog
        echo '<script>';
        echo 'if(confirm("Are you sure you want to delete this candidate?")){';
        echo 'window.location.href = "delete_candidate.php?confirmed=' . $candidateID . '";';
        echo '}';
        echo 'else {';
        echo 'window.location.href = "candidate.php";';
        echo '}';
        echo '</script>';
    } else {
        // Candidate does not exist, redirect to candidate.php
        header('Location: candidate.php');
    }
}

// Check if the delete is confirmed
if (isset($_GET['confirmed'])) {
    $candidateID = $_GET['confirmed'];

    // Enable foreign key checks
    $conn->query("SET foreign_key_checks = 0");

    // Delete the candidate
    $deleteQuery = "DELETE FROM tblcandidate WHERE candidate_id = $candidateID";

    if ($conn->query($deleteQuery) === TRUE) {
        // Candidate deleted successfully
        echo '<script>';
        echo 'alert("Candidate deleted successfully.");';
        echo 'window.location.href = "candidate.php";';
        echo '</script>';
    } else {
        // Error deleting candidate
        echo '<script>';
        echo 'alert("Error deleting candidate: ' . $conn->error . '");';
        echo 'window.location.href = "candidate.php";';
        echo '</script>';
    }
}

// Enable foreign key checks if they were not re-enabled before this point
$conn->query("SET foreign_key_checks = 1");

$conn->close();
?>
