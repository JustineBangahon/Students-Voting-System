<?php include './include/config.php'; ?>

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
                <li class="nav-item active">
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
        <h2 class="text-center">Year Levels</h2>
        <a href="add_yearlevel.php" class="btn btn-primary mb-3">Add Year Level</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Year Level Initial</th>
                    <th>Year Level Name</th>
                    <th>Population</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                 // Fetch year levels from the database
                $query = "SELECT * FROM tbl_year_level";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $yearLevelID = $row['year_level_id'];
                        $yearLevelInitial = $row['year_level_initial'];
                        $yearLevelName = $row['year_level_name'];

                        // Get population for the current year level
                        $populationQuery = "SELECT COUNT(*) AS population FROM tblstudent WHERE year_level_id = $yearLevelID";
                        $populationResult = mysqli_query($conn, $populationQuery);
                        $populationRow = mysqli_fetch_assoc($populationResult);
                        $population = $populationRow['population'];

                        echo "<tr>";
                        echo "<td>$yearLevelInitial</td>";
                        echo "<td>$yearLevelName</td>";
                        echo "<td>$population</td>";
                        echo "<td>
                                <a href='update_yearlevel.php?updateid=$yearLevelID' class='btn btn-primary btn-sm'>Update</a>
                                <a href='delete_yearlevel.php?deleteid=$yearLevelID' class='btn btn-danger btn-sm'>Delete</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No year levels found.</td></tr>";
                }

                // Close database connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
