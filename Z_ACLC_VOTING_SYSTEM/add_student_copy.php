<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["csv_file"]) && $_FILES["csv_file"]["error"] == 0) {
        $file = $_FILES["csv_file"]["tmp_name"];
        $handle = fopen($file, "r");

        // Skip the header row
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $student_usn = $data[0];
            $last_name = $data[1];
            $first_name = $data[2];
            $middle_name = $data[3];
            $year_level_id = $data[4];
            $course_id = $data[5];

            $voting_code = generateVotingCode($conn);

            $query = "SELECT * FROM tblstudent WHERE student_usn = '$student_usn'";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                echo '<script>alert("Student with USN ' . $student_usn . ' already exists in the database. Skipping.");</script>';
                continue;
            }

            $yearLevelQuery = "SELECT year_level_id, year_level_name FROM tbl_year_level";
            $yearLevelResult = mysqli_query($conn, $yearLevelQuery);
            $yearLevelRow = mysqli_fetch_assoc($yearLevelResult);
            $year_level_name = $yearLevelRow['year_level_name'];

            $courseQuery = "SELECT * FROM tblcourse WHERE course_id = $course_id";
            $courseResult = mysqli_query($conn, $courseQuery);
            $courseRow = mysqli_fetch_assoc($courseResult);
            $course_initial = $courseRow['course_initial'];

            $sql = "INSERT INTO tblstudent (student_usn, last_name, first_name, middle_name, year_level_id, course_id, voting_code, vote_status) 
            VALUES ('$student_usn', '$last_name', '$first_name', '$middle_name', $year_level_id, '$course_id', '$voting_code', 1)";

            if (mysqli_query($conn, $sql)) {
                echo '<script>alert("Student added successfully. Voting Code: ' . $voting_code . '");</script>';
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
            }
        }

        fclose($handle);
    }
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
    <title>Add Student</title>
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
                <!-- Navigation links here -->
            </ul>
            <ul class="navbar-nav">
                <!-- Logout link here -->
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Add Student</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="csv_file">CSV File:</label>
                <input type="file" class="form-control-file" id="csv_file" name="csv_file" required accept=".csv">
            </div>
            <button type="submit" class="btn btn-primary">Add Students</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
