<?php
require_once '../../includes/config.php';

if (!isset($_GET['id'])) {
    die("Booking ID not provided");
}

$bookingId = (int)$_GET['id'];
$booking = $db->prepare("
    SELECT b.*, r.name as room_name, r.price as room_price, r.image as room_image 
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.id = ?
");
$booking->execute([$bookingId]);
$booking = $booking->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found");
}
?>
<div class="booking-details">
    <h2>Booking Details</h2>
    
    <div class="details-grid">
        <div class="detail">
            <strong>Booking ID:</strong>
            <span><?php echo $booking['id']; ?></span>
        </div>
        <div class="detail">
            <strong>Room:</strong>
            <span><?php echo htmlspecialchars($booking['room_name']); ?></span>
        </div>
        <div class="detail">
            <strong>Customer Name:</strong>
            <span><?php echo htmlspecialchars($booking['customer_name']); ?></span>
        </div>
        <div class="detail">
            <strong>Phone:</strong>
            <span><?php echo htmlspecialchars($booking['phone']); ?></span>
        </div>
        <div class="detail">
            <strong>Email:</strong>
            <span><?php echo htmlspecialchars($booking['email'] ?: 'N/A'); ?></span>
        </div>
        <div class="detail">
            <strong>Amount:</strong>
            <span>KSh <?php echo number_format($booking['amount'], 2); ?></span>
        </div>
        <div class="detail">
            <strong>Booking Date:</strong>
            <span><?php echo date('M j, Y H:i', strtotime($booking['booking_date'])); ?></span>
        </div>
    </div>
    
    <?php if ($booking['room_image']): ?>
    <div class="room-image">
        <h3>Room Image</h3>
        <img src="../uploads/<?php echo htmlspecialchars($booking['room_image']); ?>" alt="<?php echo htmlspecialchars($booking['room_name']); ?>" style="max-width: 200px;">
    </div>
    <?php endif; ?>
</div>