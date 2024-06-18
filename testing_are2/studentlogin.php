<?php
session_start();

// Check if the student is already logged in
if (isset($_SESSION['student_id'])) {
    header("Location: student_voting_dashboard.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the entered voting code
    $votingCode = $_POST['voting_code'];

    // Query the database to check if the voting code exists
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "a_voting_system";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM tblstudent WHERE voting_code = ?");
    $stmt->bind_param("s", $votingCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Student exists, check the vote status and election date
        $row = $result->fetch_assoc();
        $voteStatus = $row['vote_status'];

        // Query the election date from tbl_election_date table
        $electionDateQuery = $conn->query("SELECT election_date FROM tbl_election_date");
        $electionDateRow = $electionDateQuery->fetch_assoc();
        $electionDate = $electionDateRow['election_date'];
        $currentDate = date("Y-m-d");

        if ($voteStatus == '0') {
            // Student has already voted
            echo '<script>alert("You have already voted.");</script>';
        } elseif ($electionDate < $currentDate) {
            // Election date has elapsed
            echo '<script>alert("The election has ended.");</script>';
        } elseif ($electionDate > $currentDate) {
            // Election date is in the future
            echo '<script>alert("The election has not yet started. The election date is: ' . $electionDate . '");</script>';
        } else {
            // Student successfully logged in
            $_SESSION['student_id'] = $row['student_id'];
            header("Location: student_voting_dashboard.php");
            exit();
        }
    } else {
        // Voting code is invalid
        echo '<script>alert("Invalid voting code.");</script>';
    }
    

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Student Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="voting_code">Voting Code:</label>
                <input type="text" class="form-control" name="voting_code" id="voting_code" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</body>
</html>
