<?php
// viewcandidates.php

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
// Replace 'your_host', 'your_username', 'your_password', and 'your_database' with the actual values
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

// Retrieve the candidates of the selected party
$query = "SELECT tblcandidate.*, tbl_candidate_position.position_name, tblparty.party_name, CONCAT(tblstudent.last_name, ' ', tblstudent.first_name, ' ', tblstudent.middle_name) AS full_name
          FROM tblcandidate
          INNER JOIN tbl_candidate_position ON tblcandidate.candidate_position_id = tbl_candidate_position.candidate_position_id
          INNER JOIN tblparty ON tblcandidate.party_id = tblparty.party_id
          INNER JOIN tblstudent ON tblcandidate.student_id = tblstudent.student_id
          WHERE tblcandidate.party_id = '$selectedPartyId'";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Check if any candidates were found
    if (mysqli_num_rows($result) > 0) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>View Candidates</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
        
                h2 {
                    text-align: center;
                }
        
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
        
                th, td {
                    padding: 8px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
        
                th {
                    background-color: #f2f2f2;
                }
        
                label {
                    display: block;
                }
        
                button[type='submit'] {
                    display: block;
                    width: 100%;
                    padding: 10px;
                    margin-top: 10px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    cursor: pointer;
                }
            </style>
        </head>
        <body>
            <h2>View Candidates</h2>
            <form method='POST' action='submitvote.php'>
                <table>
                    <tr>
                        <th>Full Name</th>
                        <th>Position</th>
                        <th>Party</th>
                        <th>Vote</th>
                    </tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            $candidateId = $row['student_id'];
            $fullName = $row['full_name'];
            $position = $row['position_name'];
            $party = $row['party_name'];

            echo "<tr>
                    <td>$fullName</td>
                    <td>$position</td>
                    <td>$party</td>
                    <td><label><input type='checkbox' name='candidates[]' value='$candidateId'";

            // Check if the candidate ID is already selected (from previous submission)
            if (isset($_POST['candidates']) && in_array($candidateId, $_POST['candidates'])) {
                echo " checked";
            }

            echo "></label></td>
                  </tr>";
        }

        echo "</table>
              <button type='submit'>Submit Vote</button>
              </form>
              </body>
              </html>";
    } else {
        echo "No candidates found for the selected party.";
    }
} else {
    echo "Error executing query: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
