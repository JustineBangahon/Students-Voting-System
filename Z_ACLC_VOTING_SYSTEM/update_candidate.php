<?php include 'config.php'; ?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $candidateID = $_POST['candidate_id'];
        $studentID = $_POST['student_id'];
        $partyID = $_POST['party_id'];
        $positionID = $_POST['position_id'];
        $electionDate = $_POST['election_date'];

        // Check if the candidate already exists
        $checkQuery = "SELECT * FROM tblcandidate WHERE student_id = $studentID AND party_id = $partyID AND candidate_position_id = $positionID AND candidate_id != $candidateID";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // Candidate already exists
            echo '<div class="alert alert-danger">Candidate already exists.</div>';
        } else {
            // Update the candidate's information in the database
            $updateQuery = "UPDATE tblcandidate SET student_id = $studentID, party_id = $partyID, candidate_position_id = $positionID WHERE candidate_id = $candidateID";

            if ($conn->query($updateQuery) === TRUE) {
                echo '<div class="alert alert-success">Candidate updated successfully.</div>';
            } else {
                echo '<div class="alert alert-danger">Error updating candidate: ' . $conn->error . '</div>';
            }
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Candidates</title>
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
                <li class="nav-item active">
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
    <?php
    // Retrieve candidate information from the database
    $candidateID = $_GET['updateid'];

    // Query to fetch candidate information
    $query = "SELECT c.candidate_id, c.student_id, c.party_id, c.candidate_position_id, p.party_name, tcp.position_name, s.student_usn, s.last_name, s.first_name, s.middle_name, ted.election_date
              FROM tblcandidate c
              INNER JOIN tblparty p ON c.party_id = p.party_id
              INNER JOIN tbl_candidate_position tcp ON c.candidate_position_id = tcp.candidate_position_id
              INNER JOIN tblstudent s ON c.student_id = s.student_id
              INNER JOIN tbl_election_date ted ON p.electiondate_id = ted.election_date_id
              WHERE c.candidate_id = $candidateID";

    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $studentID = $row['student_id'];
        $partyID = $row['party_id'];
        $positionID = $row['candidate_position_id'];
        $electionDate = $row['election_date'];
?>


<div class="container">
        <h2 class="text-center">Update Admin</h2>
        <form method="POST" action="">
    <input type="hidden" name="candidate_id" value="<?php echo $row['candidate_id']; ?>">
    <!-- Other candidate information fields -->
    <div class="form-group">
        <label for="student_id">Student:</label>
        <select name="student_id" class="form-control">
        <?php
                $studentQuery = "SELECT * FROM tblstudent";
                $studentResult = $conn->query($studentQuery);

                while ($studentRow = $studentResult->fetch_assoc()) {
                    $selected = ($studentRow['student_id'] == $studentID) ? "selected" : "";
                    $studentName = $studentRow['last_name'] . ', ' . $studentRow['first_name'] . ' ' . $studentRow['middle_name'];
                    echo "<option value='" . $studentRow['student_id'] . "' " . $selected . ">" . $studentName . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="party_id">Party:</label>
        <select name="party_id" class="form-control">
            <?php
                $partyQuery = "SELECT * FROM tblparty";
                $partyResult = $conn->query($partyQuery);

                while ($partyRow = $partyResult->fetch_assoc()) {
                    $selected = ($partyRow['party_id'] == $partyID) ? "selected" : "";
                    echo "<option value='" . $partyRow['party_id'] . "' " . $selected . ">" . $partyRow['party_name'] . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="position_id">Candidate Position:</label>
        <select name="position_id" class="form-control">
            <?php
                $positionQuery = "SELECT * FROM tbl_candidate_position";
                $positionResult = $conn->query($positionQuery);

                while ($positionRow = $positionResult->fetch_assoc()) {
                    $selected = ($positionRow['candidate_position_id'] == $positionID) ? "selected" : "";
                    echo "<option value='" . $positionRow['candidate_position_id'] . "' " . $selected . ">" . $positionRow['position_name'] . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="election_date">Election Date:</label>
        <select name="election_date" class="form-control">
            <?php
                $electionDateQuery = "SELECT * FROM tbl_election_date";
                $electionDateResult = $conn->query($electionDateQuery);

                while ($electionDateRow = $electionDateResult->fetch_assoc()) {
                    $selected = ($electionDateRow['election_date'] == $electionDate) ? "selected" : "";
                    echo "<option value='" . $electionDateRow['election_date_id'] . "' " . $selected . ">" . $electionDateRow['election_date'] . "</option>";
                }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Candidate</button>
</form>

<?php
    } else {
        echo '<div class="alert alert-danger">Candidate not found.</div>';
    }
?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>