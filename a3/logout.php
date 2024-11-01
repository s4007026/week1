<?php

session_start();

$_SESSION = [];

session_destroy();

// Redirect to the homepage
header('Location: index.php');
exit();
?>
