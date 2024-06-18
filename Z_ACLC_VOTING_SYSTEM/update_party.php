<?php include 'config.php' ?>

<!DOCTYPE html>
<html>

<head>
    <title>Year Level</title>
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
                <li class="nav-item">
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
                <li class="nav-item active">
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

    <?php

        if (isset($_GET['updateid'])) {
            $update_id = $_GET['updateid'];

            // Fetch party details from tblparty
            $query = "SELECT * FROM tblparty WHERE party_id = '$update_id'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $party_name = $row['party_name'];
                $election_date_id = $row['electiondate_id'];
        ?>
        <div class="container">
        <h2 class="text-center">Update Year Level</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="party_name">Party Name:</label>
                <input type="text" class="form-control" id="party_name" name="party_name" value="<?php echo $party_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="election_date">Election Date:</label>
                <select class="form-control" id="election_date" name="election_date" required>
                    <?php
                    // Fetch election dates from tbl_election_date
                    $election_date_query = "SELECT * FROM tbl_election_date";
                    $election_date_result = mysqli_query($conn, $election_date_query);

                    if (mysqli_num_rows($election_date_result) > 0) {
                        while ($election_date_row = mysqli_fetch_assoc($election_date_result)) {
                            $selected = ($election_date_row['election_date_id'] == $election_date_id) ? 'selected' : '';
                            echo '<option value="' . $election_date_row['election_date_id'] . '" ' . $selected . '>' . $election_date_row['election_date'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No election dates found</option>';
                    }
                    ?>
                </select>
            </div>


            <button type="submit" class="btn btn-primary" name="submit">Update Party</button>
        </form>
        <?php
            } else {
                echo '<div class="alert alert-danger">Party not found.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Invalid request.</div>';
        }
        ?>

        <?php
        // Process form submission
        if (isset($_POST['submit'])) {
            $party_name = $_POST['party_name'];
            $election_date_id = $_POST['election_date'];

            // Check if the party already exists, excluding the current party being updated
            $check_query = "SELECT * FROM tblparty WHERE party_name = '$party_name' AND party_id != '$update_id'";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                echo '<script>alert("Party already exists in the database.");</script>';
            } else {
                // Update party in tblparty
                $update_query = "UPDATE tblparty SET party_name = '$party_name', electiondate_id = '$election_date_id' WHERE party_id = '$update_id'";
                $update_result = mysqli_query($conn, $update_query);

                if ($update_result) {
                    echo '<script>alert("Party updated successfully.");</script>';
                    echo '<script>window.location.href = "party.php";</script>';
                } else {
                    echo '<script>alert("Failed to update party.");</script>';
                }
            }
        }
        ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>