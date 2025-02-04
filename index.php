<?php 
require_once 'includes/header.php';
require_once 'includes/db.php';
?>

<!-- Hero Section -->
<div class="hero" style="background-image: url('assets/hero-bg.webp');">
    <div class="container">
        <div class="hero-content">
            <h1 class="display-4">Welcome to Riget Zoo Adventures</h1>
            <p class="lead">Experience wildlife up close in our safari-style zoo, stay in our luxury hotel, and discover educational adventures.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="account/register.php" class="btn btn-primary btn-lg me-3">Join Now</a>
            <?php endif; ?>
            <a href="bookings/tickets.php" class="btn btn-success btn-lg">Book Tickets</a>
        </div>
    </div>
</div>

<!-- Main Features -->
<div class="container my-5">
    <!-- Safari Zoo Section -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2>Safari-Style Wildlife Zoo</h2>
            <p>Get up close with nature in our immersive safari experience. See majestic animals in their natural habitats, including:</p>
            <ul class="list-unstyled">
                <li><i class="bi bi-check-circle-fill text-success"></i> Guided safari tours</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Interactive feeding sessions</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Wildlife photography spots</li>
            </ul>
            <a href="bookings/tickets.php" class="btn btn-outline-success">Book Safari Experience</a>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <img src="assets/safari.jpg" alt="Safari experience at RZA" class="card-img-top">
            </div>
        </div>
    </div>

    <!-- Hotel Section -->
    <div class="row mb-5 align-items-center flex-md-row-reverse">
        <div class="col-md-6">
            <h2>Luxury On-site Hotel</h2>
            <p>Extend your adventure with a stay in our comfortable hotel:</p>
            <ul class="list-unstyled">
                <li><i class="bi bi-check-circle-fill text-success"></i> Standard & VIP rooms</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Wildlife viewing balconies</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Restaurant & room service</li>
            </ul>
            <a href="bookings/hotel.php" class="btn btn-outline-success">Check Availability</a>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <img src="assets/hotel.jpg" alt="RZA Hotel accommodations" class="card-img-top">
            </div>
        </div>
    </div>

    <!-- Educational Section -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2>Educational Adventures</h2>
            <p>Perfect for school visits and curious minds:</p>
            <ul class="list-unstyled">
                <li><i class="bi bi-check-circle-fill text-success"></i> Interactive learning materials</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Conservation workshops</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Downloadable resources</li>
            </ul>
            <a href="education/resources.php" class="btn btn-outline-success">Explore Resources</a>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <img src="assets/education.jpg" alt="Educational activities at RZA" class="card-img-top">
            </div>
        </div>
    </div>

    <!-- Loyalty Program -->
    <div class="row mb-5 align-items-center flex-md-row-reverse">
        <div class="col-md-6">
            <h2>Loyalty Rewards</h2>
            <p>Join our loyalty program and earn points with every visit:</p>
            <ul class="list-unstyled">
                <li><i class="bi bi-check-circle-fill text-success"></i> Earn 10 points per booking</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Track points in your profile</li>
                <li><i class="bi bi-check-circle-fill text-success"></i> Member-exclusive offers</li>
            </ul>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="account/register.php" class="btn btn-outline-success">Join Now</a>
            <?php else: ?>
                <a href="account/profile.php" class="btn btn-outline-success">View My Points</a>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <img src="assets/loyalty.webp" alt="RZA Loyalty Program benefits" class="card-img-top">
            </div>
        </div>
    </div>
</div>

<!-- Accessibility Features -->
<div class="container mb-5">
    <div class="card bg-light">
        <div class="card-body">
            <h3>Accessibility at RZA</h3>
            <div class="row">
                <div class="col-md-6">
                    <h4>Physical Facilities</h4>
                    <ul>
                        <li>Wheelchair accessible paths</li>
                        <li>Disabled parking spaces</li>
                        <li>Accessible restrooms</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Digital Access</h4>
                    <ul>
                        <li>Screen reader compatible</li>
                        <li>High contrast mode available</li>
                        <li>Keyboard navigation support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 