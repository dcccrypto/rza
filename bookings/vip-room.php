<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

$ROOM_PRICE = 250.00;  // £250 per night for VIP

require_once '../includes/header.php';
?>

<div class="container py-5 animate-fade-in">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg">
                <div class="position-relative">
                    <img src="/rza/assets/images/vip-room.webp" class="card-img-top" alt="VIP Suite" style="height: 400px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-success">Premium</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="card-title text-success mb-0">VIP Suite</h1>
                        <div class="price-display">
                            <span class="h2 text-success">£<?= number_format($ROOM_PRICE, 2) ?></span>
                            <small class="text-muted">/night</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="text-success mb-3">Suite Description</h4>
                            <p class="lead">Indulge in luxury with our spacious VIP suites, offering premium amenities and exceptional comfort for the discerning traveler.</p>
                            <p>Our VIP Suite represents the pinnacle of luxury accommodation, featuring a separate living area, premium furnishings, and panoramic views. Experience unparalleled comfort and sophisticated elegance in this meticulously designed space.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="text-success">Quick Facts</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-ruler-combined me-2"></i> 60m² Suite Size</li>
                                        <li><i class="fas fa-mountain me-2"></i> Premium View</li>
                                        <li><i class="fas fa-users me-2"></i> Max 3 Guests</li>
                                        <li><i class="fas fa-smoking-ban me-2"></i> Non-Smoking</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-success mb-3">Premium Amenities</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-wifi me-2 text-success"></i>
                                        <span>Premium High-Speed WiFi</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tv me-2 text-success"></i>
                                        <span>55" 4K Smart TV</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bed me-2 text-success"></i>
                                        <span>King-Size Bed</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bath me-2 text-success"></i>
                                        <span>Luxury Bathroom with Jacuzzi</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-concierge-bell me-2 text-success"></i>
                                        <span>24/7 Room Service</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-glass-martini-alt me-2 text-success"></i>
                                        <span>Mini Bar</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-couch me-2 text-success"></i>
                                        <span>Separate Living Area</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-utensils me-2 text-success"></i>
                                        <span>Dining Area</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="/rza/bookings/hotel.php" class="btn btn-success btn-lg">
                            Book This Suite
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-img-top {
    transition: transform 0.3s ease-in-out;
}

.price-display {
    color: var(--bs-success);
    font-weight: bold;
}
</style>

<?php require_once '../includes/footer.php'; ?> 