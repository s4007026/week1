<?php
// Include necessary files
include('includes/db_connect.inc'); // Database connection
include('includes/header.inc'); // Header content
include('includes/nav.inc'); // Navigation content
include 'index.html'; // or require 'index.html';
?>

?>

<main>
    <div class="wrapper">
        <h1>Welcome to Pets Victoria</h1>
        <p>At Pets Victoria, we help adorable pets find loving homes. Browse through our collection of available pets and help them find their forever home.</p>
        <p>Check out the gallery or browse through our list of pets to see who is waiting for you!</p>
        <a href="pets.php" class="btn">View All Pets</a>
        <a href="gallery.php" class="btn">View Gallery</a>
    </div>
</main>

<?php
// Include footer content
include('includes/footer.inc');
?>
