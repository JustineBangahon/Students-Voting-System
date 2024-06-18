<?php
include 'config.php';

if (isset($_GET['deleteid'])) {
    $delete_id = $_GET['deleteid'];

    $conn->query("SET FOREIGN_KEY_CHECKS=0;");

    $selectQuery = "SELECT year_level_name FROM tbl_year_level WHERE year_level_id = '$delete_id'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);
    $yearLevelName = $row['year_level_name'];

    echo '<script>';
    echo 'if (confirm("Are you sure you want to delete the year level ' . $yearLevelName . '?")) {';
    echo '    window.location.href = "delete_yearlevel.php?confirmed=' . $delete_id . '";';
    echo '} else {';
    echo '    window.location.href = "yearlevel.php";';
    echo '}';
    echo '</script>';
}

if (isset($_GET['confirmed'])) {
    $confirmed_id = $_GET['confirmed'];
    $deleteQuery = "DELETE FROM tbl_year_level WHERE year_level_id = '$confirmed_id'";
    $result = mysqli_query($conn, $deleteQuery);
    $conn->query("SET FOREIGN_KEY_CHECKS=1;");
    if ($result) {
        header('location: yearlevel.php');
    } else {
        echo "<div class='alert alert-danger'>Error deleting year level: " . mysqli_error($conn) . "</div>";
    }
}
mysqli_close($conn);
?>
