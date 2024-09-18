<?php
// Include necessary files
include('includes/db_connect.inc'); // Database connection
include('includes/header.inc'); // Header content
include('includes/nav.inc'); // Navigation content

// Fetch all pet images from the database
try {
    $stmt = $pdo->query('SELECT petid, petname, image, caption FROM pets');
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching gallery: " . $e->getMessage();
    exit;
}
?>

<main>
    <div class="wrapper">
        <h2>Pet Gallery</h2>
        <div class="gallery">
            <?php if (count($pets) > 0): ?>
                <?php foreach ($pets as $pet): ?>
                    <div class="gallery-item">
                        <a href="details.php?id=<?= $pet['petid']; ?>">
                            <img src="images/<?= htmlspecialchars($pet['image']); ?>" alt="<?= htmlspecialchars($pet['caption']); ?>">
                        </a>
                        <p><?= htmlspecialchars($pet['caption']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No pets available in the gallery at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
// Include footer content
include('includes/footer.inc');
?>
