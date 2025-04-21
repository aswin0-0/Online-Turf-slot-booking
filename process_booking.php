<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        $required = ['court_id', 'booking_date', 'start_time', 'duration', 'total_amount'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Date validation
        $booking_date = $data['booking_date'];
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $booking_date)) {
            throw new Exception('Invalid date format');
        }

        $today = new DateTime('today', new DateTimeZone('Asia/Kolkata'));
        $selected_date = DateTime::createFromFormat('Y-m-d', $booking_date, new DateTimeZone('Asia/Kolkata'));
        
        if ($selected_date < $today) {
            throw new Exception('Cannot book past dates');
        }

        // Court validation
        $stmt = $pdo->prepare("SELECT * FROM courts WHERE id = ?");
        $stmt->execute([$data['court_id']]);
        $court = $stmt->fetch();

        if (!$court) {
            throw new Exception('Court not found');
        }

        // Time validation
        $start_time = $data['start_time'];
        if (!preg_match('/^(0[6-9]|1[0-9]|2[0-2]):00$/', $start_time)) {
            throw new Exception('Invalid time slot (6:00-22:00 only)');
        }

        // Calculate end time
        $duration = (int)$data['duration'];
        $start_datetime = DateTime::createFromFormat('Y-m-d H:i', "$booking_date $start_time", new DateTimeZone('Asia/Kolkata'));
        $end_datetime = clone $start_datetime;
        $end_datetime->modify("+$duration hours");

        // Check midnight crossing
        if ($end_datetime->format('Y-m-d') !== $booking_date) {
            throw new Exception('Bookings cannot extend past midnight');
        }

        // Check availability
        $stmt = $pdo->prepare("SELECT * FROM bookings 
                            WHERE court_id = ? 
                            AND booking_date = ?
                            AND (
                                (start_time < ? AND ADDTIME(start_time, SEC_TO_TIME(duration*3600)) > ?)
                                OR (start_time < ? AND ADDTIME(start_time, SEC_TO_TIME(duration*3600)) > ?)
                            )");
        $stmt->execute([
            $data['court_id'],
            $booking_date,
            $end_datetime->format('H:i:s'),
            $data['start_time'],
            $data['start_time'],
            $end_datetime->format('H:i:s')
        ]);

        if ($stmt->rowCount() > 0) {
            throw new Exception('Time slot already booked');
        }

        // Create booking
        $stmt = $pdo->prepare("INSERT INTO bookings 
                            (user_id, court_id, booking_date, start_time, duration, total_amount, status)
                            VALUES (?, ?, ?, ?, ?, ?, 'confirmed')");
        $stmt->execute([
            $_SESSION['user_id'],
            $data['court_id'],
            $booking_date,
            $data['start_time'],
            $duration,
            $data['total_amount'],
        ]);

        $booking_id = $pdo->lastInsertId();
        $pdo->commit();

        echo json_encode(['success' => true, 'booking_id' => $booking_id]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>