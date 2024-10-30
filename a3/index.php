<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('header.inc'); ?> <!-- Include header file -->
</head>
<body>

    <!-- Navbar -->
    <?php include('nav.inc'); ?> <!-- Include navigation file -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center">Welcome to Pets Victoria</h1>
        <p class="text-center">Find your perfect pet today!</p>

        <!-- Image Carousel -->
        <div id="petCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Image 1 -->
                <div class="carousel-item active">
                    <img src="images/cat1.jpeg" class="d-block w-100" alt="Cat 1" style="height: 300px; object-fit: cover;">
                </div>
                <!-- Image 2 -->
                <div class="carousel-item">
                    <img src="images/cat2.jpeg" class="d-block w-100" alt="Cat 2" style="height: 300px; object-fit: cover;">
                </div>
                <!-- Image 3 -->
                <div class="carousel-item">
                    <img src="images/cat3.jpeg" class="d-block w-100" alt="Cat 3" style="height: 300px; object-fit: cover;">
                </div>
                <!-- Image 4 -->
                <div class="carousel-item">
                    <img src="images/dog1.jpeg" class="d-block w-100" alt="Dog 1" style="height: 300px; object-fit: cover;">
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#petCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#petCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <?php include('footer.inc'); ?> <!-- Include footer file -->

    <!-- Bootstrap JS from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
