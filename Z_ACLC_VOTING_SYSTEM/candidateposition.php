
<?php
        // Include the database connection configuration
        include_once 'config.php';

        // Initialize variables
        $positionName = $sortOrder = $votesAllowed = $allowPerParty = '';
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data
            $positionName = $_POST['position_name'];
            $sortOrder = $_POST['sort_order'];
            $votesAllowed = $_POST['votes_allowed'];
            $allowPerParty = $_POST['allow_per_party'];

            // Check if the candidate position already exists
            $checkQuery = "SELECT * FROM tbl_candidate_position WHERE position_name = '$positionName'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                $errorMsg = 'The candidate position already exists in the database.';
            } else {
                // Insert the candidate position into the database
                $insertQuery = "INSERT INTO tbl_candidate_position (position_name, sort_order, votes_allowed, allow_per_party) 
                                VALUES ('$positionName', '$sortOrder', '$votesAllowed', '$allowPerParty')";

                if (mysqli_query($conn, $insertQuery)) {
                    echo "<div class='alert alert-success'>Candidate position added successfully.</div>";
                    // Reset form data
                    $positionName = $sortOrder = $votesAllowed = $allowPerParty = '';
                } else {
                    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                }
            }
        }
        ?>

<!DOCTYPE html>
<html>

<head>
    <title>Candidate Position</title>
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
                <li class="nav-item active">
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

    <div class="container">
        <h2 class="text-center">Candidate Positions</h2>
        <a href="add_candidateposition.php" class="btn btn-primary mb-2">Add Candidate Position</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Position Name</th>
                    <th>Sort Order</th>
                    <th>Votes Allowed</th>
                    <th>Allow Per Party</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include the database connection configuration
                include_once 'config.php';

                // Fetch candidate positions from the database
                $sql = "SELECT * FROM tbl_candidate_position";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['position_name'] . "</td>";
                        echo "<td>" . $row['sort_order'] . "</td>";
                        echo "<td>" . $row['votes_allowed'] . "</td>";
                        echo "<td>" . $row['allow_per_party'] . "</td>";
                        echo "<td>";
                        echo "<a href='update_candidateposition.php?updateid=" . $row['candidate_position_id'] . "' class='btn btn-primary btn-sm'>Update</a>";
                        echo " <a href='delete_candidateposition.php?deleteid=" . $row['candidate_position_id'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No candidate positions found</td></tr>";
                }

                // Close database connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>