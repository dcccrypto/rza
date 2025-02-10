<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

// Define room prices
$ROOM_PRICES = [
    'Standard' => 150.00,  // £150 per night for Standard
    'VIP' => 250.00       // £250 per night for VIP
];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = $_POST['booking_date'] ?? '';
    $room_type = $_POST['room_type'] ?? '';
    $nights = (int)($_POST['nights'] ?? 0);
    
    // Validate inputs
    if (strtotime($booking_date) < strtotime('today')) {
        $error = 'Please select a future date.';
    } elseif (!array_key_exists($room_type, $ROOM_PRICES)) {
        $error = 'Please select a valid room type.';
    } elseif ($nights < 1 || $nights > 14) {
        $error = 'Please select between 1 and 14 nights.';
    } else {
        // Calculate total amount
        $total_amount = $ROOM_PRICES[$room_type] * $nights;
        
        // Validate card details (mock validation)
        $card_number = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
        $card_expiry = $_POST['card_expiry'] ?? '';
        $card_cvv = $_POST['card_cvv'] ?? '';
        $card_holder = trim($_POST['card_holder'] ?? '');
        
        if (!preg_match('/^[0-9]{16}$/', $card_number)) {
            $error = 'Please enter a valid 16-digit card number.';
        } elseif (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $card_expiry)) {
            $error = 'Please enter a valid expiry date (MM/YY).';
        } elseif (!preg_match('/^[0-9]{3}$/', $card_cvv)) {
            $error = 'Please enter a valid 3-digit CVV.';
        } elseif (empty($card_holder)) {
            $error = 'Please enter the card holder name.';
        } else {
            try {
                $conn->beginTransaction();
                
                // Insert booking
                $stmt = $conn->prepare("INSERT INTO aweb_bookings (user_id, type, booking_date, room_type, total_amount, details) VALUES (?, 'hotel', ?, ?, ?, ?)");
                
                $details = json_encode([
                    'nights' => $nights,
                    'payment' => [
                        'last4' => substr($card_number, -4),
                        'brand' => 'Visa', // Mock card brand
                        'card_holder' => $card_holder
                    ]
                ]);
                
                $stmt->execute([$_SESSION['user_id'], $booking_date, $room_type, $total_amount, $details]);
                
                // Update loyalty points
                $points = 10; // Points per booking
                $stmt = $conn->prepare("INSERT INTO aweb_loyalty_points (user_id, points) 
                                      VALUES (?, ?) 
                                      ON DUPLICATE KEY UPDATE points = points + ?");
                
                $stmt->execute([$_SESSION['user_id'], $points, $points]);
                
                $conn->commit();
                set_flash_message("Booking successful! You've earned {$points} loyalty points!", 'success');
                header('Location: ../account/profile.php');
                exit;
            } catch (Exception $e) {
                $conn->rollback();
                $error = 'An error occurred while processing your booking: ' . $e->getMessage();
            }
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container py-5 animate-fade-in">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Room Preview Cards -->
            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <a href="/rza/bookings/standard-room.php" class="text-decoration-none">
                        <div class="card room-card shadow-sm h-100">
                            <div class="position-relative">
                                <img src="/rza/assets/images/standard-room.jpg" class="card-img-top" alt="Standard Room" style="height: 200px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-success">Most Popular</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-success">Standard Room</h5>
                                <div class="price-display mb-2">
                                    <span class="h4 text-success">£<?= $ROOM_PRICES['Standard'] ?></span>
                                    <small class="text-muted">per night</small>
                                </div>
                                <p class="card-text">Experience comfort in our well-appointed standard rooms, perfect for both business and leisure travelers.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-wifi me-2"></i> Free High-Speed WiFi</li>
                                    <li><i class="fas fa-tv me-2"></i> 42" Smart TV</li>
                                    <li><i class="fas fa-bed me-2"></i> Queen-Size Bed</li>
                                    <li><i class="fas fa-bath me-2"></i> En-suite Bathroom</li>
                                </ul>
                                <button class="btn btn-outline-success w-100 mt-3">View Details</button>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-4">
                    <a href="/rza/bookings/vip-room.php" class="text-decoration-none">
                        <div class="card room-card shadow-sm h-100">
                            <div class="position-relative">
                                <img src="/rza/assets/images/vip-room.webp" class="card-img-top" alt="VIP Suite" style="height: 200px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-success">Premium</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-success">VIP Suite</h5>
                                <div class="price-display mb-2">
                                    <span class="h4 text-success">£<?= $ROOM_PRICES['VIP'] ?></span>
                                    <small class="text-muted">per night</small>
                                </div>
                                <p class="card-text">Indulge in luxury with our spacious VIP suites, offering premium amenities and exceptional comfort.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-wifi me-2"></i> Premium High-Speed WiFi</li>
                                    <li><i class="fas fa-tv me-2"></i> 55" 4K Smart TV</li>
                                    <li><i class="fas fa-bed me-2"></i> King-Size Bed</li>
                                    <li><i class="fas fa-bath me-2"></i> Luxury Bathroom with Jacuzzi</li>
                                </ul>
                                <button class="btn btn-outline-success w-100 mt-3">View Details</button>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Booking Form Card -->
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Hotel Booking</h3>
                </div>
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Book Your Hotel Stay</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <!-- Stay Details Section -->
                        <div class="mb-4">
                            <h4 class="mb-3">1. Choose Your Stay Details</h4>
                            <div class="mb-3">
                                <label class="form-label">Check-in Date</label>
                                <input type="date" name="booking_date" class="form-control" 
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Number of Nights</label>
                                <input type="number" name="nights" class="form-control" 
                                       min="1" max="14" value="1" required>
                                <small class="text-muted">Maximum stay: 14 nights</small>
                            </div>
                        </div>

                        <!-- Room Selection Section -->
                        <div class="mb-4">
                            <h4 class="mb-3">2. Select Room Type</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input room-type" type="radio" name="room_type" 
                                               value="Standard" id="standardRoom" required checked>
                                        <label class="form-check-label" for="standardRoom">
                                            <strong>Standard Room</strong><br>
                                            £<?= number_format($ROOM_PRICES['Standard'], 2) ?> per night<br>
                                            <small class="text-muted">Comfortable room with essential amenities</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input room-type" type="radio" name="room_type" 
                                               value="VIP" id="vipRoom" required>
                                        <label class="form-check-label" for="vipRoom">
                                            <strong>VIP Suite</strong><br>
                                            £<?= number_format($ROOM_PRICES['VIP'], 2) ?> per night<br>
                                            <small class="text-muted">Luxury suite with premium amenities</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="total-amount" role="region" aria-label="Booking Total">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span id="totalLabel">Total Amount:</span>
                                    <span class="h4 mb-0" id="totalAmount" aria-labelledby="totalLabel">£0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="mb-4">
                            <h4 class="mb-3">3. Payment Details</h4>
                            <div class="mb-3">
                                <label class="form-label">Card Holder Name</label>
                                <input type="text" name="card_holder" class="form-control" 
                                       placeholder="Name on card" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Card Number</label>
                                <input type="text" name="card_number" class="form-control" 
                                       placeholder="1234 5678 9012 3456" 
                                       pattern="[0-9\s]{13,19}" maxlength="19" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" name="card_expiry" class="form-control" 
                                           placeholder="MM/YY" maxlength="5" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CVV</label>
                                    <input type="text" name="card_cvv" class="form-control" 
                                           placeholder="123" maxlength="3" required>
                                    <small class="text-muted">3 digits on back of card</small>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5>Booking Information</h5>
                                <ul class="mb-0">
                                    <li>Check-in time: 2:00 PM</li>
                                    <li>Check-out time: 11:00 AM</li>
                                    <li>Maximum stay: 14 nights</li>
                                    <li>Earn 10 loyalty points per booking</li>
                                    <li>Free cancellation up to 24 hours before check-in</li>
                                </ul>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Complete Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.room-card {
    transition: transform 0.3s ease-in-out;
    border-radius: 10px;
    overflow: hidden;
}

.room-card:hover {
    transform: translateY(-5px);
}

.price-display {
    color: var(--bs-success);
    font-weight: bold;
}

.card-img-top {
    transition: transform 0.3s ease-in-out;
}

.room-card:hover .card-img-top {
    transform: scale(1.05);
}
</style>

<script>
// Card number formatting
document.querySelector('[name="card_number"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = '';
    for(let i = 0; i < value.length; i++) {
        if(i > 0 && i % 4 === 0) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }
    e.target.value = formattedValue;
});

// Expiry date formatting
document.querySelector('[name="card_expiry"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    if(value.length > 2) {
        value = value.substr(0, 2) + '/' + value.substr(2);
    }
    e.target.value = value;
});

// Calculate total amount
function updateTotal() {
    const nights = parseInt(document.querySelector('[name="nights"]').value) || 0;
    const roomType = document.querySelector('input[name="room_type"]:checked').value;
    const pricePerNight = roomType === 'Standard' ? <?= $ROOM_PRICES['Standard'] ?> : <?= $ROOM_PRICES['VIP'] ?>;
    const total = nights * pricePerNight;
    document.getElementById('totalAmount').textContent = '£' + total.toFixed(2);
}

document.querySelector('[name="nights"]').addEventListener('input', updateTotal);
document.querySelectorAll('.room-type').forEach(input => {
    input.addEventListener('change', updateTotal);
});

// Initial calculation
updateTotal();
</script>

<?php require_once '../includes/footer.php'; ?> 