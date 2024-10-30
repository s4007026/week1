<?php
// Start session management
session_start();

// Include database connection
include('db_connect.inc');

// Initialize variables for form handling
$email = $password = '';
$emailErr = $passwordErr = $loginErr = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate form inputs
    if (empty($email)) {
        $emailErr = 'Email is required.';
    }
    if (empty($password)) {
        $passwordErr = 'Password is required.';
    }

    // If no errors, check user credentials
    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verify user credentials
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Check if the password matches
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Redirect to the homepage
                header('Location: index.php');
                exit();
            } else {
                $loginErr = 'Incorrect email or password.';
            }
        } else {
            $loginErr = 'Incorrect email or password.';
        }

        $stmt->close();
    }
}
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
        <h2 class="text-center">Login</h2>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="login-form">
            <!-- Email Input -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <span class="text-danger"><?php echo $emailErr; ?></span>
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
    <?php include('footer.inc'); ?> <!-- Include footer -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
