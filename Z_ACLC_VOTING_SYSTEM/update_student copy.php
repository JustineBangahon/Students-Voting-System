<?php include 'config.php' ?>

<?php
// Check if the student ID is provided
if (isset($_GET['updateid'])) {
    $update_id = $_GET['updateid'];


    // Retrieve the existing student data
    $query = "SELECT * FROM tblstudent WHERE student_id = $update_id";
    $result = mysqli_query($conn, $query);


    // Check if a matching record is found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $student_usn = $row['student_usn'];
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
    } else {

        echo '<script>alert("Student record not found.");</script>';
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $student_usn = $_POST["student_usn"];
        $last_name = $_POST["last_name"];
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];

        // Check if the updated data already exists in the database
        $query = "SELECT * FROM tblstudent WHERE student_usn = '$student_usn' AND student_id != $update_id";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            // Updated data already exists, display an alert message
            echo '<script>alert("Updated data already exists in the database."); window.location.href = "update_student.php";</script>';
        } else {
            // Update the student data in the tblstudent table
            $sql = "UPDATE tblstudent SET student_usn = '$student_usn', last_name = '$last_name', 
                    first_name = '$first_name', middle_name = '$middle_name' WHERE student_id = $update_id";

            if (mysqli_query($conn, $sql)) {
                echo '<script>alert("Student data updated successfully."); window.location.href = "student.php";</script>';
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
            }
        }

        // Close the database connection
        mysqli_close($conn);
    }
} else {
    // No student ID provided, display an error message
    echo '<script>alert("Invalid student ID.");</script>';
    exit;
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
                <li class="nav-item active">
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
        <h2 class="text-center">Update student</h2>
        <form method="POST" action="">
            <div>
                <label for="student_usn">Student USN:</label>
                <input type="text" name="student_usn" id="student_usn" class="form-control" value="<?php echo $student_usn; ?>" required>
            </div>
            <div>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $last_name; ?>" required>
                <div>
                    <div>
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $first_name; ?>" required>
                    </div>
                    <div>
                        <label for="middle_name">Middle Name:</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control" value="<?php echo $middle_name; ?>" required><br>
                    </div>
                    <!--<input type="submit" value="Update"> -->

                    <a href="student.php" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Update student</button>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>