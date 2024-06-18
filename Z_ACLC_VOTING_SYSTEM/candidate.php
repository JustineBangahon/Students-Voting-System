<?php include 'config.php' ?>

<!DOCTYPE html>
<html>

<head>
    <title>Candidate</title>
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

        <h2 class="text-center">Candidates</h2>
        <a href="add_candidate.php" class="btn btn-primary mb-3">Add Candidate</a>

        <table class="table">
            <thead>
                <tr>
                    <th>USN</th>
                    <th>Full Name</th>
                    <th>Party</th>
                    <th>Candidate Position</th>
                    <th>Election Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
                // Fetch candidate information from the database
                $query = "SELECT c.candidate_id, p.party_name, ted.election_date, tcp.position_name, s.student_usn, s.last_name, s.first_name, s.middle_name
              FROM tblcandidate c
              INNER JOIN tblparty p ON c.party_id = p.party_id
              INNER JOIN tbl_election_date ted ON p.electiondate_id = ted.election_date_id
              INNER JOIN tbl_candidate_position tcp ON c.candidate_position_id = tcp.candidate_position_id
              INNER JOIN tblstudent s ON c.student_id = s.student_id";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // Display candidate information in table rows
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['student_usn'] . "</td>";
                        echo "<td>" . $row['last_name'] . " " . $row['first_name'] . " " . $row['middle_name'] . "</td>";
                        echo "<td>" . $row['party_name'] . "</td>";
                        echo "<td>" . $row['position_name'] . "</td>";
                        echo "<td>" . $row['election_date'] . "</td>";
                        echo '<td><a class="btn btn-sm btn-primary mr-2" href="update_candidate.php?updateid=' . $row['candidate_id'] . '">Update</a> 
                            <a class="btn btn-sm btn-danger mr-2" href="delete_candidate.php?deleteid=' . $row['candidate_id'] . '">Delete</a></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No candidates found.</td></tr>";
                }
                ?>

            </tbody>
        </table>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>