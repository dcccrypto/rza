<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

// Define ticket prices
$TICKET_PRICES = [
    'adult' => 25.00,  // £25 for adult tickets
    'child' => 15.00   // £15 for child tickets (under 12)
];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = $_POST['booking_date'] ?? '';
    $adult_quantity = (int)($_POST['adult_quantity'] ?? 0);
    $child_quantity = (int)($_POST['child_quantity'] ?? 0);
    $total_quantity = $adult_quantity + $child_quantity;
    
    // Validate inputs
    if (strtotime($booking_date) < strtotime('today')) {
        $error = 'Please select a future date.';
    } elseif ($total_quantity < 1 || $total_quantity > 10) {
        $error = 'Please select between 1 and 10 tickets in total.';
    } else {
        // Calculate total amount
        $total_amount = ($adult_quantity * $TICKET_PRICES['adult']) + 
                       ($child_quantity * $TICKET_PRICES['child']);
        
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
                $stmt = $conn->prepare("INSERT INTO aweb_bookings (user_id, type, booking_date, quantity, total_amount, details) VALUES (?, 'ticket', ?, ?, ?, ?)");
                
                $details = json_encode([
                    'adult' => $adult_quantity, 
                    'child' => $child_quantity,
                    'payment' => [
                        'last4' => substr($card_number, -4),
                        'brand' => 'Visa', // Mock card brand
                        'card_holder' => $card_holder
                    ]
                ]);
                
                $stmt->execute([$_SESSION['user_id'], $booking_date, $total_quantity, $total_amount, $details]);
                
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

<div class="container my-5 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Book Your Zoo Adventure</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <!-- Visit Details Section -->
                        <div class="mb-4">
                            <h4 class="mb-3">1. Choose Your Visit Date</h4>
                            <div class="mb-3">
                                <label class="form-label">Visit Date</label>
                                <input type="date" name="booking_date" class="form-control" 
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                            </div>
                        </div>

                        <!-- Ticket Selection Section -->
                        <div class="mb-4">
                            <h4 class="mb-3">2. Select Tickets</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Adult Tickets (£<?= number_format($TICKET_PRICES['adult'], 2) ?>)</label>
                                    <input type="number" name="adult_quantity" class="form-control ticket-quantity" 
                                           min="0" max="10" value="0" required>
                                    <small class="text-muted">Ages 12 and above</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Child Tickets (£<?= number_format($TICKET_PRICES['child'], 2) ?>)</label>
                                    <input type="number" name="child_quantity" class="form-control ticket-quantity" 
                                           min="0" max="10" value="0" required>
                                    <small class="text-muted">Under 12 years</small>
                                </div>
                            </div>
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Total Amount:</span>
                                        <span class="h4 mb-0" id="totalAmount">£0.00</span>
                                    </div>
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
                                    <li>Maximum 10 tickets per booking</li>
                                    <li>Children under 3 enter free</li>
                                    <li>Tickets are non-refundable</li>
                                    <li>Earn 10 loyalty points per booking</li>
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
    const adultQty = parseInt(document.querySelector('[name="adult_quantity"]').value) || 0;
    const childQty = parseInt(document.querySelector('[name="child_quantity"]').value) || 0;
    const total = (adultQty * <?= $TICKET_PRICES['adult'] ?>) + (childQty * <?= $TICKET_PRICES['child'] ?>);
    document.getElementById('totalAmount').textContent = '£' + total.toFixed(2);
}

document.querySelectorAll('.ticket-quantity').forEach(input => {
    input.addEventListener('input', updateTotal);
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const adultQty = parseInt(document.querySelector('[name="adult_quantity"]').value) || 0;
    const childQty = parseInt(document.querySelector('[name="child_quantity"]').value) || 0;
    const total = adultQty + childQty;
    
    if (total < 1 || total > 10) {
        e.preventDefault();
        alert('Please select between 1 and 10 tickets in total.');
    }
});
</script>

<?php require_once '../includes/footer.php'; ?> 