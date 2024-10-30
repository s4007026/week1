<?php
// Start session management
session_start();

// Include database connection and header
include('includes/header.inc');

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables for form handling
$name = $description = $type = $age = $image = '';
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
    
    // Handle file upload
    if ($_FILES['image']['error'] == 0) {
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
    if (empty($nameErr) && empty($descriptionErr) && empty($typeErr) && empty($ageErr) && empty($imageErr)) {
        $stmt = $conn->prepare("INSERT INTO pets (name, description, type, age, image, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisi", $name, $description, $type, $age, $image, $_SESSION['user_id']);

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
    <?php include('includes/header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('includes/nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">Add a New Pet</h2>
        
        <form action="add.php" method="POST" enctype="multipart/form-data" class="add-form">
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

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
