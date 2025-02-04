<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../account/profile.php');
    exit;
}

$booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
$user_id = $_SESSION['user_id'];

try {
    $conn->begin_transaction();

    // Check if booking exists and belongs to user
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        throw new Exception("Booking not found.");
    }

    // Check if booking is in the future
    if (strtotime($booking['booking_date']) <= strtotime('today')) {
        throw new Exception("Cannot cancel bookings for today or past dates.");
    }

    // Remove loyalty points
    $points = 10; // Points per booking
    $stmt = $conn->prepare("UPDATE loyalty_points SET points = points - ? WHERE user_id = ? AND points >= ?");
    $stmt->bind_param("iii", $points, $user_id, $points);
    $stmt->execute();

    // Mark booking as cancelled
    $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled', cancelled_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    $conn->commit();
    set_flash_message("Booking cancelled successfully. Your loyalty points have been adjusted.", 'success');
} catch (Exception $e) {
    $conn->rollback();
    set_flash_message("Could not cancel booking: " . $e->getMessage(), 'danger');
}

header('Location: ../account/profile.php');
exit;
?> 