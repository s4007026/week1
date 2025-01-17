<?php

session_start();

include('includes/db_connect.inc');

$search_term = '';

// Handle search input
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
}

// Prepare SQL query for searching
$query = "SELECT petid, petname, type, age, image FROM pets WHERE petname LIKE ? OR description LIKE ? OR type LIKE ?";
$stmt = $conn->prepare($query);

// Parameters n execute query
$search_param = "%" . $search_term . "%";
$stmt->bind_param("sss", $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.inc'); ?>
</head>
<body>

    <?php include('includes/nav.inc'); ?> 

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
                                <td><?php echo htmlspecialchars($row['petname']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?> years</td>
                                <td>
                                    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['petname']); ?>" class="img-fluid" style="width: 100px; height: auto;">
                                </td>
                                <td>
                                    <a href="details.php?id=<?php echo $row['petid']; ?>" class="btn btn-info btn-sm">View Details</a>
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

    <?php include('includes/footer.inc'); ?>

</body>
</html>
