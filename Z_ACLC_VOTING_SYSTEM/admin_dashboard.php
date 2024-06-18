<?php
include 'config.php';

// Fetch positions
$positionsQuery = "SELECT * FROM tbl_candidate_position ORDER BY sort_order";
$positionsResult = $conn->query($positionsQuery);

// Get total voters count
$totalVotersQuery = "SELECT COUNT(DISTINCT student_id) AS total_voters FROM tblcandidate";
$totalVotersResult = $conn->query($totalVotersQuery);
$totalVotersRow = $totalVotersResult->fetch_assoc();
$totalVoters = $totalVotersRow['total_voters'];

// Get total not voted students count
$notVotedStudentsQuery = "SELECT COUNT(*) AS not_voted_students FROM tblstudent WHERE student_id NOT IN (SELECT DISTINCT student_id FROM tblcandidate)";
$notVotedStudentsResult = $conn->query($notVotedStudentsQuery);
$notVotedStudentsRow = $notVotedStudentsResult->fetch_assoc();
$notVotedStudents = $notVotedStudentsRow['not_voted_students'];

// Get total voted students count
$votedStudents = $totalVoters - $notVotedStudents;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .stat-card {
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .card-body {
            padding: 20px;
        }

        .stat-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 0;
        }

        .position-card {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
        }

        .position-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .candidate-list {
            list-style: none;
            padding: 0;
        }

        .candidate-list li {
            margin-bottom: 5px;
        }

        .candidate-name {
            font-weight: bold;
        }

        .party-name {
            margin-left: 10px;
        }

        .vote-count {
            font-weight: bold;
        }

        .chart-container {
            height: 200px;
            margin-bottom: 10px;
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
                    <a class="nav-link active" href="./admin_dashboard.php">Dashboard</a>
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
                <li class="nav-item ">
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
    <h1 class="text-center mb-4">Dashboard</h1>

    <!-- Overall Voting Statistics: Pie Chart -->
    <div class="chart-container">
        <canvas id="overall-chart"></canvas>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="stat-card card">
                <div class="card-header">Total Candidates</div>
                <div class="card-body">
                    <p class="stat-label">Total Candidates</p>
                    <p class="stat-value"><?php echo $totalVoters; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
          
        </div>

        <div class="col-md-4">
            <div class="stat-card card">
         
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($positionsResult->num_rows > 0) {
        while ($positionRow = $positionsResult->fetch_assoc()) {
            $positionId = $positionRow['candidate_position_id'];
            $positionName = $positionRow['position_name'];
            $votesAllowed = $positionRow['votes_allowed'];

            echo '<div class="position-card card" data-position-id="' . $positionId . '" data-votes-allowed="' . $votesAllowed . '">';
            echo '<div class="card-header position-name">' . $positionName . '</div>';
            echo '<div class="card-body">';
            echo '<ul class="candidate-list">';

            // Fetch candidates for the current position
            $candidatesQuery = "SELECT tblcandidate.*, tblparty.party_name, tblstudent.first_name, tblstudent.last_name, COUNT(tblvote.candidate_id) AS vote_count FROM tblcandidate 
                INNER JOIN tblstudent ON tblcandidate.student_id = tblstudent.student_id
                INNER JOIN tblparty ON tblcandidate.party_id = tblparty.party_id
                LEFT JOIN tblvote ON tblcandidate.candidate_id = tblvote.candidate_id
                WHERE tblcandidate.candidate_position_id = $positionId
                GROUP BY tblcandidate.candidate_id";
            $candidatesResult = $conn->query($candidatesQuery);

            if ($candidatesResult->num_rows > 0) {
                while ($candidateRow = $candidatesResult->fetch_assoc()) {
                    $candidateId = $candidateRow['candidate_id'];
                    $candidateName = $candidateRow['first_name'] . ' ' . $candidateRow['last_name'];
                    $partyName = $candidateRow['party_name'];
                    $voteCount = $candidateRow['vote_count'];

                    echo '<li>';
                    echo '<span class="candidate-name">' . $candidateName . '</span>';
                    echo ' -';
                    echo '<span class="party-name">' . $partyName . '</span>';
                    echo ' ';
                    echo '<span class="vote-count">Vote Count: ' . $voteCount . '</span>';
                    echo '</li>';
                }
            } else {
                echo '<li>No candidates found.</li>';
            }

            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-12">';
        echo '<p>No positions found.</p>';
        echo '</div>';
    }
    ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Overall Voting Statistics: Pie Chart
    var overallChartCanvas = document.getElementById('overall-chart').getContext('2d');
    var overallChartData = {
        labels: ['Voted Students', 'Not Voted Students'],
        datasets: [{
            data: [<?php echo $votedStudents; ?>, <?php echo $notVotedStudents; ?>],
            backgroundColor: ['#007bff', '#dc3545'],
            borderWidth: 0
        }]
    };
    var overallChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'bottom',
            labels: {
                boxWidth: 10,
                padding: 15
            }
        }
    };
    var overallChart = new Chart(overallChartCanvas, {
        type: 'pie',
        data: overallChartData,
        options: overallChartOptions
    });
</script>
</body>
</html>
