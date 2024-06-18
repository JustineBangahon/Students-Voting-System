<?php
//$host = "localhost"; // Hostname
//username = "root"; // MySQL username
//$password = ""; // MySQL password
//$database = "a_voting_system"; // Database name

// Create a new MySQLi instance
$conn = new mysqli('localhost', 'root', '', 'a_voting_system');

// Check the connection
if ($conn->connect_error) {
    die($conn->connect_error);
}
?>
