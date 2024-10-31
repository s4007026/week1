<footer>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <p> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    <?php endif; ?>
    <p>&copy; <?php echo date("Y"); ?> Copyright S4007026. All Rights Reserved | Designed for Pets Victoria</p>
</footer>

<script src="js/main.js"></script>
</body>
</html>