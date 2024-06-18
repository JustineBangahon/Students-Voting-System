<?php include 'config.php' ?>


<?php


// Define variables and set to empty values
$yearLevelID = $yearLevelInitial = $yearLevelName = '';
$yearLevelIDErr = '';

// Function to sanitize input data
function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $yearLevelID = sanitize($_POST['yearLevelID']);
    $yearLevelInitial = sanitize($_POST['yearLevelInitial']);
    $yearLevelName = sanitize($_POST['yearLevelName']);

    if (empty($yearLevelID)) {
        $yearLevelIDErr = 'Year level ID is required';
    }

    if (empty($yearLevelIDErr)) {

        $checkQuery = "SELECT * FROM tbl_year_level WHERE year_level_initial = '$yearLevelInitial' AND year_level_id != '$yearLevelID'";
        $result = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($result) > 0) {

            echo '<script>alert("The updated year level already exists in the database.");';
            echo 'window.location.href = "update_yearlevel.php";</script>';
        } else {

            $updateQuery = "UPDATE tbl_year_level SET year_level_initial = '$yearLevelInitial', year_level_name = '$yearLevelName' WHERE year_level_id = '$yearLevelID'";

            if (mysqli_query($conn, $updateQuery)) {
                echo '<script>alert("Year level updated successfully.");';
                echo 'window.location.href = "yearlevel.php";</script>';
            } else {

                echo "<div class='alert alert-danger'>Error updating year level: " . mysqli_error($conn) . "</div>";
            }
        }
    }
} else {

    if (isset($_GET['updateid'])) {
        $yearLevelID = $_GET['updateid'];
        $selectQuery = "SELECT * FROM tbl_year_level WHERE year_level_id = '$yearLevelID'";
        $result = mysqli_query($conn, $selectQuery);
        $row = mysqli_fetch_assoc($result);
        $yearLevelInitial = $row['year_level_initial'];
        $yearLevelName = $row['year_level_name'];
    } else {
        header("Location: yearlevel.php");
        exit();
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
        <h2 class="text-center">Update Year Level</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="yearLevelID" value="<?php echo $yearLevelID; ?>">

            <div class="form-group">
                <label for="yearLevelInitial">Year Level Initial:</label>
                <input type="text" class="form-control" name="yearLevelInitial" value="<?php echo $yearLevelInitial; ?>" required>
            </div>

            <div class="form-group">
                <label for="yearLevelName">Year Level Name:</label>
                <input type="text" class="form-control" name="yearLevelName" value="<?php echo $yearLevelName; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="yearlevel.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>