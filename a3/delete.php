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

// Check if the 'petid' parameter is set in the URL
if (!isset($_GET['petid']) || !is_numeric($_GET['petid'])) {
    echo "<script>alert('No pet ID provided.'); window.location.href='pets.php';</script>";
    exit();
}

$pet_id = intval($_GET['petid']); // Ensure ID is an integer

// Fetch pet details to confirm deletion, ensuring the pet belongs to the logged-in user
$stmt = $conn->prepare("SELECT image, petname FROM pets WHERE petid = ? AND username = ?");
$stmt->bind_param("is", $pet_id, $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

// Check if the pet exists and belongs to the logged-in user
if ($result->num_rows == 0) {
    echo "<script>alert('Pet not found or you do not have permission to delete it.'); window.location.href='pets.php';</script>";
    exit();
}

$pet = $result->fetch_assoc();
$image = $pet['image'];
$petname = $pet['petname'];

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirm_delete'])) {
        // Delete the pet record
        $delete_stmt = $conn->prepare("DELETE FROM pets WHERE petid = ? AND username = ?");
        $delete_stmt->bind_param("is", $pet_id, $_SESSION['username']);
        
        if ($delete_stmt->execute()) {
            // Remove the pet image from the server
            if (!empty($image) && file_exists("images/" . $image)) {
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
            <p>Are you sure you want to delete the pet named <strong><?php echo htmlspecialchars($petname); ?></strong>?</p>
            <p>This action cannot be undone.</p>
        </div>

        <!-- Delete Confirmation Form -->
        <form action="delete.php?petid=<?php echo $pet_id; ?>" method="POST" class="text-center">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
            <a href="pets.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <?php include('includes/footer.inc'); ?>

</body>
</html>
