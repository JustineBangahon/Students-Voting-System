<?php include 'config.php' ?>

<?php

$positionName = $sortOrder = $votesAllowed = $allowPerParty = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $positionName = $_POST['position_name'];
    $sortOrder = $_POST['sort_order'];
    $votesAllowed = $_POST['votes_allowed'];
    $allowPerParty = $_POST['allow_per_party'];

    $checkQuery = "SELECT * FROM tbl_candidate_position WHERE position_name = '$positionName'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo '<script>alert("You enter is already exists.");</script>';
        //$errorMsg = 'The candidate position already exists in the database.';
    } else {
        $insertQuery = "INSERT INTO tbl_candidate_position (position_name, sort_order, votes_allowed, allow_per_party) 
                                VALUES ('$positionName', '$sortOrder', '$votesAllowed', '$allowPerParty')";

        if (mysqli_query($conn, $insertQuery)) {
            echo '<script>alert("Candidate position added successfully.");</script>';
            echo '<script>window.location.href = "candidateposition.php";</script>';


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
                <li class="nav-item ">
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
        <h2 class="text-center">Add Candidate Positon</h2>
        <form method="post">
            <div class="form-group">
                <label for="position_name">Position Name:</label>
                <input type="text" class="form-control" id="position_name" name="position_name" value="<?php echo htmlspecialchars($positionName ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="sort_order">Sort Order:</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo htmlspecialchars($sortOrder ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="votes_allowed">Votes Allowed:</label>
                <input type="number" class="form-control" id="votes_allowed" name="votes_allowed" value="<?php echo htmlspecialchars($votesAllowed ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="allow_per_party">Allow Per Party:</label>
                <input type="number" class="form-control" id="allow_per_party" name="allow_per_party" value="<?php echo htmlspecialchars($allowPerParty ?? ''); ?>" required>
            </div>
            <?php
            //if (!empty($errorMsg)) {
             //   echo "<div class='alert alert-danger'>$errorMsg</div>";
           // }
            ?>
            <a href="candidateposition.php" class="text-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Add</button>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>