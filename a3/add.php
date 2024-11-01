<?php

session_start();


include('includes/db_connect.inc');

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables for form handling
$petname = $description = $type = $age = $location = $caption = $image = '';
$petnameErr = $descriptionErr = $typeErr = $ageErr = $locationErr = $captionErr = $imageErr = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $petname = trim($_POST['petname']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);
    $age = trim($_POST['age']);
    $location = trim($_POST['location']);
    $caption = trim($_POST['caption']);
    $username = $_SESSION['username']; // Retrieve logged-in username from session

    // Validate form inputs
    if (empty($petname)) $petnameErr = 'Pet name is required.';
    if (empty($description)) $descriptionErr = 'Description is required.';
    if (empty($type)) $typeErr = 'Pet type is required.';
    if (empty($age) || !is_numeric($age) || $age <= 0) $ageErr = 'Valid age is required.';
    if (empty($location)) $locationErr = 'Location is required.';
    if (empty($caption)) $captionErr = 'Caption is required.';

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]);
            } else {
                $imageErr = 'Error uploading the image.';
            }
        } else {
            $imageErr = 'Only JPG, JPEG, and PNG files are allowed.';
        }
    } else {
        $imageErr = 'Image is required.';
    }

    // If no errors, insert into database
    if (empty($petnameErr) && empty($descriptionErr) && empty($typeErr) && empty($ageErr) && empty($locationErr) && empty($captionErr) && empty($imageErr)) {
        $stmt = $conn->prepare("INSERT INTO pets (petname, description, type, age, location, caption, image, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisiss", $petname, $description, $type, $age, $location, $caption, $image, $username);

        if ($stmt->execute()) {
            echo "<script>alert('Pet added successfully!'); window.location.href='pets.php';</script>";
        } else {
            echo "<script>alert('Error adding pet. Please try again.');</script>";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.inc'); ?>
</head>
<body>

    <?php include('includes/nav.inc'); ?>

    <div class="container mt-5">
        <h2 class="text-center">Add a New Pet</h2>
        
        <form action="add.php" method="POST" enctype="multipart/form-data" class="add-form">
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
                <label for="image">Upload Image</label>
                <input type="file" name="image" id="image" class="form-control">
                <span class="text-danger"><?php echo $imageErr; ?></span>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Pet</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>


    <?php include('includes/footer.inc'); ?>

</body>
</html>
