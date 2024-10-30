<?php
// Start session management
session_start();

// Include database connection
include('includes/db_connect.inc');

// Initialize variables for form handling
$name = $email = $password = $confirm_password = '';
$nameErr = $emailErr = $passwordErr = $confirm_passwordErr = $registerErr = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate form inputs
    if (empty($name)) {
        $nameErr = 'Name is required.';
    }
    if (empty($email)) {
        $emailErr = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = 'Invalid email format.';
    }
    if (empty($password)) {
        $passwordErr = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $passwordErr = 'Password must be at least 6 characters long.';
    }
    if (empty($confirm_password)) {
        $confirm_passwordErr = 'Confirm password is required.';
    } elseif ($password !== $confirm_password) {
        $confirm_passwordErr = 'Passwords do not match.';
    }

    // If no errors, check if email already exists and register user
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirm_passwordErr)) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $registerErr = 'Email is already registered.';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
            } else {
                $registerErr = 'Error during registration. Please try again.';
            }
        }

        $stmt->close();
    }
}
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
        <h2 class="text-center">Register</h2>

        <!-- Registration Form -->
        <form action="register.php" method="POST" class="register-form">
            <!-- Name Input -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
                <span class="text-danger"><?php echo $nameErr; ?></span>
            </div>

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

            <!-- Confirm Password Input -->
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                <span class="text-danger"><?php echo $confirm_passwordErr; ?></span>
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

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
