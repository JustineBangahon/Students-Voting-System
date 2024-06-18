<?php

include 'config.php';
// Check if the deleteid parameter is set
if (isset($_GET['deleteid'])) {
    $deleteId = $_GET['deleteid'];

    // Include your database connection code here

    // Fetch candidate position details for confirmation message
    $sql = "SELECT position_name FROM tbl_candidate_position WHERE candidate_position_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->bind_result($positionName);
    $stmt->fetch();
    $stmt->close();

    if (!$positionName) {
        // Candidate position not found
        echo "<script>alert('Candidate position not found.');</script>";
        echo "<script>window.location.href = 'candidateposition.php';</script>";
        exit;
    }

    // Generate the confirmation message
    $confirmationMessage = "Are you sure you want to delete the candidate position: " . $positionName . "?";

    echo '<script>';
    echo 'if (confirm("' . $confirmationMessage . '")) {';
    echo '    window.location.href = "delete_candidateposition.php?confirmed=' . $deleteId . '";';
    echo '} else {';
    echo '    window.location.href = "candidateposition.php";';
    echo '}';
    echo '</script>';
} else {
    // deleteid parameter is not set
    echo "<script>window.location.href = 'candidateposition.php';</script>";
    exit;
}
?>
