<footer>
    <?php if (isset($_SESSION['loggedin']) && isset($_SESSION['username'])): ?>
        <p><a href="user.php" style="color: #fff; text-decoration: underline;"><?php echo htmlspecialchars($_SESSION['username']); ?></a></p>
        <p>&copy; <?php echo date("Y"); ?> Copyright S4007026. All Rights Reserved | Designed for Pets Victoria</p>
    <?php endif; ?>
</footer>

<script src="js/main.js"></script>
</body>
</html>
