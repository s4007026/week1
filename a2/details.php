<!DOCTYPE html>
<html lang="en"> <!-- Added lang attribute to declare the document's language -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Details</title> <!-- Ensure the title element is included in the head -->
    <link rel="stylesheet" href="styles.css"> <!-- Assuming you have a stylesheet -->
</head>
<body>
    <?php
    include 'includes/header.inc';  // Header section including DOCTYPE, head, and start of body
    include 'includes/nav.inc';     // Navigation section
    include 'includes/db_connect.inc';  // Database connection

    if (isset($_GET['id'])) {
        $petid = $_GET['id'];

        // Fetch pet details based on the petid
        $stmt = $conn->prepare("SELECT petname, description, caption, age, type, location, image FROM pets WHERE petid = ?");
        $stmt->bind_param("i", $petid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $pet = $result->fetch_assoc();
            $imagePath = "images/" . htmlspecialchars($pet['image']);
    ?>
            <main>
                <div class="details-container">
                    <div class="pet-image">
                        <!-- Correctly using PHP for the img src and alt -->
                        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($pet['caption']); ?>">
                    </div>
                    <div class="pet-info">
                        <div class="icon-group">
                            <div class="icon">
                                <img src="images/age.png" alt="Age Icon">
                                <p><?php echo htmlspecialchars($pet['age']); ?> months</p>
                            </div>
                            <div class="icon">
                                <img src="images/ipet.png" alt="Type Icon">
                                <p><?php echo htmlspecialchars($pet['type']); ?></p>
                            </div>
                            <div class="icon">
                                <img src="images/location.png" alt="Location Icon">
                                <p><?php echo htmlspecialchars($pet['location']); ?></p>
                            </div>
                        </div>
                        <h2><?php echo !empty($pet['petname']) ? htmlspecialchars($pet['petname']) : 'Unnamed Pet'; ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($pet['description'])); ?></p>
                    </div>
                </div>
                <div class="back-to-gallery">
                    <a href="gallery.php">← Back to Gallery</a>
                </div>
            </main>
    <?php
        } else {
            echo "<p>Pet not found.</p>"; // Correct PHP tag placement
        }

        $stmt->close();
    } else {
        echo "<p>No pet ID provided.</p>"; // Correct PHP tag placement
    }

    include 'includes/footer.inc';  // Footer section with closing body and HTML tags
    ?>
</body>
</html>
