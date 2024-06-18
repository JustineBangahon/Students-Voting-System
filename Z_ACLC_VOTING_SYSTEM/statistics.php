<?php
include 'config.php';

// Fetch positions
$positionsQuery = "SELECT * FROM tbl_candidate_position ORDER BY sort_order";
$positionsResult = $conn->query($positionsQuery);

// Fetch candidates' vote count for each position
$positionCandidates = array();
if ($positionsResult->num_rows > 0) {
    while ($positionRow = $positionsResult->fetch_assoc()) {
        $positionId = $positionRow['candidate_position_id'];
        $positionName = $positionRow['position_name'];

        $candidatesQuery = "SELECT tblcandidate.*, COUNT(tblvote.candidate_id) AS vote_count FROM tblcandidate 
            INNER JOIN tblvote ON tblcandidate.candidate_id = tblvote.candidate_id
            WHERE tblcandidate.candidate_position_id = $positionId
            GROUP BY tblcandidate.candidate_id";
        $candidatesResult = $conn->query($candidatesQuery);

        $positionCandidates[] = array(
            'position_id' => $positionId,
            'position_name' => $positionName,
            'candidates' => $candidatesResult->fetch_all(MYSQLI_ASSOC)
        );
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Graphical Statistics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Styles here */
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Graphical Statistics</h1>

        <?php foreach ($positionCandidates as $position) : ?>
            <div class="position-card card">
                <div class="card-header position-name"><?php echo $position['position_name']; ?></div>
                <div class="card-body">
                    <canvas id="position-<?php echo $position['position_id']; ?>-chart"></canvas>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        <?php foreach ($positionCandidates as $position) : ?>
            var position<?php echo $position['position_id']; ?>Canvas = document.getElementById('position-<?php echo $position['position_id']; ?>-chart').getContext('2d');
            var position<?php echo $position['position_id']; ?>Data = {
                labels: [
                    <?php foreach ($position['candidates'] as $candidate) {
                        echo "'" . $candidate['first_name'] . ' ' . $candidate['last_name'] . "', ";
                    } ?>
                ],
                datasets: [{
                    label: 'Vote Count',
                    data: [
                        <?php foreach ($position['candidates'] as $candidate) {
                            echo $candidate['vote_count'] . ', ';
                        } ?>
                    ],
                    backgroundColor: '#007bff',
                    borderWidth: 0
                }]
            };
            var position<?php echo $position['position_id']; ?>Options = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            };
            var position<?php echo $position['position_id']; ?>Chart = new Chart(position<?php echo $position['position_id']; ?>Canvas, {
                type: 'bar',
                data: position<?php echo $position['position_id']; ?>Data,
                options: position<?php echo $position['position_id']; ?>Options
            });
        <?php endforeach; ?>
    </script>
</body>
</html>
