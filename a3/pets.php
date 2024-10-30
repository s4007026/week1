<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Fetch all pets from the database
$query = "SELECT petid, petname, type, age, location, image FROM pets ORDER BY petid DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets</title>
    <?php include('includes/header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="css/style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('includes/nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">Discover Pets Victoria</h2>
        <p class="text-center">Find your perfect pet from our available listings below!</p>

        <!-- Grid Layout for Pet Cards -->
        <div class="row">
            <?php
            // Check if there are any pets to display
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $petId = $row['petid'];
                    $name = $row['petname'];
                    $type = $row['type'];
                    $age = $row['age'];
                    $location = $row['location'];
                    $imagePath = $row['image'];

                    // Display each pet as a card
                    echo '
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="' . htmlspecialchars($imagePath) . '" class="card-img-top" alt="' . htmlspecialchars($name) . '" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($name) . '</h5>
                                <p class="card-text">
                                    Type: ' . htmlspecialchars($type) . '<br>
                                    Age: ' . htmlspecialchars($age) . ' months<br>
                                    Location: ' . htmlspecialchars($location) . '
                                </p>
                                <a href="details.php?id=' . $petId . '" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No pets available at the moment. Please check back later!</p>';
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
