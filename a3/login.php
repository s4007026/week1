<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Initialize variables for form handling
$username = $password = '';
$usernameErr = $passwordErr = $loginErr = '';

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
    }

    // If no errors, check user credentials
    if (empty($usernameErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT userID, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verify user credentials
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Check if the password matches
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['userID'] = $username['userID'];
                $_SESSION['username'] = $username['username'];

                // Redirect to the homepage
                header('Location: index.php');
                exit();
            } else {
                $loginErr = 'Incorrect username or password.';
            }
        } else {
            $loginErr = 'Incorrect username or password.';
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
    <title>Login</title>
    <?php include('includes/header.inc'); ?> <!-- Include header -->
    <link rel="stylesheet" href="css/style.css"> <!-- Custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <?php include('includes/nav.inc'); ?> <!-- Include navigation -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="login-form">
            <!-- Username Input -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="userID" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
                <span class="text-danger"><?php echo $usernameErr; ?></span>
            </div>
            
            <!-- Password Input -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
                <span class="text-danger"><?php echo $passwordErr; ?></span>
            </div>

            <!-- Error Message for Incorrect Credentials -->
            <?php if (!empty($loginErr)): ?>
                <div class="alert alert-danger text-center"><?php echo $loginErr; ?></div>
            <?php endif; ?>

            <!-- Form Actions -->
            <div class="form-actions mt-4 text-center">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="register.php" class="btn btn-link">Create an Account</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
