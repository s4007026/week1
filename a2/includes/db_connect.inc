<?php

$hostname = "talsprddb02.int.its.rmit.edu.au";
$username = "s4007026";
$password = "KkAkSummer13!!";
$database = "petsvictoria";

try {
    // Create connection
    $conn = new mysqli($hostname, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>