<?php
include 'config.php';

// Check if the form is submitted
if(isset($_POST['submit'])) {
    $votingCode = $_POST['votingCode'];

    // Fetch the student details from the database
    $query = "SELECT * FROM tblstudent WHERE voting_code = '$votingCode'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Check the vote status
        if($row['vote_status'] == '0') {
            // Vote status is 0 (voted), student cannot login
            echo "You have already voted and cannot login.";
        } else {
            // Vote status is not 0 (not voted), student can login
            echo "Login successful!";
            header('Location: student_dashboard.php');
            // Perform necessary actions after successful login
        }
    } else {
        // No student found with the given voting code
        echo "Invalid voting code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
</head>
<body>
    <h2>Student Login</h2>
    <form method="POST" action="student_login.php">
        <label for="votingCode">Voting Code:</label>
        <input type="text" name="votingCode" required><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
