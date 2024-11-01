<?php

session_start();

include('includes/db_connect.inc');

// Check if the 'id' parameter is set
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $petId = $_GET['id'];

    $sql = "SELECT * FROM pets WHERE petid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $petId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the pet was found
    if ($result && $result->num_rows > 0) {
        $pet = $result->fetch_assoc(); // Store the result in $pet for consistent naming
    } else {
        echo "<p>Pet not found.</p>";
        exit();
    }
} else {
    echo "<p>Invalid pet ID.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.inc'); ?>
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
                <p><strong>Location:</strong> <?php echo htmlspecialchars($pet['location']); ?></p>
                <p><strong>Caption:</strong> <?php echo htmlspecialchars($pet['caption']); ?></p>
            </div>

            <!-- Edit and Delete Options (Visible only to the owner) -->
            <?php if (isset($_SESSION['username']) && $_SESSION['username'] == $pet['username']): ?>
                <div class="text-center mt-4">
                    <a href="edit.php?petid=<?php echo $pet['petid']; ?>" class="btn btn-primary">Edit Pet</a>
                    <a href="delete.php?petid=<?php echo $pet['petid']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this pet?')">Delete Pet</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('includes/footer.inc'); ?>

</body>
</html>
