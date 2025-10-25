<?php
session_start();
require_once '../db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $facilityId = intval($_POST['facility_id'] ?? 0);
    $bookDate = $_POST['book_date'] ?? '';
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';
    $numPax = intval($_POST['num_pax'] ?? 1);
    

    if (empty($facilityId) || empty($bookDate) || empty($startTime) || empty($endTime)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }

    $today = date('Y-m-d');
    if ($bookDate < $today) {
        echo json_encode(['success' => false, 'message' => 'Cannot book dates in the past.']);
        exit();
    }

    if ($endTime <= $startTime) {
        echo json_encode(['success' => false, 'message' => 'End time must be after start time.']);
        exit();
    }

    $facility_stmt = $conn->prepare("SELECT facilityId, name, capacityMin, capacityMax FROM facility WHERE facilityId = ?");
    $facility_stmt->bind_param("i", $facilityId);
    $facility_stmt->execute();
    $facility_result = $facility_stmt->get_result();
    
    if ($facility_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid facility selected.']);
        exit();
    }
    
    $facility = $facility_result->fetch_assoc();
    $facility_stmt->close();

    if ($numPax < $facility['capacityMin'] || $numPax > $facility['capacityMax']) {
        echo json_encode([
            'success' => false, 
            'message' => "Number of people must be between {$facility['capacityMin']} and {$facility['capacityMax']} for this facility."
        ]);
        exit();
    }

    $conflict_stmt = $conn->prepare("
        SELECT bookingId FROM booking 
        WHERE facilityId = ? 
        AND bookedDate = ? 
        AND status != 'cancelled'
        AND (
            (startTime < ? AND endTime > ?) OR
            (startTime < ? AND endTime > ?) OR
            (startTime >= ? AND endTime <= ?)
        )
    ");
    $conflict_stmt->bind_param("isssssss", 
        $facilityId, $bookDate, 
        $endTime, $startTime,
        $endTime, $startTime,
        $startTime, $endTime
    );
    $conflict_stmt->execute();
    $conflict_result = $conflict_stmt->get_result();
    
    if ($conflict_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'This time slot is already booked. Please choose another time.']);
        exit();
    }
    $conflict_stmt->close();

    $avail_stmt = $conn->prepare("
        SELECT availabilityId FROM availability 
        WHERE facilityId = ? 
        AND date = ? 
        AND startTime <= ? 
        AND endTime >= ?
        AND isAvailable = 1
        LIMIT 1
    ");
    $avail_stmt->bind_param("isss", $facilityId, $bookDate, $startTime, $endTime);
    $avail_stmt->execute();
    $avail_result = $avail_stmt->get_result();
    
    if ($avail_result->num_rows === 0) {
        $create_avail = $conn->prepare("INSERT INTO availability (facilityId, date, startTime, endTime, isAvailable) VALUES (?, ?, ?, ?, 1)");
        $create_avail->bind_param("isss", $facilityId, $bookDate, $startTime, $endTime);
        $create_avail->execute();
        $availabilityId = $conn->insert_id;
        $create_avail->close();
    } else {
        $avail_data = $avail_result->fetch_assoc();
        $availabilityId = $avail_data['availabilityId'];
    }
    $avail_stmt->close();

    $insert_stmt = $conn->prepare("
        INSERT INTO booking (userId, facilityId, availabilityId, bookedDate, startTime, endTime, numPax, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed')
    ");
    $insert_stmt->bind_param("iiisssi", $userId, $facilityId, $availabilityId, $bookDate, $startTime, $endTime, $numPax);
    
    if ($insert_stmt->execute()) {
        $bookingId = $conn->insert_id;
        $update_avail = $conn->prepare("UPDATE availability SET isAvailable = 0 WHERE availabilityId = ?");
        $update_avail->bind_param("i", $availabilityId);
        $update_avail->execute();
        $update_avail->close();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Booking created successfully!',
            'booking_id' => $bookingId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating booking. Please try again.']);
    }
    
    $insert_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>