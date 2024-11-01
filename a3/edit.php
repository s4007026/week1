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

// Check if 'petid' parameter is set in the URL
if (!isset($_GET['petid']) || !is_numeric($_GET['petid'])) {
    echo "<script>alert('No pet ID provided.'); window.location.href='pets.php';</script>";
    exit();
}

$pet_id = intval($_GET['petid']); // Ensure ID is an integer

// Fetch pet details from the database to pre-fill the form
$stmt = $conn->prepare("SELECT petid, petname, description, type, age, location, caption, image, username FROM pets WHERE petid = ? AND username = ?");
$stmt->bind_param("is", $pet_id, $_SESSION['username']);
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
$petname = $pet['petname'];
$description = $pet['description'];
$type = $pet['type'];
$age = $pet['age'];
$location = $pet['location'];
$caption = $pet['caption'];
$image = $pet['image'];
$petnameErr = $descriptionErr = $typeErr = $ageErr = $locationErr = $captionErr = $imageErr = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $petname = trim($_POST['petname']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);
    $age = trim($_POST['age']);
    $location = trim($_POST['location']);
    $caption = trim($_POST['caption']);

    // Validate form inputs
    if (empty($petname)) $petnameErr = 'Pet name is required.';
    if (empty($description)) $descriptionErr = 'Description is required.';
    if (empty($type)) $typeErr = 'Pet type is required.';
    if (empty($age) || !is_numeric($age) || $age <= 0) $ageErr = 'Valid age is required.';
    if (empty($location)) $locationErr = 'Location is required.';
    if (empty($caption)) $captionErr = 'Caption is required.';

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
if (empty($petnameErr) && empty($descriptionErr) && empty($typeErr) && empty($ageErr) && empty($locationErr) && empty($captionErr) && empty($imageErr)) {
    $stmt = $conn->prepare("UPDATE pets SET petname = ?, description = ?, type = ?, age = ?, location = ?, caption = ?, image = ? WHERE petid = ? AND username = ?");
    $stmt->bind_param("sssisssis", $petname, $description, $type, $age, $location, $caption, $image, $pet_id, $_SESSION['username']);

    if ($stmt->execute()) {
        echo "<script>alert('Pet updated successfully!'); window.location.href='index.php';</script>";
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
        <h2 class="text-center">Edit Pet: <?php echo htmlspecialchars($petname); ?></h2>

        <form action="edit.php?petid=<?php echo $pet_id; ?>" method="POST" enctype="multipart/form-data" class="edit-form">
            <!-- Pet Name -->
            <div class="form-group">
                <label for="petname">Pet Name</label>
                <input type="text" name="petname" id="petname" class="form-control" value="<?php echo htmlspecialchars($petname); ?>">
                <span class="text-danger"><?php echo $petnameErr; ?></span>
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

            <!-- Location -->
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>">
                <span class="text-danger"><?php echo $locationErr; ?></span>
            </div>

            <!-- Caption -->
            <div class="form-group">
                <label for="caption">Caption</label>
                <input type="text" name="caption" id="caption" class="form-control" value="<?php echo htmlspecialchars($caption); ?>">
                <span class="text-danger"><?php echo $captionErr; ?></span>
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
                <img src="images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($petname); ?>" class="img-fluid rounded mt-2">
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary">Update Pet</button>
                <a href="details.php?petid=<?php echo $pet_id; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

</body>
</html>
