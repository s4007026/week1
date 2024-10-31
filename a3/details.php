<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Fetch pet details from the database
$stmt = $conn->prepare("SELECT petid, petname, description, type, age, image, username FROM pets WHERE petid = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the pet exists
if ($result->num_rows == 0) {
    echo "<script>alert('Pet not found.'); window.location.href='pets.php';</script>";
    exit();
}

$pet = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="css/style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('includes/nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center"><?php echo htmlspecialchars($pet['petname']); ?></h2>

        <div class="details-container">
            <!-- Pet Image -->
            <div class="pet-image">
                <img src="images/<?php echo htmlspecialchars($pet['image']); ?>" alt="<?php echo htmlspecialchars($pet['petname']); ?>" class="img-fluid rounded">
            </div>

            <!-- Pet Info -->
            <div class="pet-info">
                <p><strong>Description:</strong> <?php echo htmlspecialchars($pet['description']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($pet['type']); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</p>
            </div>

            <!-- Edit and Delete Options (Visible only to the owner) -->
            <?php if (isset($_SESSION['userID']) && $_SESSION['userID'] == $pet['userID']): ?>
                <div class="text-center mt-4">
                    <a href="edit.php?petid=<?php echo $pet['petid']; ?>" class="btn btn-primary">Edit Pet</a>
                    <a href="delete.php?petid=<?php echo $pet['petid']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this pet?')">Delete Pet</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
