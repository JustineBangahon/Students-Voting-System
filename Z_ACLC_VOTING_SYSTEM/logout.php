<?php
// Start or resume the current session
session_start();

// Destroy all session data
session_destroy();

// Redirect to the login page or any other desired location
header("Location: adminlogin.php");
exit;
?>
