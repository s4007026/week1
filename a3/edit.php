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

// Check if 'id' parameter is set in the URL
if (!isset($_GET['id'])) {
    echo "<script>alert('No pet ID provided.'); window.location.href='pets.php';</script>";
    exit();
}

$pet_id = intval($_GET['id']); // Ensure ID is an integer

// Fetch pet details from the database to pre-fill the form
$stmt = $conn->prepare("SELECT petid, petname, description, type, age, image, userID FROM pets WHERE id = ? AND userID = ?");
$stmt->bind_param("ii", $pet_id, $_SESSION['userID']);
$stmt->execute();
$result = $stmt->get_result();

// Check if the pet exists and belongs to the logged-in user
if ($result->num_rows == 0) {
    echo "<script>alert('Pet not found or you do not have permission to edit it.'); window.location.href='pets.php';</script>";
    exit();
}

$pet = $result->fetch_assoc();
$stmt->close();

// Initialize variables for form handling
$name = $pet['name'];
$description = $pet['description'];
$type = $pet['type'];
$age = $pet['age'];
$image = $pet['image'];
$nameErr = $descriptionErr = $typeErr = $ageErr = $imageErr = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);
    $age = trim($_POST['age']);

    // Validate form inputs
    if (empty($name)) $nameErr = 'Pet name is required.';
    if (empty($description)) $descriptionErr = 'Description is required.';
    if (empty($type)) $typeErr = 'Pet type is required.';
    if (empty($age) || !is_numeric($age) || $age <= 0) $ageErr = 'Valid age is required.';

    // Handle file upload if a new image is uploaded
    if ($_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Delete the old image from the server
                if (file_exists("images/" . $image)) {
                    unlink("images/" . $image);
                }
                $image = basename($_FILES["image"]["name"]);
            } else {
                $imageErr = 'Error uploading the new image.';
            }
        } else {
            $imageErr = 'Only JPG, JPEG, and PNG files are allowed.';
        }
    }

    // If no errors, update the pet details in the database
    if (empty($nameErr) && empty($descriptionErr) && empty($typeErr) && empty($ageErr) && empty($imageErr)) {
        $stmt = $conn->prepare("UPDATE pets SET name = ?, description = ?, type = ?, age = ?, image = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sssisii", $name, $description, $type, $age, $image, $pet_id, $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo "<script>alert('Pet updated successfully!'); window.location.href='details.php?id=$pet_id';</script>";
        } else {
            echo "<script>alert('Error updating pet. Please try again.');</script>";
        }

        $stmt->close();
    }
}
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
        <h2 class="text-center">Edit Pet: <?php echo htmlspecialchars($pet['name']); ?></h2>

        <form action="edit.php?id=<?php echo $pet_id; ?>" method="POST" enctype="multipart/form-data" class="edit-form">
            <!-- Pet Name -->
            <div class="form-group">
                <label for="name">Pet Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
                <span class="text-danger"><?php echo $nameErr; ?></span>
            </div>
            
            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"><?php echo htmlspecialchars($description); ?></textarea>
                <span class="text-danger"><?php echo $descriptionErr; ?></span>
            </div>
            
            <!-- Pet Type -->
            <div class="form-group">
                <label for="type">Pet Type</label>
                <input type="text" name="type" id="type" class="form-control" value="<?php echo htmlspecialchars($type); ?>">
                <span class="text-danger"><?php echo $typeErr; ?></span>
            </div>
            
            <!-- Age -->
            <div class="form-group">
                <label for="age">Age (in years)</label>
                <input type="number" name="age" id="age" class="form-control" value="<?php echo htmlspecialchars($age); ?>">
                <span class="text-danger"><?php echo $ageErr; ?></span>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Upload New Image (optional)</label>
                <input type="file" name="image" id="image" class="form-control">
                <span class="text-danger"><?php echo $imageErr; ?></span>
            </div>

            <!-- Current Image Display -->
            <div class="form-group mt-3">
                <label>Current Image:</label>
                <img src="images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="img-fluid rounded mt-2">
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary">Update Pet</button>
                <a href="details.php?id=<?php echo $pet_id; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

</body>
</html>
