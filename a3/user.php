<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Check if 'id' parameter is set in the URL (user ID)
if (!isset($_GET['id'])) {
    echo "<script>alert('No user ID provided.'); window.location.href='index.php';</script>";
    exit();
}

$user_id = intval($_GET['id']); // Ensure ID is an integer

// Fetch user details
$user_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

// Check if the user exists
if ($user_result->num_rows == 0) {
    echo "<script>alert('User not found.'); window.location.href='index.php';</script>";
    exit();
}

$user = $user_result->fetch_assoc();
$user_name = $user['name'];

// Fetch pets uploaded by the user
$pets_stmt = $conn->prepare("SELECT id, petname, type, age, image FROM pets WHERE user_id = ?");
$pets_stmt->bind_param("i", $user_id);
$pets_stmt->execute();
$pets_result = $pets_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="css/style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('includes/nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">Pets Uploaded by <?php echo htmlspecialchars($user_name); ?></h2>

        <!-- User's Pets Table -->
        <div class="mt-4">
            <?php if ($pets_result->num_rows > 0): ?>
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
                        <?php while ($row = $pets_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?> years</td>
                                <td>
                                    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid" style="width: 100px; height: auto;">
                                </td>
                                <td>
                                    <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View Details</a>
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id): ?>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">This user has not uploaded any pets.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

</body>
</html>
