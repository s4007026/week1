<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Initialize variables
$filter_type = '';
$search = '';

// Handle filter and search input
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $filter_type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
}

// Fetch distinct pet types for the dropdown
$type_stmt = $conn->prepare("SELECT DISTINCT type FROM pets");
$type_stmt->execute();
$type_result = $type_stmt->get_result();

// Prepare SQL query for filtering and searching
$query = "SELECT petid, petname, type, image FROM pets WHERE 1=1";
if ($filter_type) {
    $query .= " AND type = ?";
}
if ($search) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
}

$stmt = $conn->prepare($query);

// Bind parameters based on filter and search
if ($filter_type && $search) {
    $search_param = "%" . $search . "%";
    $stmt->bind_param("sss", $filter_type, $search_param, $search_param);
} elseif ($filter_type) {
    $stmt->bind_param("s", $filter_type);
} elseif ($search) {
    $search_param = "%" . $search . "%";
    $stmt->bind_param("ss", $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
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
        <h2 class="text-center">Pet Gallery</h2>

        <!-- Filter and Search Form -->
        <form action="gallery.php" method="GET" class="d-flex justify-content-center mb-4">
            <!-- Pet Type Filter -->
            <div class="form-group me-2">
                <select name="type" class="form-control">
                    <option value="">All Types</option>
                    <?php while ($row = $type_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['type']); ?>" <?php echo $filter_type == $row['type'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['type']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Search Input -->
            <div class="form-group me-2">
                <input type="text" name="search" class="form-control" placeholder="Search by name or description" value="<?php echo htmlspecialchars($search); ?>">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Gallery Grid -->
        <div class="gallery">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="pet-card">
                        <div class="img-container">
                            <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['petname']); ?>">
                            <div class="overlay">
                                <span><?php echo htmlspecialchars($row['petname']); ?></span>
                            </div>
                        </div>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?></p>
                        <a href="details.php?id=<?php echo $row['petid']; ?>" class="btn btn-info">View Details</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No pets found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
