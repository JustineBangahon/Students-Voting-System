<?php
include 'config.php';

if (isset($_POST['candidateId']) && isset($_POST['votingCode'])) {
    $candidateId = $_POST['candidateId'];
    $votingCode = $_POST['votingCode'];

    // Check if the student has already voted
    $checkVoteQuery = "SELECT vote_status FROM tblstudent WHERE voting_code = '$votingCode'";
    $checkVoteResult = mysqli_query($conn, $checkVoteQuery);

    if (mysqli_num_rows($checkVoteResult) > 0) {
        $voteStatusRow = mysqli_fetch_assoc($checkVoteResult);
        $voteStatus = $voteStatusRow['vote_status'];

        if ($voteStatus == 0) {
            echo "You have already voted.";
            exit;
        }
    }

    // Update the student's vote status to indicate that they have voted
    $updateVoteQuery = "UPDATE tblstudent SET vote_status = 0 WHERE voting_code = '$votingCode'";
    $updateVoteResult = mysqli_query($conn, $updateVoteQuery);

    if ($updateVoteResult) {
        // Perform any additional actions you need after successful voting

        echo "Vote casted successfully!";
    } else {
        echo "Failed to cast vote. Please try again later.";
    }
}
?>
