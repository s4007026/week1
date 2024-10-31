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
        <p class="text-center">
            Pets Victoria is a dedicated pet adoption organization based in Victoria, Australia, focused on providing a safe and loving environment for pets in need. With a compassionate approach, Pets Victoria works tirelessly to rescue, rehabilitate, and rehome dogs, cats, and other animals. Their mission is to connect these deserving pets with caring individuals and families, creating lifelong bonds.
        </p>

        <!-- Two-Column Layout -->
        <div class="row mt-4">
            <!-- Left Column for Image -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <img src="images/pets.jpeg" alt="Pets Banner" class="img-fluid" style="max-height: 400px; object-fit: cover; width: 100%;">
            </div>

            <!-- Right Column for Table -->
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Pet</th>
                                <th>Type</th>
                                <th>Age</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Check if there are any pets to display
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $petId = $row['petid'];
                                    $name = $row['petname'];
                                    $type = $row['type'];
                                    $age = $row['age'];
                                    $location = $row['location'];

                                    // Display each pet as a table row
                                    echo '
                                    <tr>
                                        <td><a href="details.php?id=' . $petId . '">' . htmlspecialchars($name) . '</a></td>
                                        <td>' . htmlspecialchars($type) . '</td>
                                        <td>' . htmlspecialchars($age) . ' months</td>
                                        <td>' . htmlspecialchars($location) . '</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">No pets available at the moment. Please check back later!</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

</body>
</html>
