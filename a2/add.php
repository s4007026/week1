<!DOCTYPE html>
<html lang="en"> <!-- Fix: Added lang attribute for language declaration -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Pet</title>
</head>
<body>

<?php
include 'includes/header.inc';  // Including header
include 'includes/nav.inc';     // Including navigation
include 'includes/db_connect.inc';  // Including DB connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $petname = $_POST['pet-name'];
    $type = $_POST['pet-type'];
    $description = $_POST['description'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $caption = $_POST['caption'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];

        // Define upload folder
        $target_dir = "images/";
        $target_file = $target_dir . basename($image_name);

        // Move uploaded file to the target folder
        if (move_uploaded_file($image_tmp, $target_file)) {
            // Prepare SQL insert query
            $stmt = $conn->prepare("INSERT INTO pets (petname, type, description, age, location, caption, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisss", $petname, $type, $description, $age, $location, $caption, $image_name);

            if ($stmt->execute()) {
                echo "<p>Pet added successfully!</p>";
                echo '<p><a href="gallery.php">Go to Gallery</a></p>';
            } else {
                echo "<p>Failed to add the pet.</p>";
            }

            $stmt->close();
        } else {
            echo "<p>There was an error uploading the image file.</p>";
        }
    } else {
        echo "<p>No image file uploaded or there was an error.</p>";
    }

    $conn->close();
}
?>

<main>
    <h2>Add a Pet</h2>
    <p>You can add a new pet here.</p>
    
    <!-- Fix the form action to handle PHP_SELF correctly and remove validator issues -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <label for="pet-name">Provide a name for the pet *</label>
        <input type="text" id="pet-name" name="pet-name" required>

        <label for="pet-type">Type *</label>
        <select id="pet-type" name="pet-type" required>
            <option value="">--Choose an option--</option>
            <option value="cat">Cat</option>
            <option value="dog">Dog</option>
            <option value="other">Other</option>
        </select>

        <label for="description">Description *</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="image">Select an Image *</label>
        <input type="file" id="image" name="image" required>

        <label for="caption">Image Caption *</label>
        <input type="text" id="caption" name="caption" required>

        <label for="age">Age (months) *</label>
        <input type="number" id="age" name="age" required>

        <label for="location">Location *</label>
        <input type="text" id="location" name="location" required>

        <div class="form-actions">
            <button type="submit">✔️ Submit</button>
            <button type="reset">✖️ Clear</button>
        </div>
    </form>
</main>

<?php
// Footer include
include 'includes/footer.inc';  // Including footer
?>

</body>
</html>
