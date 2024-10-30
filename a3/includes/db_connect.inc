<?php

if (strstr($_SERVER['SERVER_NAME'], 'localhost')) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "petsvic";
    $imagePath="C:\xampp\htdocs\wp\a3\images";
} 

  else {
    $servername = "talsprddb02.int.its.rmit.edu.au";
    $username = "s4007026";
    $password = "KkAkSummer13!!";
    $dbname = "petsvictoria";
    $imagePath="/~s4007026/public_html/wp/a2/images";
}


try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }


} catch (Exception $e) {
    echo " Error: " . $e->getMessage();  
}

?>