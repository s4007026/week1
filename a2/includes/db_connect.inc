<?php
// Database connection file

// Localhost or Jupiter server details (modify as needed for deployment)
$host = 'localhost'; // Change this to your Jupiter server if deploying
$dbname = 'petsvictoria'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create a connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if connection fails
    die("Connection failed: " . $e->getMessage());
}
?>
