<?php
// Include database connection
include './include/config.php';

// Check if admin ID is provided
if (!isset($_GET['updateid'])) {
    // Redirect to admin.php if ID is not provided
    header("Location: admin.php");
    exit;
}

// Retrieve admin ID from the URL
$admin_id = $_GET['updateid'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for duplicate name or username
    $stmt = $conn->prepare('SELECT admin_id FROM tbladmin WHERE (name = ? OR username = ?) AND admin_id <> ?');
    $stmt->bind_param('ssi', $name, $username, $admin_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Duplicate name or username found
        echo '<script>alert("Error: Name or username already exists in the database.");';
        echo 'window.location.href = "admin.php";</script>';
        exit;
    }

    // Prepare the UPDATE statement
    $stmt = $conn->prepare('UPDATE tbladmin SET name = ?, username = ?, password = ? WHERE admin_id = ?');

    // Bind parameters and execute the statement
    $stmt->bind_param('sssi', $name, $username, $password, $admin_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Admin updated successfully
        echo '<script>alert("Admin updated successfully.");';
        echo 'window.location.href = "admin.php";</script>';
    } else {
        // Error occurred while updating admin
        echo '<script>alert("Error: Failed to update admin.");';
        echo 'window.location.href = "admin.php";</script>';
    }

    // Close the statement
    $stmt->close();
}

// Retrieve admin details from the database
$stmt = $conn->prepare('SELECT name, username, password FROM tbladmin WHERE admin_id = ?');
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$stmt->bind_result($name, $username, $password);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Year Level</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="./student.php">Student</a>
                </li>
                <li class="nav-item active">
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
        <h2 class="text-center">Update Admin</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" required>
            </div>
            <div class="form-group">
                <a href="admin.php" class="text-danger">Cancel</a>
                <button type="submit" class="btn btn-primary"> Update</button>

            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>