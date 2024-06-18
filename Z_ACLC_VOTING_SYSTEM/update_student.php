<?php include 'config.php' ?>

<?php
// Check if the student ID is provided
if (isset($_GET['updateid'])) {
    $update_id = $_GET['updateid'];

    // Retrieve the existing student data
    $query = "SELECT s.*, y.year_level_name, c.course_name
              FROM tblstudent s
              INNER JOIN tbl_year_level y ON s.year_level_id = y.year_level_id
              INNER JOIN tblcourse c ON s.course_id = c.course_id
              WHERE s.student_id = $update_id";
    $result = mysqli_query($conn, $query);

    // Check if a matching record is found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $student_usn = $row['student_usn'];
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $year_level_id = $row['year_level_id'];
        $course_id = $row['course_id'];
    } else {

        echo '<script>alert("Student record not found.");</script>';
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $student_usn = $_POST["student_usn"];
        $last_name = $_POST["last_name"];
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $year_level_id = $_POST["year_level"];
        $course_id = $_POST["course"];

        // Check if the updated data already exists in the database
        $query = "SELECT * FROM tblstudent WHERE student_usn = '$student_usn' AND student_id != $update_id";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            // Updated data already exists, display an alert message
            echo '<script>alert("Updated data already exists in the database."); window.location.href = "update_student.php?updateid='.$update_id.'";</script>';
        } else {
            // Update the student data in the tblstudent table
            $sql = "UPDATE tblstudent 
                    SET student_usn = '$student_usn', last_name = '$last_name', 
                    first_name = '$first_name', middle_name = '$middle_name',
                    year_level_id = $year_level_id, course_id = $course_id
                    WHERE student_id = $update_id";

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
            </div>
            <div>
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $first_name; ?>" required>
            </div>
            <div>
                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control" value="<?php echo $middle_name; ?>" required>
            </div>
            <div>
                <label for="year_level">Year Level:</label>
                <select name="year_level" id="year_level" class="form-control" required>
                    <?php
                    // Retrieve year levels from the database
                    $query = "SELECT * FROM tbl_year_level";
                    $result = mysqli_query($conn, $query);

                    // Generate options for year levels
                    while ($row = mysqli_fetch_assoc($result)) {
                        $year_level_id = $row['year_level_id'];
                        $year_level_name = $row['year_level_name'];
                        $selected = ($year_level_id == $year_level_id) ? 'selected' : '';
                        echo '<option value="' . $year_level_id . '" ' . $selected . '>' . $year_level_name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="course">Course:</label>
                <select name="course" id="course" class="form-control" required>
                    <?php
                    // Retrieve courses from the database
                    $query = "SELECT * FROM tblcourse";
                    $result = mysqli_query($conn, $query);

                    // Generate options for courses
                    while ($row = mysqli_fetch_assoc($result)) {
                        $course_id = $row['course_id'];
                        $course_name = $row['course_name'];
                        $selected = ($course_id == $course_id) ? 'selected' : '';
                        echo '<option value="' . $course_id . '" ' . $selected . '>' . $course_name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <a href="student.php" class="btn btn-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Update student</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
