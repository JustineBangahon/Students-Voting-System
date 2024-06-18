<?php
// This file contains common functions used in the student voting system
include_once 'config.php';
// Function to establish database connection
function connectDB()
{
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "a_voting_system";

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to check if a student has already voted
function hasVoted($votingCode)
{
    $conn = connectDB();

    $votingCode = sanitizeInput($votingCode);

    $query = "SELECT voted FROM tblstudent WHERE voting_code = '$votingCode'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $voted = $row['voted'];
        $conn->close();

        return $voted == 0;
    }

    $conn->close();
    return false;
}

// Function to update the vote status of a student
function updateVoteStatus($votingCode)
{
    $conn = connectDB();

    $votingCode = sanitizeInput($votingCode);

    $query = "UPDATE tblstudent SET voted = 0 WHERE voting_code = '$votingCode'";
    $conn->query($query);

    $conn->close();
}

// Function to sanitize user input
function sanitizeInput($input)
{
    $conn = connectDB();
    $input = mysqli_real_escape_string($conn, $input);
    $input = htmlspecialchars($input);
    $conn->close();

    return $input;
}

// Function to redirect to a specific page
function redirect($page)
{
    header("Location: $page");
    exit();
}
?>
