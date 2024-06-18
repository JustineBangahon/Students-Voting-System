<?php include 'config.php' ?>

<?php


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = $_POST['courseId'];
    $courseInitial = $_POST['courseInitial'];
    $courseName = $_POST['courseName'];

    // Check if the updated data already exists in the database
    $checkQuery = "SELECT * FROM tblcourse WHERE course_initial = '$courseInitial' AND course_name = '$courseName' AND course_id != '$courseId'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If the same data already exists, display an alert message and redirect back to course.php
        echo '<script>alert("The same course data already exists in the database."); window.location.href = "course.php";</script>';
        exit();
    }

    // Update the course in the database
    $updateQuery = "UPDATE tblcourse SET course_initial = '$courseInitial', course_name = '$courseName' WHERE course_id = '$courseId'";
    mysqli_query($conn, $updateQuery);

    // Check if the update was successful
    if (mysqli_affected_rows($conn) > 0) {
        // Display a success message and redirect back to course.php
        echo '<script>alert("Course updated successfully."); window.location.href = "course.php";</script>';
        exit();
    } else {
        // Display an error message if the update failed
        echo '<script>alert("Failed to update the course. Please try again.");</script>';
    }
}

// Check if the course ID is provided in the URL
if (isset($_GET['updateid'])) {
    $courseId = $_GET['updateid'];

    // Retrieve the course details from the database
    $selectQuery = "SELECT * FROM tblcourse WHERE course_id = '$courseId'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);

    $courseInitial = $row['course_initial'];
    $courseName = $row['course_name'];
} else {
    // If the course ID is not provided, redirect back to course.php
    header("Location: course.php");
    exit();
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
                <li class="nav-item active">
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
    <h2 class="text-center">Update Course</h2>
    <form action="" method="post">
            <input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
            <div class="form-group">
                <label for="courseInitial">Course Initial:</label>
                <input type="text" class="form-control" id="courseInitial" name="courseInitial" value="<?php echo $courseInitial; ?>" required>
            </div>
            <div class="form-group">
                <label for="courseName">Course Name:</label>
                <input type="text" class="form-control" id="courseName" name="courseName" value="<?php echo $courseName; ?>" required>
            </div>
            <a href="course.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>