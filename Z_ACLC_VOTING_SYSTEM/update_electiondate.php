<?php include 'config.php' ?>

<?php
// Check if the election date ID is provided in the URL
if (isset($_GET['updateid'])) {
    $electionDateId = $_GET['updateid'];

    // Retrieve the current election date from the database
    $sql = "SELECT * FROM tbl_election_date WHERE election_date_id = '$electionDateId'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $currentElectionDate = $row['election_date'];

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the new election date input
            $newElectionDate = $_POST["new_election_date"];

            // Update the election date in the database
            $updateSql = "UPDATE tbl_election_date SET election_date = '$newElectionDate' WHERE election_date_id = '$electionDateId'";
            if ($conn->query($updateSql) === TRUE) {
                echo "<script>alert('Election date updated successfully.');</script>";
                echo "<script>window.location.href = 'electiondate.php';</script>";
                exit;
            } else {
                echo "Error: " . $updateSql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Invalid election date ID.";
        exit;
    }
} else {
    echo "Election date ID is not provided.";
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Election Date</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            padding-top: 60px;
        }

        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand text-primary" href="#">VOTING SYSTEM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="./admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./electiondate.php">Election Date</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./candidate.php">Candidates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./candidateposition.php">Position</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="./course.php">Course</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./yearlevel.php">Year Level</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./party.php">Party</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./student.php">Student</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./admin.php">Admin</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Update Election Date</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?updateid=' . $electionDateId; ?>">
            <div class="form-group">
                <label for="newElectionDate">New Election Date:</label>
                <input type="date" class="form-control" id="newElectionDate" name="new_election_date" value="<?php echo $currentElectionDate; ?>" required>
            </div>
            <a href="electiondate.php" class="text-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Election Date</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
