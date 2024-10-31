<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Initialize variables for form handling
$username = $password = '';
$usernameErr = $passwordErr = $registerErr = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate form inputs
    if (empty($username)) {
        $usernameErr = 'Username is required.';
    }
    if (empty($password)) {
        $passwordErr = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $passwordErr = 'Password must be at least 6 characters long.';
    }

    // If no errors, register user
    if (empty($usernameErr) && empty($passwordErr)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
        } else {
            $registerErr = 'Error during registration. Please try again.';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <?php include('includes/header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="css/style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('includes/nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">Register</h2>

        <!-- Registration Form -->
        <form action="register.php" method="POST" class="register-form">
            <!-- Username Input -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
                <span class="text-danger"><?php echo $usernameErr; ?></span>
            </div>
            
            <!-- Password Input -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
                <span class="text-danger"><?php echo $passwordErr; ?></span>
            </div>

            <!-- Error Message for Registration -->
            <?php if (!empty($registerErr)): ?>
                <div class="alert alert-danger text-center"><?php echo $registerErr; ?></div>
            <?php endif; ?>

            <!-- Form Actions -->
            <div class="form-actions mt-4 text-center">
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="login.php" class="btn btn-link">Already have an account? Log in</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

</body>
</html>
