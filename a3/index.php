<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Fetch the last 4 added pets for the carousel
$query = "SELECT image, petname FROM pets ORDER BY petid DESC LIMIT 4";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('includes/nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center">Welcome to Pets Victoria</h1>
        <p class="text-center">Find your perfect pet today!</p>

        <!-- Image Carousel -->
        <div id="petCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                // Check if there are any pets to display
                if ($result->num_rows > 0) {
                    $active = true; // Flag for setting the first item as active
                    while ($row = $result->fetch_assoc()) {
                        $image = $row['image'];
                        $name = $row['name'];
                        echo '<div class="carousel-item' . ($active ? ' active' : '') . '">';
                        echo '<img src="images/' . htmlspecialchars($image) . '" class="d-block w-100" alt="' . htmlspecialchars($name) . '" style="height: 300px; object-fit: cover;">';
                        echo '<div class="carousel-caption d-none d-md-block">';
                        echo '<h5>' . htmlspecialchars($name) . '</h5>';
                        echo '</div></div>';
                        $active = false;
                    }
                } else {
                    echo '<div class="carousel-item active">';
                    echo '<img src="images/default.jpg" class="d-block w-100" alt="No pets available" style="height: 300px; object-fit: cover;">';
                    echo '<div class="carousel-caption d-none d-md-block">';
                    echo '<h5>No pets available</h5>';
                    echo '</div></div>';
                }
                ?>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#petCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#petCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
