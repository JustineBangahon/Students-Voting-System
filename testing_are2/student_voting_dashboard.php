<?php
session_start();

// Check if the student is not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: studentlogin.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_vote'])) {
    // Retrieve the selected candidates
    $selectedCandidates = $_POST['candidates'];
    // Check if any candidate is selected
    if (!empty($selectedCandidates)) {
        // Create a database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "a_voting_system";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO tblvote (candidate_id) VALUES (?)");

        // Bind the candidate_id parameter
        $stmt->bind_param("i", $candidateId);

        // Insert the selected candidates into the database
        foreach ($selectedCandidates as $positionId => $positionCandidates) {
            foreach ($positionCandidates as $candidateId) {
                $stmt->execute();
            }
        }

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Update the vote status of the student
        $studentId = $_SESSION['student_id'];

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE tblstudent SET vote_status = '0' WHERE student_id = ?");

        // Bind the student_id parameter
        $stmt->bind_param("i", $studentId);

        // Execute the update query
        $stmt->execute();
        

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Redirect to a page indicating successful voting
        header("Location: vote_success.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Voting Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }

        .position-card {
            margin-bottom: 20px;
        }

        .position-card .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .candidate-list {
            list-style-type: none;
            padding: 0;
        }

        .candidate-list li {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4">Student Voting Dashboard</h2>
        <form method="POST" action="">
            <?php
            // Create a database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "a_voting_system";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch positions
            $positionsQuery = "SELECT * FROM tbl_candidate_position ORDER BY sort_order";
            $positionsResult = $conn->query($positionsQuery);

            if ($positionsResult->num_rows > 0) {
                // Iterate over each position
                while ($positionRow = $positionsResult->fetch_assoc()) {
                    $positionId = $positionRow['candidate_position_id'];
                    $positionName = $positionRow['position_name'];
                    $votesAllowed = $positionRow['votes_allowed'];

                    echo '<div class="position-card card" data-position-id="' . $positionId . '" data-votes-allowed="' . $votesAllowed . '">';
                    echo '<div class="card-header">' . $positionName . '</div>';
                    echo '<div class="card-body">';
                    echo '<ul class="candidate-list">';

                    // Fetch candidates for the current position
                    $candidatesQuery = "SELECT tblcandidate.*, tblparty.party_name, tblstudent.first_name, tblstudent.last_name FROM tblcandidate 
                        INNER JOIN tblstudent ON tblcandidate.student_id = tblstudent.student_id
                        INNER JOIN tblparty ON tblcandidate.party_id = tblparty.party_id
                        WHERE tblcandidate.candidate_position_id = $positionId";
                    $candidatesResult = $conn->query($candidatesQuery);

                    // Initialize variables
                    $selectedCandidatesCount = 0;
                    $candidateVoteCount = [];

                    // Iterate over each candidate
                    while ($candidateRow = $candidatesResult->fetch_assoc()) {
                        $candidateId = $candidateRow['candidate_id'];
                        $candidateName = $candidateRow['first_name'] . ' ' . $candidateRow['last_name'];
                        $partyName = $candidateRow['party_name'];

                        echo '<li>';
                        echo '<label>';

                        // Check if the candidate is selected and not reached the vote_allowed limit
                        if (isset($_POST['candidates'][$positionId]) && in_array($candidateId, $_POST['candidates'][$positionId]) && $selectedCandidatesCount < $votesAllowed) {
                            echo '<input type="checkbox" name="candidates[' . $positionId . '][]" value="' . $candidateId . '" checked> ' . $candidateName . ' - ' . $partyName;
                            $selectedCandidatesCount++;
                        } else {
                            echo '<input type="checkbox" name="candidates[' . $positionId . '][]" value="' . $candidateId . '"> ' . $candidateName . ' - ' . $partyName;
                        }

                        // Check if the vote count reached the votes_allowed limit
                        if (!isset($candidateVoteCount[$candidateId])) {
                            $candidateVoteCount[$candidateId] = 0;
                        }

                        echo '</label>';
                        echo '</li>';
                    }

                    echo '</ul>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No positions found.";
            }

            $conn->close();
            ?>

            <div class="text-center mt-4">
                <button type="submit" name="submit_vote" class="btn btn-primary">Submit Vote</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');

        function handleCheckboxChange() {
            var positionId = this.closest(".position-card").dataset.positionId;
            var allowedLimit = this.closest(".position-card").dataset.votesAllowed;
            var checkedCount = 0;

            var positionCheckboxes = document.querySelectorAll('input[name="candidates[' + positionId + '][]"]');
            for (var i = 0; i < positionCheckboxes.length; i++) {
                if (positionCheckboxes[i].checked) {
                    checkedCount++;
                }
            }

            if (checkedCount > allowedLimit) {
                this.checked = false;
            }

            if (checkedCount === allowedLimit) {
                for (var i = 0; i < positionCheckboxes.length; i++) {
                    if (!positionCheckboxes[i].checked) {
                        positionCheckboxes[i].disabled = true;
                    }
                }
            } else {
                for (var i = 0; i < positionCheckboxes.length; i++) {
                    positionCheckboxes[i].disabled = false;
                }
            }
        }

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener("change", handleCheckboxChange);
        });
    </script>
</body>

</html>