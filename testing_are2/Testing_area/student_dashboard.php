<?php
include 'config.php';
$query = "SELECT c.candidate_id, CONCAT(s.last_name, ' ', s.first_name, ' ', s.middle_name) AS full_name, cp.position_name, p.party_name 
          FROM tblcandidate c
          JOIN tblstudent s ON c.student_id = s.student_id
          JOIN tbl_candidate_position cp ON c.candidate_position_id = cp.candidate_position_id
          JOIN tblparty p ON c.party_id = p.party_id
          ORDER BY cp.sort_order";
$result = mysqli_query($conn, $query);
$candidates = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected candidate IDs
    $selectedCandidates = $_POST['candidateId'];

    // Check if any candidates are selected
    if (!empty($selectedCandidates)) {
        $studentCode = $_SESSION['student_code'];
        $query = "SELECT * FROM tblstudent WHERE voting_code = '$studentCode'";
        $result = mysqli_query($conn, $query);
        $student = mysqli_fetch_assoc($result);

        if ($student) {
            $voteStatus = $student['vote_status'];

            if ($voteStatus == 1) {
                $studentId = $student['student_id'];

                // Increment the vote count for each selected candidate
                foreach ($selectedCandidates as $candidateId) {
                    $query = "UPDATE tblcandidate SET vote_count = vote_count + 1 WHERE candidate_id = $candidateId";
                    mysqli_query($conn, $query);
                }

                $query = "UPDATE tblstudent SET vote_status = 0 WHERE student_id = $studentId";
                mysqli_query($conn, $query);
                echo 'Your vote has been recorded.';
            } else {
                echo 'You have already voted.';
            }
        } else {
            header("Location: studentlogin.php");
            exit();
        }
    } else {
        echo 'Please select at least one candidate.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="mt-4 mb-4">Student Dashboard</h2>
        <form method="POST" action="studentdashboard.php">
            <?php foreach ($candidates as $candidate) : ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $candidate['full_name']; ?></h5>
                        <p class="card-text">Position: <?php echo $candidate['position_name']; ?></p>
                        <p class="card-text">Party: <?php echo $candidate['party_name']; ?></p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="candidateId[]" value="<?php echo $candidate['candidate_id']; ?>" id="vote<?php echo $candidate['candidate_id']; ?>">
                            <label class="form-check-label" for="vote<?php echo $candidate['candidate_id']; ?>">
                                Vote
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Submit Votes</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
