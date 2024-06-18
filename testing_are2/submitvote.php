<?php
// submitvote.php

// Start session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to studentlogin.php if not logged in
    header("Location: studentlogin.php");
    exit();
}

// Check if the selected party ID is stored in the session
if (!isset($_SESSION['selected_party_id'])) {
    // Redirect to selectparty.php if the party ID is not set
    header("Location: selectparty.php");
    exit();
}

// Include the database connection code here
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'a_voting_system';

$conn = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the selected party ID from the session
$selectedPartyId = $_SESSION['selected_party_id'];

// Check if any candidates were selected
if (isset($_POST['candidates'])) {
    // Get the selected candidate IDs
    $selectedCandidates = $_POST['candidates'];

    // Get the current date and time
    $currentDate = date("Y-m-d H:i:s");

    // Prepare the insert statement
    $insertQuery = "INSERT INTO tblvote (candidate_id, date_record) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "is", $candidateId, $currentDate);

        // Begin a transaction
        mysqli_begin_transaction($conn);

        $success = true;

        // Insert the votes into the tblvote table
        foreach ($selectedCandidates as $candidateId) {
            // Check if the candidate ID exists in the tblcandidate table
            $checkQuery = "SELECT candidate_id FROM tblcandidate WHERE candidate_id = ?";
            $checkStmt = mysqli_prepare($conn, $checkQuery);
            mysqli_stmt_bind_param($checkStmt, "i", $candidateId);
            mysqli_stmt_execute($checkStmt);

            // Fetch the result
            mysqli_stmt_store_result($checkStmt);
            $rowCount = mysqli_stmt_num_rows($checkStmt);

            // If the candidate ID exists, proceed with inserting the vote
            if ($rowCount === 1) {
                // Execute the prepared statement
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) === 0) {
                    $success = false;
                    break;
                }
            } else {
                $success = false;
                break;
            }
            

            // Close the check statement
            mysqli_stmt_close($checkStmt);
        }

        if ($success) {
            // Commit the transaction
            mysqli_commit($conn);

            // Clear the selected party ID from the session
            unset($_SESSION['selected_party_id']);

            echo "Vote recorded successfully.";
        } else {
            // Rollback the transaction
            mysqli_rollback($conn);

            echo "Error recording vote.";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "No candidates selected.";
}

// Close the database connection
mysqli_close($conn);
?>
