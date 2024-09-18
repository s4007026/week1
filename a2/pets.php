<?php
// Include necessary files
include('includes/db_connect.inc'); // Database connection
include('includes/header.inc'); // Header content
include('includes/nav.inc'); // Navigation content

// Fetch all pets from the database
try {
    $stmt = $pdo->query('SELECT petid, petname, type, age, location FROM pets');
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching pets: " . $e->getMessage();
    exit;
}
?>

<main>
    <div class="wrapper">
        <h2>Available Pets</h2>
        <?php if (count($pets) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Age (Months)</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pets as $pet): ?>
                        <tr>
                            <td><a href="details.php?id=<?= $pet['petid']; ?>"><?= htmlspecialchars($pet['petname']); ?></a></td>
                            <td><?= htmlspecialchars($pet['type']); ?></td>
                            <td><?= htmlspecialchars($pet['age']); ?></td>
                            <td><?= htmlspecialchars($pet['location']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pets available for adoption at the moment.</p>
        <?php endif; ?>
    </div>
</main>

<?php
// Include footer content
include('includes/footer.inc');
?>
