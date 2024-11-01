<?php

session_start();

include('includes/db_connect.inc');

// Fetch the last 4 entries from the pets table
$query = "SELECT image, petname FROM pets ORDER BY petid DESC LIMIT 4";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets Victoria - Welcome to Pet Adoption</title>
    <?php include('includes/header.inc'); ?> 
</head>
<body>

    <?php include('includes/nav.inc'); ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="hero">
            <!-- Left Column for Carousel -->
            <div class="carousel">
                <div id="petCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
                    <div class="carousel-inner">
                        <?php
                        // Check if there are pets to display in the carousel
                        if ($result->num_rows > 0) {
                            $active = 'active';
                            while ($row = $result->fetch_assoc()) {
                                $image = $row['image'];
                                $petname = $row['petname'];

                                // Display each pet as a carousel item
                                echo '
                                <div class="carousel-item ' . $active . '">
                                    <img src="images/' . htmlspecialchars($image) . '" class="d-block w-100" alt="' . htmlspecialchars($petname) . '" style="max-height: 450px; object-fit: cover;">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>' . htmlspecialchars($petname) . '</h5>
                                    </div>
                                </div>';
                                $active = ''; // Only the first item should be active
                            }
                        } else {
                            echo '<div class="carousel-item active">
                                    <img src="images/default.jpg" class="d-block w-100" alt="No pets available" style="max-height: 450px; object-fit: cover;">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>No Pets Available</h5>
                                    </div>
                                  </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Right Column for Heading and Subheading -->
            <div>
                <h1 class="display-4 index_heading permanent-Marker">PETS VICTORIA</h1>
                <h2>WELCOME TO PET ADOPTION</h2>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-form">
            <form action="search.php" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="I am looking for..." aria-label="Search">
                <select name="type" class="form-select me-2">
                    <option value="">Select your pet type</option>
                    <option value="cat">Cat</option>
                    <option value="dog">Dog</option>
                </select>
                <button type="submit" class="btn btn-success">Search</button>
            </form>
        </div>

        <!-- Description Section -->
        <div class="text-center mt-4">
            <h3>Discover Pets Victoria</h3>
            <p class="mt-3">
                Pets Victoria is a dedicated pet adoption organization based in Victoria, Australia, focused on providing a safe and loving environment for pets in need. With a compassionate approach, Pets Victoria works tirelessly to rescue, rehabilitate, and rehome dogs, cats, and other animals. Their mission is to connect these deserving pets with caring individuals and families, creating lifelong bonds.
            </p>
        </div>
    </div>

    
    <?php include('includes/footer.inc'); ?>

</body>
</html>
