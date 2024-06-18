<?php include 'config.php' ?>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data
  $electionDate = $_POST['election_date'];
  $studentID = $_POST['student_id'];
  $partyID = $_POST['party_id'];
  $positionID = $_POST['position_id'];

  // Check if the candidate already exists
  $checkQuery = "SELECT * FROM tblcandidate WHERE student_id = ?";
  $checkStmt = $conn->prepare($checkQuery);
  $checkStmt->bind_param("s", $studentID);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result();

  if ($checkResult->num_rows > 0) {
    // Candidate already exists
    echo '<div class="alert alert-danger">Candidate already exists.</div>';
  } else {
    // Check if the election date exists
    $electionDateQuery = "SELECT * FROM tbl_election_date WHERE election_date_id = ?";
    $electionDateStmt = $conn->prepare($electionDateQuery);
    $electionDateStmt->bind_param("s", $electionDate);
    $electionDateStmt->execute();
    $electionDateResult = $electionDateStmt->get_result();

    if ($electionDateResult->num_rows > 0) {
      // Check if the maximum number of candidates for the position in the party has been reached
      $positionQuery = "SELECT allow_per_party FROM tbl_candidate_position WHERE candidate_position_id = ?";
      $positionStmt = $conn->prepare($positionQuery);
      $positionStmt->bind_param("s", $positionID);
      $positionStmt->execute();
      $positionResult = $positionStmt->get_result();

      if ($positionResult->num_rows > 0) {
        $positionRow = $positionResult->fetch_assoc();
        $allowPerParty = $positionRow['allow_per_party'];

        $candidateQuery = "SELECT COUNT(*) AS candidate_count FROM tblcandidate WHERE party_id = ? AND candidate_position_id = ?";
        $candidateStmt = $conn->prepare($candidateQuery);
        $candidateStmt->bind_param("ss", $partyID, $positionID);
        $candidateStmt->execute();
        $candidateResult = $candidateStmt->get_result();

        if ($candidateResult->num_rows > 0) {
          $candidateRow = $candidateResult->fetch_assoc();
          $candidateCount = $candidateRow['candidate_count'];

          if ($candidateCount >= $allowPerParty) {
            // Maximum number of candidates for this position in the party has been reached
            echo '<div class="alert alert-danger">Maximum number of candidates for this position in the party has been reached.</div>';
          } else {
            // Insert the new candidate into the database
            $insertQuery = "INSERT INTO tblcandidate (student_id, party_id, candidate_position_id) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("sss", $studentID, $partyID, $positionID);

            if ($insertStmt->execute()) {
              // Redirect back to candidate.php
              header("Location: candidate.php");
              exit();
            } else {
              echo '<div class="alert alert-danger">Error adding candidate: ' . $conn->error . '</div>';
            }
          }
        }
      }
    } else {
      // Election date does not exist
      echo '<div class="alert alert-danger">Invalid election date.</div>';
    }
  }
}
?>





<!DOCTYPE html>
<html>

<head>
    <title>Add Candidates</title>
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
    <div class="container">
        <h2 class="text-center">Add Candidates</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="election_date">Election Date:</label>
                <select class="form-control" id="election_date" name="election_date">
                    <?php
                    // Fetch election dates from the database
                    $electionQuery = "SELECT election_date_id, election_date FROM tbl_election_date";
                    $electionResult = $conn->query($electionQuery);

                    if ($electionResult->num_rows > 0) {
                        while ($row = $electionResult->fetch_assoc()) {
                            echo '<option value="' . $row['election_date_id'] . '">' . $row['election_date'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="student_id">Student:</label>
                <select class="form-control" id="student_id" name="student_id">
                    <?php
                    // Fetch students from the database
                    $studentQuery = "SELECT student_id, student_usn, CONCAT(last_name, ' ', first_name, ' ', middle_name) AS full_name FROM tblstudent";
                    $studentResult = $conn->query($studentQuery);

                    if ($studentResult->num_rows > 0) {
                        while ($row = $studentResult->fetch_assoc()) {
                            echo '<option value="' . $row['student_id'] . '">' . $row['student_usn'] . ' - ' . $row['full_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="party_id">Party:</label>
                <select class="form-control" id="party_id" name="party_id">
                    <?php
                    // Fetch parties from the database
                    $partyQuery = "SELECT party_id, party_name FROM tblparty";
                    $partyResult = $conn->query($partyQuery);

                    if ($partyResult->num_rows > 0) {
                        while ($row = $partyResult->fetch_assoc()) {
                            echo '<option value="' . $row['party_id'] . '">' . $row['party_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="position_id">Candidate Position:</label>
                <select class="form-control" id="position_id" name="position_id">
                    <?php
                    // Fetch candidate positions from the database
                    $positionQuery = "SELECT candidate_position_id, position_name FROM tbl_candidate_position";
                    $positionResult = $conn->query($positionQuery);

                    if ($positionResult->num_rows > 0) {
                        while ($row = $positionResult->fetch_assoc()) {
                            echo '<option value="' . $row['candidate_position_id'] . '">' . $row['position_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <a href="candidate.php" class="text-danger">Cancel</a>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
