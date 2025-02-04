<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';
require_once '../includes/receipt_generator.php';
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

// Generate PDF
$pdf = new ReceiptGenerator($booking, $details);
$pdf->generateReceipt();

// Output PDF
$filename = 'RZA_Receipt_' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT) . '.pdf';
$pdf->Output('D', $filename);
?> 