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
        ?>
        <main>
            <h1><?php echo htmlspecialchars($pet['petname']); ?>'s Details</h1>
            <div class="pet-details">
                <img src="images/<?php echo htmlspecialchars($pet['image']); ?>" alt="<?php echo htmlspecialchars($pet['caption']); ?>" class="pet-image">
                <h2>Name: <?php echo htmlspecialchars($pet['petname']); ?></h2>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($pet['type']); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> months</p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($pet['description'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($pet['location']); ?></p>
            </div>
            <div class="back-to-gallery">
                <a href="gallery.php">← Back to Gallery</a>
            </div>
        </main>
        <?php
    } else {
        echo "<p>Pet not found.</p>";
    }

    $stmt->close();
} else {
    echo "<p>No pet ID provided.</p>";
}

include 'includes/footer.inc';  // Footer section with closing body and HTML tags
?>
