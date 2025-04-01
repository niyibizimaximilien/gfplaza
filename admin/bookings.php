<?php
require_once '../includes/config.php';
require_once 'includes/auth.php';

// Get all bookings with room information
$bookings = $db->query("
    SELECT b.*, r.name as room_name, r.price as room_price 
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    ORDER BY b.booking_date DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GF Plaza - Manage Bookings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <h1>Manage Bookings</h1>
        
        <div class="table-section">
            <div class="search-box">
                <input type="text" id="bookingSearch" placeholder="Search bookings...">
                <button class="btn">Search</button>
            </div>
            
            <div class="table-responsive">
                <table id="bookingsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Room</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Booking Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['id']; ?></td>
                            <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                            <td><?php echo htmlspecialchars($booking['email']); ?></td>
                            <td>KSh <?php echo number_format($booking['amount'], 2); ?></td>
                            <td><?php echo date('M j, Y H:i', strtotime($booking['booking_date'])); ?></td>
                            <td>
                                <button class="btn btn-sm view-booking" data-id="<?php echo $booking['id']; ?>">View</button>
                                <button class="btn btn-sm btn-danger delete-booking" data-id="<?php echo $booking['id']; ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="bookingModalContent"></div>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>