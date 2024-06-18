<?php
include 'config.php';

$query = "SELECT * FROM tbladmin";
$result = mysqli_query($conn, $query);


if ($result) {

    $admin = mysqli_fetch_assoc($result);


    if ($admin) {
        $adminId = $admin['admin_id'];
        $adminName = $admin['name'];
        $adminUsername = $admin['username'];
        $adminPassword = $admin['password'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $enteredUsername = $_POST['username'];
            $enteredPassword = $_POST['password'];

            if ($enteredUsername === $adminUsername && $enteredPassword === $adminPassword) {
                //$errorMessage = 'Login successful';
                echo '<script>alert("Login successful!");</script>';
                echo '<script> window.location.href = "admin_dashboard.php";</script>';
                exit();

            } else {
                echo '<script>alert("Invalid username or password");</script>';
                echo '<script> window.location.href = "adminlogin.php";</script>';
            }
        }
    } else {
        $errorMessage = 'No admin data found';
        echo '<script>alert("No admin data found");</script>';
    }
} else {
    $errorMessage = 'Error in executing query';
    echo '<script>alert("Error in executing query");</script>';
}

mysqli_close($conn);
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
    <!DOCTYPE html>
    <html>

    <head>



        <title>Admin Login</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
            }

            .container {
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                background-color: #ffffff;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
            }

            form {
                margin-top: 20px;
            }

            label {
                display: block;
                margin-bottom: 10px;
                font-weight: bold;
            }

            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #4caf50;
                border: none;
                border-radius: 5px;
                color: #ffffff;
                font-size: 16px;
                cursor: pointer;
            }

            .error-message {
                color: red;
                margin-top: 10px;
            }
        </style>
    </head>

<body>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <div class="container">
        <h1>Admin Login</h1>

        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="username">Username:</label>
            <input type="text" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            <div class="text-center my-3 ">
            <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <p class="text-center"> <a class="text-danger" href="../Testing_are2/studentlogin.php">student</a> </p>
        </form>
    </div>
</body>

</html>