<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// Check if the 'id' parameter is set in the URL
if (!isset($_GET['id'])) {
    echo "<script>alert('No pet ID provided.'); window.location.href='pets.php';</script>";
    exit();
}

$pet_id = intval($_GET['id']); // Ensure ID is an integer

// Fetch pet details to confirm deletion
$stmt = $conn->prepare("SELECT image, name FROM pets WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $pet_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Check if the pet exists and belongs to the logged-in user
if ($result->num_rows == 0) {
    echo "<script>alert('Pet not found or you do not have permission to delete it.'); window.location.href='pets.php';</script>";
    exit();
}

$pet = $result->fetch_assoc();
$image = $pet['image'];
$name = $pet['name'];

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirm_delete'])) {
        // Delete the pet record
        $delete_stmt = $conn->prepare("DELETE FROM pets WHERE id = ? AND user_id = ?");
        $delete_stmt->bind_param("ii", $pet_id, $_SESSION['user_id']);
        
        if ($delete_stmt->execute()) {
            // Remove the pet image from the server
            if (file_exists("images/" . $image)) {
                unlink("images/" . $image);
            }

            echo "<script>alert('Pet deleted successfully.'); window.location.href='pets.php';</script>";
        } else {
            echo "<script>alert('Error deleting pet. Please try again.');</script>";
        }
        
        $delete_stmt->close();
    } else {
        // If deletion is canceled
        header('Location: pets.php');
        exit();
    }
}

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
        <h2 class="text-center text-danger">Confirm Deletion</h2>
        <div class="alert alert-warning text-center">
            <p>Are you sure you want to delete the pet named <strong><?php echo htmlspecialchars($name); ?></strong>?</p>
            <p>This action cannot be undone.</p>
        </div>

        <!-- Delete Confirmation Form -->
        <form action="delete.php?id=<?php echo $petid; ?>" method="POST" class="text-center">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
            <a href="pets.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
