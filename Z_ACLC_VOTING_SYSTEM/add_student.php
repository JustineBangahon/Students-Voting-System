<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_usn = $_POST["student_usn"];
    $last_name = $_POST["last_name"];
    $first_name = $_POST["first_name"];
    $middle_name = $_POST["middle_name"];
    $year_level_id = $_POST["year_level_id"];
    $course_id = $_POST["course_id"];

    // Disable foreign key checks
    mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS = 0');

    $voting_code = generateVotingCode($conn);

    $query = "SELECT * FROM tblstudent WHERE student_usn = '$student_usn'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo '<script>alert("Student already exists in the database."); window.location.href = "add_student.php";</script>';
    } else {
        $yearLevelQuery = "SELECT year_level_name FROM tbl_year_level WHERE year_level_id = $year_level_id";
        $yearLevelResult = mysqli_query($conn, $yearLevelQuery);
        $yearLevelRow = mysqli_fetch_assoc($yearLevelResult);
        $year_level_name = $yearLevelRow['year_level_name'];

        $courseQuery = "SELECT course_name FROM tblcourse WHERE course_id = $course_id";
        $courseResult = mysqli_query($conn, $courseQuery);
        $courseRow = mysqli_fetch_assoc($courseResult);
        $course_name = $courseRow['course_name'];

        $sql = "INSERT INTO tblstudent (student_usn, last_name, first_name, middle_name, year_level_id, course_id, voting_code, vote_status) 
        VALUES ('$student_usn', '$last_name', '$first_name', '$middle_name', $year_level_id, '$course_id', '$voting_code', 1)";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Student added successfully. Voting Code: ' . $voting_code . '"); window.location.href = "student.php";</script>';
        } else {
            if (mysqli_errno($conn) == 1062) {
                echo '<script>alert("Duplicate entry found for student USN."); window.location.href = "add_student.php";</script>';
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
            }
        }
    }

    // Enable foreign key checks
    mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS = 1');

    mysqli_close($conn);
}

function generateVotingCode($conn)
{
    $unique = false;
    $voting_code = "";
    while (!$unique) {
        $voting_code = generateRandomCode();

        $query = "SELECT * FROM tblstudent WHERE voting_code = '$voting_code'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            $unique = true;
        }
    }

    return $voting_code;
}

function generateRandomCode()
{
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $code_length = 8;
    $code = "";

    for ($i = 0; $i < $code_length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
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


    <div class="container mt-4">
        <h2 class="text-center">Add Student</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="student_usn">Student USN:</label>
                <input type="text" class="form-control" id="student_usn" name="student_usn" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name" required>
            </div>

            <div class="form-group">
                <label for="year_level_id">Year Level:</label>
                <select class="form-control" id="year_level_id" name="year_level_id">
                    <?php
                    $yearLevelQuery = "SELECT year_level_id, year_level_name FROM tbl_year_level";
                    $yearLevelResult = $conn->query($yearLevelQuery);

                    if ($yearLevelResult->num_rows > 0) {
                        while ($row = $yearLevelResult->fetch_assoc()) {
                            echo '<option value="' . $row['year_level_id'] . '">' . $row['year_level_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="course_id">Course:</label>
                <select class="form-control" id="course_id" name="course_id">
                    <?php
                    $courseQuery = "SELECT course_id, course_name FROM tblcourse";
                    $courseResult = $conn->query($courseQuery);

                    if ($courseResult->num_rows > 0) {
                        while ($row = $courseResult->fetch_assoc()) {
                            echo '<option value="' . $row['course_id'] . '">' . $row['course_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <a href="student.php" class="text-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Add Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>