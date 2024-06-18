<?php
include 'config.php';

$sqlTotalStudents = "SELECT COUNT(*) AS total_students FROM tblstudent";
$resultTotalStudents = mysqli_query($conn, $sqlTotalStudents);
$rowTotalStudents = mysqli_fetch_assoc($resultTotalStudents);
$totalStudents = $rowTotalStudents['total_students'];

$sqlVotedStudents = "SELECT COUNT(*) AS voted_students FROM tblstudent WHERE vote_status = 'Voted'";
$resultVotedStudents = mysqli_query($conn, $sqlVotedStudents);
$rowVotedStudents = mysqli_fetch_assoc($resultVotedStudents);
$votedStudents = $rowVotedStudents['voted_students'];

$notVotedStudents = $totalStudents - $votedStudents;

$sqlLiveResult = "SELECT candidate_id, COUNT(*) AS vote_count FROM tblvote GROUP BY candidate_id";
$resultLiveResult = mysqli_query($conn, $sqlLiveResult);
$liveResult = mysqli_fetch_all($resultLiveResult, MYSQLI_ASSOC);

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
    
    .statistics {
      margin-bottom: 30px;
      text-align: center;
    }
    
    .statistics h3 {
      margin-bottom: 20px;
    }
    
    .statistics p {
      font-size: 18px;
      margin-bottom: 10px;
    }
    
    .live-result {
      margin-top: 30px;
    }
    
    .live-result h3 {
      margin-bottom: 20px;
    }
    
    .live-result p {
      font-size: 18px;
      margin-bottom: 5px;
    }
    
    .live-result p i {
      margin-right: 5px;
    }
  </style>
</head>
<body>
  <div class="container mb-5 ">
    <h1 class="text-center mb-4">Dashboard</h1>
    
    <div class="statistics">
      <h3>Voting Statistics:</h3>
      <div class="row">
        <div class="col-md-4">
          <p><i class="bi bi-check-circle-fill text-success"></i> Total Voted Students: <?php echo $votedStudents; ?></p>
        </div>
        <div class="col-md-4">
          <p><i class="bi bi-x-circle-fill text-danger"></i> Total Not Voted Students: <?php echo $notVotedStudents; ?></p>
        </div>
        <div class="col-md-4">
          <p><i class="bi bi-people-fill"></i> Total Voters: <?php echo $totalStudents; ?></p>
        </div>
      </div>
    </div>
    
    <div class="live-result">
      <h3>Live Result:</h3>
      <?php foreach ($liveResult as $result) : ?>
        <p><i class="bi bi-poll"></i> Candidate <?php echo $result['candidate_id']; ?>: <?php echo $result['vote_count']; ?> votes</p>
      <?php endforeach; ?>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
