<?php
include 'config.php';



if (isset($_GET['deleteid'])) {
    $partyID = $_GET['deleteid'];
    $conn->query("SET FOREIGN_KEY_CHECKS=0;");

    $selectQuery = "SELECT * FROM tblparty WHERE party_id = '$partyID'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);

    $partyName = $row['party_name'];
    $electionDateID = $row['electiondate_id'];

    if (isset($_GET['confirm'])) {
        $deleteQuery = "DELETE FROM tblparty WHERE party_id = '$partyID'";
        mysqli_query($conn, $deleteQuery);
       

        if (mysqli_affected_rows($conn) > 0) {
            header('Location: party.php');
            $conn->query("SET FOREIGN_KEY_CHECKS=1;");
            exit();
        } else {
            echo '<script>alert("Failed to delete the party. Please try again."); window.location.href = "party.php";</script>';
            exit();
        }
        $conn->query("SET FOREIGN_KEY_CHECKS=1;");
    } else {
        echo '<script>
            if (confirm("Are you sure you want to delete the party: ' . $partyID . ' - ' . $electionDateID . '?")) {
                window.location.href = "delete_party.php?deleteid=' . $partyID . '&confirm=true";
            } else {
                window.location.href = "party.php";
            }
        </script>';
        exit();
    }
} else {
    header("Location: party.php");
    exit();
}
?>