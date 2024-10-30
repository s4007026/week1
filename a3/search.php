<?php
// Start session management
session_start();

// Include database connection
include('db_connect.inc');

// Initialize variables
$search_term = '';

// Handle search input
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
}

// Prepare SQL query for searching
$query = "SELECT id, name, type, age, image FROM pets WHERE name LIKE ? OR description LIKE ? OR type LIKE ?";
$stmt = $conn->prepare($query);

// Bind parameters and execute the query
$search_param = "%" . $search_term . "%";
$stmt->bind_param("sss", $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">Search Pets</h2>

        <!-- Search Form -->
        <form action="search.php" method="GET" class="d-flex justify-content-center mb-4">
            <div class="form-group me-2">
                <input type="text" name="search" class="form-control" placeholder="Enter keyword..." value="<?php echo htmlspecialchars($search_term); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Search Results -->
        <div class="mt-4">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Age</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?> years</td>
                                <td>
                                    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid" style="width: 100px; height: auto;">
                                </td>
                                <td>
                                    <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View Details</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No pets found matching your search criteria.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include('footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
