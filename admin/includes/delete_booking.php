<?php
require_once '../../includes/config.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided']);
    exit();
}

$bookingId = (int)$_POST['id'];

try {
    $db->beginTransaction();
    
    // Update room status to available
    $roomId = $db->prepare("SELECT room_id FROM bookings WHERE id = ?")->execute([$bookingId])->fetchColumn();
    $db->prepare("UPDATE rooms SET status = 'available' WHERE id = ?")->execute([$roomId]);
    
    // Delete booking
    $stmt = $db->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$bookingId]);
    
    $db->commit();
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}