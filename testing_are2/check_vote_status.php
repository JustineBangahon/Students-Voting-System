<?php
// Assuming you have a database connection established
// Replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials
$mysqli = new mysqli("localhost", "root", "", "a_voting_system");

// Check for any connection errors
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Get the voting code sent via AJAX
$votingCode = $_POST["votingCode"];

// Prepare and execute a SQL query to fetch the vote status based on the voting code
$query = "SELECT vote_status FROM tblstudent WHERE voting_code = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $votingCode);
$stmt->execute();
$stmt->bind_result($voteStatus);
$stmt->fetch();
$stmt->close();

// Create an associative array to hold the vote status
$response = array("voteStatus" => $voteStatus);

// Send the vote status as a JSON response
header("Content-Type: application/json");
echo json_encode($response);

// Close the database connection
$mysqli->close();
?>
