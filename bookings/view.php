<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Fetch booking details
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    set_flash_message("Booking not found.", 'danger');
    header('Location: ../account/profile.php');
    exit;
}

$details = json_decode($booking['details'], true);
$can_cancel = strtotime($booking['booking_date']) > strtotime('today') && 
              (!isset($booking['status']) || $booking['status'] !== 'cancelled');

require_once '../includes/header.php';
?>

<div class="container my-5 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title mb-0">Booking Details</h2>
                        <div>
                            <?php if (isset($booking['status']) && $booking['status'] === 'cancelled'): ?>
                                <span class="badge bg-danger p-2">Cancelled</span>
                            <?php else: ?>
                                <span class="badge bg-<?= $booking['type'] === 'ticket' ? 'success' : 'primary' ?> p-2">
                                    <?= ucfirst($booking['type']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Booking Information</h5>
                            <p><strong>Date:</strong> <?= date('d F Y', strtotime($booking['booking_date'])) ?></p>
                            <p><strong>Reference:</strong> #<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></p>
                            <p><strong>Booked On:</strong> <?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?></p>
                            <?php if (isset($booking['cancelled_at'])): ?>
                                <p><strong>Cancelled On:</strong> <?= date('d/m/Y H:i', strtotime($booking['cancelled_at'])) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Details</h5>
                            <p><strong>Total Amount:</strong> £<?= number_format($booking['total_amount'], 2) ?></p>
                            <?php if (isset($details['payment'])): ?>
                                <p><strong>Card:</strong> **** **** **** <?= $details['payment']['last4'] ?></p>
                                <p><strong>Card Holder:</strong> <?= htmlspecialchars($details['payment']['card_holder']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($booking['type'] === 'ticket'): ?>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5>Ticket Details</h5>
                                <div class="row">
                                    <?php if ($details['adult'] > 0): ?>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Adult Tickets:</strong> <?= $details['adult'] ?></p>
                                            <small class="text-muted">Ages 12 and above</small>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($details['child'] > 0): ?>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Child Tickets:</strong> <?= $details['child'] ?></p>
                                            <small class="text-muted">Under 12 years</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5>Room Details</h5>
                                <p class="mb-2"><strong>Room Type:</strong> <?= $booking['room_type'] ?></p>
                                <?php if (isset($details['nights'])): ?>
                                    <p class="mb-0"><strong>Number of Nights:</strong> <?= $details['nights'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="../account/profile.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Profile
                        </a>
                        <div>
                            <?php if (!isset($booking['status']) || $booking['status'] !== 'cancelled'): ?>
                                <a href="download_receipt.php?id=<?= $booking['id'] ?>" 
                                   class="btn btn-outline-primary me-2">
                                    <i class="bi bi-download"></i> Download Receipt
                                </a>
                                <?php if ($can_cancel): ?>
                                    <form method="POST" action="cancel.php" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-x-circle"></i> Cancel Booking
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 