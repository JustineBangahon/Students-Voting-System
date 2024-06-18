<?php include 'config.php' ?>


<?php
// Connect to the database
$host = 'localhost';
$db = 'a_voting_system';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random voting code
function generateVotingCode($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the election date input
    $electionDate = $_POST["election_date"];
    
    // Check if the election date already exists
    $checkSql = "SELECT * FROM tbl_election_date WHERE election_date = '$electionDate'";
    $checkResult = $conn->query($checkSql);
    
    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Election date already exists.');</script>";
    } else {
        // Insert the election date into the database
        $insertSql = "INSERT INTO tbl_election_date (election_date) VALUES ('$electionDate')";
        if ($conn->query($insertSql) === TRUE) {
            // Reset Voting Code of students and generate new codes
            $resetSql = "UPDATE tblstudent SET voting_code = NULL, vote_status = 1";
            $conn->query($resetSql);
            
            // Generate and update unique voting codes for students
            $studentsSql = "SELECT student_id FROM tblstudent";
            $studentsResult = $conn->query($studentsSql);
            
            while ($row = $studentsResult->fetch_assoc()) {
                $studentId = $row['student_id'];
                $votingCode = generateVotingCode();
                
                $updateSql = "UPDATE tblstudent SET voting_code = '$votingCode' WHERE student_id = '$studentId'";
                $conn->query($updateSql);
            }
            
            echo "<script>alert('Election date added successfully, Students Voting Code will be randomly change, and the Students Voting Status will turned into Not Voted');</script>";
            echo "<script>window.location.href = 'electiondate.php';</script>";
            exit;
        } else {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
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
                <li class="nav-item active">
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
        <h2 class="text-center">Add Student</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="electionDate">Election Date:</label>
                <input type="date" class="form-control" id="electionDate" name="election_date" required>
            </div>
            <a href="electiondate.php" class="text-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Add Election Date</button>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>