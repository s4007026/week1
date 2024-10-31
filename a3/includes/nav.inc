<header>
    <nav>
        <!-- Logo -->
        <img src="images/favicon.png" alt="Pets Victoria Logo" class="logo">

        <!-- Navigation Links -->
        <div>
            <a href="index.php" class="active">Home</a>
            <a href="pets.php">Pets</a>
            <a href="gallery.php">Gallery</a>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <!-- Show "Add More" instead of "Register" and "Logout" instead of "Login" -->
                <a href="add.php">Add More</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- Show "Register" and "Login" if not logged in -->
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>

        <!-- Search Form -->
        <form action="search.php" method="GET">
            <input type="text" name="search" placeholder="Search...">
            <button type="submit">🔍</button>
        </form>
    </nav>
</header>
