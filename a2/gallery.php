<!DOCTYPE html>
<html lang="en"> <!-- Added lang attribute to declare the language of the document -->
<head>
    <meta charset="UTF-8"> <!-- Declaring character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Meta tag for responsiveness -->
    <title>Gallery</title> <!-- Added a title element -->
</head>
<body>

<?php
include 'includes/header.inc';  // Header section including DOCTYPE, head, and start of body
include 'includes/nav.inc';     // Navigation section
?>

<main>
    <h2>Pets Victoria has a lot to offer!</h2>
    <p>For almost two decades, Pets Victoria has helped in creating true social change by bringing pet adoption into the mainstream. Our work has helped make a difference to the Victorian rescue community and thousands of pets in need of rescue and rehabilitation. But, until every pet is safe, respected, and loved, we all still have so, so, so much work to do.</p>
    
    <div class="gallery">
        <div class="pet-card">
            <div class="img-container">
                <a href="details.php?id=1">
                    <img src="images/cat1.jpeg" alt="Milo">
                    <div class="overlay">
                        <span>Discover More</span>
                    </div>
                </a>
            </div>
            <p><a href="details.php?id=1">Milo</a></p>
        </div>
        <div class="pet-card">
            <div class="img-container">
                <a href="details.php?id=2">
                    <img src="images/dog1.jpeg" alt="Willow">
                    <div class="overlay">
                        <span>Discover More</span>
                    </div>
                </a>
            </div>
            <p><a href="details.php?id=2">Willow</a></p>
        </div>
        <div class="pet-card">
            <div class="img-container">
                <a href="details.php?id=3">
                    <img src="images/cat4.jpeg" alt="Luna">
                    <div class="overlay">
                        <span>Discover More</span>
                    </div>
                </a>
            </div>
            <p><a href="details.php?id=3">Luna</a></p>
        </div>
        <div class="pet-card">
            <div class="img-container">
                <a href="details.php?id=4">
                    <img src="images/dog4.jpeg" alt="Baxter">
                    <div class="overlay">
                        <span>Discover More</span>
                    </div>
                </a>
            </div>
            <p><a href="details.php?id=4">Baxter</a></p>
        </div>
        <div class="pet-card">
            <div class="img-container">
                <a href="details.php?id=5">
                    <img src="images/cat3.jpeg" alt="Oliver">
                    <div class="overlay">
                        <span>Discover More</span>
                    </div>
                </a>
            </div>
            <p><a href="details.php?id=5">Oliver</a></p>
        </div>
        <div class="pet-card">
            <div class="img-container">
                <a href="details.php?id=6">
                    <img src="images/dog3.jpeg" alt="Bella">
                    <div class="overlay">
                        <span>Discover More</span>
                    </div>
                </a>
            </div>
            <p><a href="details.php?id=6">Bella</a></p>
        </div>
    </div>
</main>

<?php
include 'includes/footer.inc';  // Footer section with closing body and HTML tags
?>

</body>
</html>
