<?php
session_start();

include('includes/db_connect.inc');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Retrieve the logged-in username
$username = $_SESSION['username'];

// Fetch user details based on the username in the session
$user_stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

// Check if the user exists
if ($user_result->num_rows == 0) {
    echo "<script>alert('User not found.'); window.location.href='index.php';</script>";
    exit();
}

$user = $user_result->fetch_assoc();
$user_name = $user['username'];

// Fetch pets uploaded by this user
$pets_stmt = $conn->prepare("SELECT petid, petname, type, age, image FROM pets WHERE username = ?");
$pets_stmt->bind_param("s", $username);
$pets_stmt->execute();
$pets_result = $pets_stmt->get_result();
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
                                <td><?php echo htmlspecialchars($row['petname']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?> years</td>
                                <td>
                                    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['petname']); ?>" class="img-fluid" style="width: 100px; height: auto;">
                                </td>
                                <td>
                                    <a href="details.php?petid=<?php echo $row['petid']; ?>" class="btn btn-info btn-sm">View Details</a>
                                    <?php if ($username == $_SESSION['username']): ?>
                                        <a href="edit.php?petid=<?php echo $row['petid']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="delete.php?petid=<?php echo $row['petid']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">You have not uploaded any pets.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include('includes/footer.inc'); ?>

</body>
</html>
