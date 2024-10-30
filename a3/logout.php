<?php
// Start session management
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the homepage
header('Location: index.php');
exit();
?>
