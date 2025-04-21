<?php
session_start();
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to cancel bookings";
    header("Location: login.php");
    exit();
}

// Check if booking ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid booking ID";
    header("Location: booking.php");
    exit();
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // Verify booking belongs to user and is cancellable
    $stmt = $pdo->prepare("
        UPDATE bookings 
        SET status = 'cancelled' 
        WHERE id = ? 
        AND user_id = ?
        AND status != 'cancelled'
    ");
    
    $stmt->execute([$booking_id, $user_id]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Booking #$booking_id cancelled successfully";
    } else {
        $_SESSION['error'] = "Booking could not be cancelled or doesn't exist";
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Error cancelling booking: " . $e->getMessage();
}

header("Location: booking.php");
exit();
?>