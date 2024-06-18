<?php include 'config.php' ?>
<?php
        $yearLevelInitial = $yearLevelName = '';
        $yearLevelInitialErr = '';
        function sanitize($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $yearLevelInitial = sanitize($_POST['yearLevelInitial']);
            $yearLevelName = sanitize($_POST['yearLevelName']);
            if (empty($yearLevelInitial)) {
                $yearLevelInitialErr = 'Year level initial is required';
            }
            $checkQuery = "SELECT * FROM tbl_year_level WHERE year_level_initial = '$yearLevelInitial'";
            $checkResult = mysqli_query($conn, $checkQuery);
            if (mysqli_num_rows($checkResult) > 0) {         
                echo '<script>alert("Year level already exists in the database.");';
                echo 'window.location.href = "add_yearlevel.php";</script>';
            } elseif (empty($yearLevelInitialErr)) {
               
                $insertQuery = "INSERT INTO tbl_year_level (year_level_initial, year_level_name) VALUES ('$yearLevelInitial', '$yearLevelName')";

                if (mysqli_query($conn, $insertQuery)) {
                    
                    echo '<script>alert("Year level added successfully.");';
                    echo 'window.location.href = "yearlevel.php";</script>';
                    $yearLevelInitial = $yearLevelName = ''; 
                } else {
                    echo "<div class='alert alert-danger'>Error adding year level: " . mysqli_error($conn) . "</div>";
                }
            }
        }


        mysqli_close($conn);
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
        <h2 class="text-center">Add Year Level</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="yearLevelInitial">Year Level Initial:</label>
                <input type="text" class="form-control" id="yearLevelInitial" name="yearLevelInitial" require="required" value="<?php echo $yearLevelInitial; ?>">
                <span class="text-danger"><?php echo $yearLevelInitialErr; ?></span>
            </div>
            <div class="form-group">
                <label for="yearLevelName">Year Level Name:</label>
                <input type="text" class="form-control" id="yearLevelName" name="yearLevelName" require="required" value="<?php echo $yearLevelName; ?>">
            </div>
            <a href="yearlevel.php" class="text-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>