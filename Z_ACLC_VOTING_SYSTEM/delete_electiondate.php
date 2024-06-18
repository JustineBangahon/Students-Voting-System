<?php
include 'config.php';

if (isset($_GET['deleteid'])) {
    $electionDateID = $_GET['deleteid'];

    $conn->query("SET foreign_key_checks = 0");

    // Retrieve the election date details from the database
    $selectQuery = "SELECT * FROM tbl_election_date WHERE election_date_id = '$electionDateID'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);

    $electionDateID = $row['election_date_id'];
    $electionDate = $row['election_date'];

    if (isset($_GET['confirm'])) {
        // Delete the election date, associated parties, and candidates from the database
        $deleteQueryParties = "DELETE FROM tblparty WHERE electiondate_id = '$electionDateID'";
        $deleteQueryCandidates = "DELETE FROM tblcandidate WHERE candidate_id IN (SELECT candidate_id FROM tbl_election_date WHERE election_date_id = '$electionDateID')";
        $deleteQueryElectionDate = "DELETE FROM tbl_election_date WHERE election_date_id = '$electionDateID'";

        mysqli_query($conn, $deleteQueryParties);
        mysqli_query($conn, $deleteQueryCandidates);
        mysqli_query($conn, $deleteQueryElectionDate);

        // Check if the delete was successful
        if (mysqli_affected_rows($conn) > 0) {
            // Display a success message and redirect back to electiondate.php
            header('Location: electiondate.php');
            $conn->query("SET foreign_key_checks = 1");

            exit();
        } else {
            // Display an error message if the delete failed
            echo '<script>alert("Failed to delete the election date. Please try again."); window.location.href = "electiondate.php";</script>';
            exit();
        }
    } else {
        // Display the confirmation message with the election date details
        echo '<script>
            if (confirm("Are you sure you want to delete the election date: ' . $electionDateID . ' - ' . $electionDate . '? This will also delete all associated parties and candidates.")) {
                window.location.href = "delete_electiondate.php?deleteid=' . $electionDateID . '&confirm=true";
            } else {
                window.location.href = "electiondate.php";
            }
        </script>';
        exit();
    }
} else {
    // If the election date ID is not provided, redirect back to electiondate.php
    header("Location: electiondate.php");
    exit();
}
?>
