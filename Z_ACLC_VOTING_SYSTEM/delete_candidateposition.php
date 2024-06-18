<?php

include 'config.php';


// Check if the deleteid parameter is set
if (isset($_GET['deleteid'])) {
    $deleteId = $_GET['deleteid'];

    $conn->query("SET foreign_key_checks = 0");
    // Include your database connection code here

    // Retrieve the candidate position details for confirmation message
    $sql = "SELECT position_name FROM tbl_candidate_position WHERE candidate_position_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->bind_result($positionName);
    $stmt->fetch();
    $stmt->close();

    // Generate the confirmation alert
    echo '<script>';
    echo 'if (confirm("Are you sure you want to delete the candidate position \'' . $positionName . '\'?")) {';
    echo '    window.location.href = "delete_candidateposition.php?confirmed=' . $deleteId . '";';
    echo '} else {';
    echo '    window.location.href = "candidateposition.php";';
    echo '}';
    echo '</script>';

} elseif (isset($_GET['confirmed'])) {
    // The deletion is confirmed

    $deleteId = $_GET['confirmed'];

    $conn->query("SET foreign_key_checks = 1");

    // Include your database connection code here

    // Perform the delete operation
    // Assuming you have a database connection object named $conn
    $sql = "DELETE FROM tbl_candidate_position WHERE candidate_position_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        // Delete successful
        echo "<script>alert('Candidate position deleted successfully.');</script>";
        echo "<script>window.location.href = 'candidateposition.php';</script>";
        exit;
    } else {
        // Delete failed
        echo "<script>alert('Failed to delete candidate position.');</script>";
        echo "<script>window.location.href = 'candidateposition.php';</script>";
        exit;
    }

} else {
    // deleteid and confirmed parameters are not set
    echo "<script>window.location.href = 'candidateposition.php';</script>";
    exit;
}
?>
