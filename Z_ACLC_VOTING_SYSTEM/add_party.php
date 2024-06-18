<?php include 'config.php' ?>


<?php
    // Process form submission
    if (isset($_POST['submit'])) {
        $party_name = $_POST['party_name'];
        $election_date_id = $_POST['election_date'];
        
        // Check if the party already exists
        $check_query = "SELECT * FROM tblparty WHERE party_name = '$party_name'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            echo '<script>alert("Party already exists in the database.");</script>';
        } else {
            // Insert new party into tblparty
            $insert_query = "INSERT INTO tblparty (party_name, electiondate_id) VALUES ('$party_name', '$election_date_id')";
            $insert_result = mysqli_query($conn, $insert_query);
            
            if ($insert_result) {
                echo '<script>alert("Party added successfully.");</script>';
                echo '<script>window.location.href = "party.php";</script>';
            } else {
                echo '<script>alert("Failed to add party.");</script>';
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
        <h2 class="text-center">Add Party</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="party_name">Party Name:</label>
                <input type="text" class="form-control" id="party_name" name="party_name" required>
            </div>
            <div class="form-group">
                <label for="election_date">Election Date:</label>
                <select class="form-control" id="election_date" name="election_date" required>
                    <?php
                    // Include database connection
                    include 'db_connection.php';
                    
                    // Fetch election dates from tbl_election_date
                    $query = "SELECT * FROM tbl_election_date";
                    $result = mysqli_query($conn, $query);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['election_date_id'] . '">' . $row['election_date'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No election dates found</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Add Party</button>
        </form>
    </div>

    <?php
    if (isset($_GET['duplicate']) && $_GET['duplicate'] === 'true') {
        echo '<script>alert("Party already exists!");</script>';
    }
    ?>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>