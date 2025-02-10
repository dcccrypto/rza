<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

$ROOM_PRICE = 150.00;  // £150 per night for Standard

require_once '../includes/header.php';
?>

<div class="container py-5 animate-fade-in">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg">
                <div class="position-relative">
                    <img src="/rza/assets/images/standard-room.jpg" class="card-img-top" alt="Standard Room" style="height: 400px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-success">Most Popular</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="card-title text-success mb-0">Standard Room</h1>
                        <div class="price-display">
                            <span class="h2 text-success">£<?= number_format($ROOM_PRICE, 2) ?></span>
                            <small class="text-muted">/night</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="text-success mb-3">Room Description</h4>
                            <p class="lead">Experience comfort and style in our well-appointed standard rooms, perfect for both business and leisure travelers.</p>
                            <p>Our Standard Room offers the perfect blend of comfort and functionality, featuring modern amenities and a welcoming atmosphere. Each room is thoughtfully designed to ensure a pleasant and relaxing stay.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="text-success">Quick Facts</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-ruler-combined me-2"></i> 30m² Room Size</li>
                                        <li><i class="fas fa-mountain me-2"></i> City View</li>
                                        <li><i class="fas fa-users me-2"></i> Max 2 Guests</li>
                                        <li><i class="fas fa-smoking-ban me-2"></i> Non-Smoking</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-success mb-3">Room Amenities</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-wifi me-2 text-success"></i>
                                        <span>Free High-Speed WiFi</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tv me-2 text-success"></i>
                                        <span>42" Smart TV</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bed me-2 text-success"></i>
                                        <span>Queen-Size Bed</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bath me-2 text-success"></i>
                                        <span>En-suite Bathroom</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-temperature-low me-2 text-success"></i>
                                        <span>Climate Control</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-coffee me-2 text-success"></i>
                                        <span>Coffee Machine</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="/rza/bookings/hotel.php" class="btn btn-success btn-lg">
                            Book This Room
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