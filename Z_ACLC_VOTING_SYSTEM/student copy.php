<?php include 'config.php' ?>


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
        <h2 class="text-center">Student Records</h2>
        <a href="add_student.php" class="btn btn-primary mb-3">Add Student</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Student USN</th>
                    <th>Full Name</th>
                    <th>Year Level</th>
                    <th>Course</th>
                    <th>Voting Code</th>
                    <th>Vote Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
// Query to fetch student records with year level name and course name
$query = "SELECT s.student_id, s.student_usn, s.last_name, s.first_name, s.middle_name, y.year_level_name, c.course_name, s.voting_code, s.vote_status
          FROM tblstudent s
          INNER JOIN tbl_year_level y ON s.year_level_id = y.year_level_id
          INNER JOIN tblcourse c ON s.course_id = c.course_id";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $student_id = $row['student_id'];
        $student_usn = $row['student_usn'];
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $year_level_name = $row['year_level_name'];
        $course_name = $row['course_name'];
        $voting_code = $row['voting_code'];
        $vote_status = $row['vote_status'];
?>
        <tr>
            <td><?php echo $student_usn; ?></td>
            <td><?php echo $last_name . ', ' . $first_name . ' ' . $middle_name; ?></td>
            <td><?php echo $year_level_name; ?></td>
            <td><?php echo $course_name; ?></td>
            <td><?php echo $voting_code; ?></td>
            <td><?php echo $vote_status == '0' ? 'Voted' : 'Not Voted'; ?></td>
            <td>
                <a href="update_student.php?updateid=<?php echo $student_id; ?>" class="btn btn-primary btn-sm">Update</a>
                <a href="delete_student.php?deleteid=<?php echo $student_id; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
<?php
    }
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}
?>





    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>