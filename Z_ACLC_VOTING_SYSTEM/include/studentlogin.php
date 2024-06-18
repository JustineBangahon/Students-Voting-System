<?php
include 'config.php';
include 'vote.php';

if (isset($_POST['votingCode'])) {
    $votingCode = $_POST['votingCode'];

    // Check if the student exists and their vote status
    $checkStudentQuery = "SELECT * FROM tblstudent WHERE voting_code = '$votingCode'";
    $checkStudentResult = mysqli_query($conn, $checkStudentQuery);

    if (mysqli_num_rows($checkStudentResult) > 0) {
        $studentRow = mysqli_fetch_assoc($checkStudentResult);
        $voteStatus = $studentRow['vote_status'];

        if ($voteStatus == 0) {
            // Student has already voted
            echo "You have already voted.";
            exit;
        } else {
            // Update the student's vote status to indicate they have logged in
            $updateVoteStatusQuery = "UPDATE tblstudent SET vote_status = 1 WHERE voting_code = '$votingCode'";
            $updateVoteStatusResult = mysqli_query($conn, $updateVoteStatusQuery);

            if ($updateVoteStatusResult) {
                // Redirect to the student dashboard
                header("Location: studentdashboard.php");
                exit;
            } else {
                echo "Failed to update vote status. Please try again later.";
            }
        }
    } else {
        echo "Invalid voting code. Please try again.";
    }
}
?>

<!-- HTML code for the student login form -->
<h2>Student Login</h2>
<form method="POST" action="studentlogin.php">
    <label for="votingCode">Voting Code:</label>
    <input type="text" name="votingCode" id="votingCode" required>
    <button type="submit">Login</button>
</form>
