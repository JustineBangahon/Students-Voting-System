<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: studentlogin.php");
    exit();
}

// End the current session
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote Successful</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Vote Successful</h2>
        <p class="text-center">Thank you for casting your vote!</p>
        <div class="text-center mt-4">
            <a href="studentlogin.php" class="btn btn-primary">End Session</a>
        </div>
    </div>
</body>
</html>
