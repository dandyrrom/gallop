<?php
session_start();
require_once '../db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $bookingId = intval($_POST['booking_id'] ?? 0);
    
    if (empty($bookingId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
        exit();
    }
    
    $check_stmt = $conn->prepare("SELECT availabilityId, status FROM booking WHERE bookingId = ? AND userId = ?");
    $check_stmt->bind_param("ii", $bookingId, $userId);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Booking not found or access denied.']);
        exit();
    }
    
    $booking = $check_result->fetch_assoc();
    
    if ($booking['status'] === 'cancelled') {
        echo json_encode(['success' => false, 'message' => 'Booking is already cancelled.']);
        exit();
    }
    
    $availabilityId = $booking['availabilityId'];
    $check_stmt->close();
    
    $cancel_stmt = $conn->prepare("UPDATE booking SET status = 'cancelled' WHERE bookingId = ?");
    $cancel_stmt->bind_param("i", $bookingId);
    
    if ($cancel_stmt->execute()) {
        $update_avail = $conn->prepare("UPDATE availability SET isAvailable = 1 WHERE availabilityId = ?");
        $update_avail->bind_param("i", $availabilityId);
        $update_avail->execute();
        $update_avail->close();
        
        echo json_encode(['success' => true, 'message' => 'Booking cancelled successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error cancelling booking. Please try again.']);
    }
    
    $cancel_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>