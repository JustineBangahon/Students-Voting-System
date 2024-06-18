<?php
include 'config.php';
include 'vote.php';

// Fetch all elected candidates and their details
$query = "SELECT c.*, CONCAT(s.last_name, ' ', s.first_name, ' ', s.middle_name) AS full_name,
            p.position_name, p.sort_order, pa.party_name
            FROM tblcandidate c
            INNER JOIN tblstudent s ON c.student_id = s.student_id
            INNER JOIN tbl_candidate_position p ON c.candidate_position_id = p.candidate_position_id
            INNER JOIN tblparty pa ON c.party_id = pa.party_id
            ORDER BY p.sort_order";

$result = mysqli_query($conn, $query);
?>

<!-- HTML code to display the elected candidates -->
<h2>Elected Candidates</h2>

<?php
// Check if there are candidates in the database
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>";
        echo "<h3>" . $row['full_name'] . "</h3>";
        echo "<p>Position: " . $row['position_name'] . "</p>";
        echo "<p>Party: " . $row['party_name'] . "</p>";

        // Check if the student has already voted
        $votingCode = $_SESSION['votingCode'];

        $checkVoteQuery = "SELECT vote_status FROM tblstudent WHERE voting_code = '$votingCode'";
        $checkVoteResult = mysqli_query($conn, $checkVoteQuery);

        if (mysqli_num_rows($checkVoteResult) > 0) {
            $voteStatusRow = mysqli_fetch_assoc($checkVoteResult);
            $voteStatus = $voteStatusRow['vote_status'];

            if ($voteStatus == 0) {
                echo "<p>You have already voted for this position.</p>";
            } else {
                echo "<button class='vote-button' data-candidate-id='" . $row['candidate_id'] . "'>Vote</button>";
            }
        }

        echo "</div>";
    }
} else {
    echo "<p>No candidates available.</p>";
}
?>

<!-- JavaScript code to handle the vote button click -->
<script>
    // Get all vote buttons
    var voteButtons = document.getElementsByClassName('vote-button');

    // Attach event listeners to each vote button
    Array.from(voteButtons).forEach(function(button) {
        button.addEventListener('click', function() {
            // Get the candidate ID
            var candidateId = button.getAttribute('data-candidate-id');

            // AJAX request to cast the vote
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'vote.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText);
                    // Refresh the page to reflect the updated vote status
                    location.reload();
                }
            };
            xhr.send('candidateId=' + candidateId + '&votingCode=' + '<?php echo $votingCode; ?>');
        });
    });
</script>
