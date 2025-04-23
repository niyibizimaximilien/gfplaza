<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $roomId = (int)$_POST['room_id'];
    $customerName = trim($_POST['customer_name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone']);
    
    // Basic validation
    if (empty($customerName) || empty($phone) || !preg_match('/^[0-9]{10,15}$/', $phone)) {
        die("Invalid input data");
    }
    
    // Get room price
    $room = $db->prepare("SELECT price, discount_price FROM rooms WHERE id = ?");
    $room->execute([$roomId]);
    $room = $room->fetch(PDO::FETCH_ASSOC);
    
    if (!$room) {
        die("Invalid room selected");
    }
    
    $amount = $room['discount_price'] ? $room['discount_price'] : $room['price'];
    
    try {
        $db->beginTransaction();
        
        // Create booking
        $stmt = $db->prepare("INSERT INTO bookings (room_id, customer_name, email, phone, amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$roomId, $customerName, $email, $phone, $amount]);
        
        // Update room status
        $db->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?")->execute([$roomId]);
        
        $db->commit();
        
        // Redirect with success message
        header("Location: ../index.php?booking_success=1&amount=" . urlencode($amount));
        exit();
    } catch (PDOException $e) {
        $db->rollBack();
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
    exit();
}