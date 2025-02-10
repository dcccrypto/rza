<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

$user_id = $_SESSION['user_id'];

// Add this near the top of the file for debugging
error_log("User ID from session: " . var_export($_SESSION['user_id'], true));

// Fetch user details
$stmt = $conn->prepare("SELECT email, created_at FROM aweb_users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // User not found in database
    set_flash_message('User account not found. Please try logging in again.', 'danger');
    header('Location: logout.php');
    exit;
}

// Fetch loyalty points
$stmt = $conn->prepare("SELECT points FROM aweb_loyalty_points WHERE user_id = ?");
$stmt->execute([$user_id]);
$loyalty = $stmt->fetch(PDO::FETCH_ASSOC);
$points = $loyalty ? $loyalty['points'] : 0;

// Fetch recent bookings
$stmt = $conn->prepare("SELECT * FROM aweb_bookings WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="container my-5 animate__animated animate__fadeIn">
    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Profile Information</h3>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Member Since:</strong> <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                    <div class="loyalty-points mt-4">
                        <h4>Loyalty Points</h4>
                        <div class="display-4 text-success"><?= $points ?></div>
                        <small class="text-muted">Earn 10 points per booking</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Card -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title mb-4">Recent Bookings</h3>
                    <?php if (!empty($bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td>#<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                            <td><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $booking['type'] === 'ticket' ? 'success' : 'primary' ?>">
                                                    <?= ucfirst($booking['type']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($booking['type'] == 'ticket'): ?>
                                                    <?= $booking['quantity'] ?> tickets
                                                <?php else: ?>
                                                    <?= $booking['room_type'] ?> Room
                                                <?php endif; ?>
                                            </td>
                                            <td>£<?= number_format($booking['total_amount'], 2) ?></td>
                                            <td>
                                                <a href="../bookings/view.php?id=<?= $booking['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No bookings yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 