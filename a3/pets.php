<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Fetch all pets from the database
$query = "SELECT id, name, type, age, image, user_id FROM pets";
$result = $conn->query($query);
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
        <h2 class="text-center">All Pets</h2>

        <!-- Pets Table -->
        <table class="table table-bordered mt-4">
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
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?> years</td>
                            <td>
                                <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid" style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No pets found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
