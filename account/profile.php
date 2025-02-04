<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch loyalty points
$stmt = $conn->prepare("SELECT points FROM loyalty_points WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$loyalty = $stmt->get_result()->fetch_assoc();
$points = $loyalty ? $loyalty['points'] : 0;

// Fetch recent bookings
$stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();

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
                    <?php if ($bookings->num_rows > 0): ?>
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
                                    <?php while ($booking = $bookings->fetch_assoc()): ?>
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
                                    <?php endwhile; ?>
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