<?php
require('fpdf/fpdf.php');

class ReceiptGenerator extends FPDF {
    private $booking;
    private $details;

    function __construct($booking, $details) {
        parent::__construct();
        $this->booking = $booking;
        $this->details = $details;
    }

    function Header() {
        // Logo
        $this->Image('../assets/images/logo.png', 10, 10, 50);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 20, 'Riget Zoo Adventures', 0, 1, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Receipt #' . str_pad($this->booking['id'], 6, '0', STR_PAD_LEFT), 0, 1, 'R');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Thank you for choosing Riget Zoo Adventures', 0, 0, 'C');
    }

    function generateReceipt() {
        $this->AddPage();
        
        // Booking Details
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Booking Details', 0, 1);
        $this->SetFont('Arial', '', 11);
        $this->Cell(50, 7, 'Booking Date:', 0);
        $this->Cell(0, 7, date('d F Y', strtotime($this->booking['booking_date'])), 0, 1);
        $this->Cell(50, 7, 'Booking Type:', 0);
        $this->Cell(0, 7, ucfirst($this->booking['type']), 0, 1);
        
        // Payment Details
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Payment Information', 0, 1);
        $this->SetFont('Arial', '', 11);
        $this->Cell(50, 7, 'Card Holder:', 0);
        $this->Cell(0, 7, $this->details['payment']['card_holder'], 0, 1);
        $this->Cell(50, 7, 'Card Number:', 0);
        $this->Cell(0, 7, '**** **** **** ' . $this->details['payment']['last4'], 0, 1);
        
        // Items
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Order Details', 0, 1);
        $this->SetFont('Arial', '', 11);
        
        // Table Header
        $this->SetFillColor(240, 240, 240);
        $this->Cell(90, 8, 'Item', 1, 0, 'L', true);
        $this->Cell(30, 8, 'Quantity', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Price', 1, 0, 'R', true);
        $this->Cell(40, 8, 'Total', 1, 1, 'R', true);
        
        if ($this->booking['type'] === 'ticket') {
            // Adult Tickets
            if ($this->details['adult'] > 0) {
                $this->Cell(90, 8, 'Adult Ticket (Ages 12+)', 1);
                $this->Cell(30, 8, $this->details['adult'], 1, 0, 'C');
                $this->Cell(30, 8, number_format(25.00, 2), 1, 0, 'R');
                $this->Cell(40, 8, number_format($this->details['adult'] * 25.00, 2), 1, 1, 'R');
            }
            
            // Child Tickets
            if ($this->details['child'] > 0) {
                $this->Cell(90, 8, 'Child Ticket (Under 12)', 1);
                $this->Cell(30, 8, $this->details['child'], 1, 0, 'C');
                $this->Cell(30, 8, number_format(15.00, 2), 1, 0, 'R');
                $this->Cell(40, 8, number_format($this->details['child'] * 15.00, 2), 1, 1, 'R');
            }
        } else {
            // Hotel Booking
            $this->Cell(90, 8, $this->booking['room_type'] . ' Room', 1);
            $this->Cell(30, 8, $this->details['nights'] . ' nights', 1, 0, 'C');
            $price_per_night = $this->booking['room_type'] === 'Standard' ? 150.00 : 250.00;
            $this->Cell(30, 8, number_format($price_per_night, 2), 1, 0, 'R');
            $this->Cell(40, 8, number_format($this->booking['total_amount'], 2), 1, 1, 'R');
        }
        
        // Total
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(150, 8, 'Total Amount:', 1);
        $this->Cell(40, 8, '£' . number_format($this->booking['total_amount'], 2), 1, 1, 'R');
        
        // Loyalty Points
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Loyalty Points Earned: 10', 0, 1);
        
        // Terms
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 5, "Terms & Conditions:\n- This receipt is proof of purchase\n- Bookings are non-refundable\n- Please present this receipt upon arrival");
    }
}
?> 