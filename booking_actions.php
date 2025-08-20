<?php
require_once 'includes/functions.php';
require_once 'config/database.php';

require_role('ADMIN');

$database = new Database();
$pdo = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('grid.php', 'Invalid request', 'error');
}

$action = $_POST['action'] ?? '';
$bookingId = $_POST['booking_id'] ?? '';

if (empty($bookingId)) {
    redirect_with_message('grid.php', 'Booking ID required', 'error');
}

try {
    switch ($action) {
        case 'mark_paid':
            // Get booking and resource details
            $stmt = $pdo->prepare("
                SELECT b.*, r.display_name, r.custom_name 
                FROM bookings b 
                JOIN resources r ON b.resource_id = r.id 
                WHERE b.id = ?
            ");
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch();
            
            if (!$booking) {
                redirect_with_message('grid.php', 'Booking not found', 'error');
            }
            
            $resourceName = $booking['custom_name'] ?: $booking['display_name'];
            
            // Calculate duration for amount (you can modify this logic)
            $duration = calculate_duration($booking['check_in']);
            $amount = max(500, $duration['hours'] * 100); // Minimum 500, then 100 per hour
            
            if (mark_booking_paid($bookingId, $pdo)) {
                // Record the payment
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO payments (booking_id, resource_id, amount, payment_method, payment_status, admin_id, payment_notes) 
                        VALUES (?, ?, ?, 'CHECKOUT', 'COMPLETED', ?, ?)
                    ");
                    $stmt->execute([
                        $bookingId, 
                        $booking['resource_id'], 
                        $amount, 
                        $_SESSION['user_id'],
                        "Checkout payment for {$resourceName} - Duration: {$duration['formatted']}"
                    ]);
                } catch (Exception $e) {
                    // Continue even if payment recording fails
                }
                
                redirect_with_message('grid.php', 'Booking marked as paid! Room is now available.', 'success');
            } else {
                redirect_with_message('grid.php', 'Failed to mark as paid', 'error');
            }
            break;
            
        case 'checkout':
            // Get booking details for payment recording
            $stmt = $pdo->prepare("
                SELECT b.*, r.display_name, r.custom_name 
                FROM bookings b 
                JOIN resources r ON b.resource_id = r.id 
                WHERE b.id = ?
            ");
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch();
            
            if (complete_checkout($bookingId, $pdo)) {
                // Record checkout completion
                if ($booking) {
                    $resourceName = $booking['custom_name'] ?: $booking['display_name'];
                    $duration = calculate_duration($booking['check_in']);
                    $amount = max(500, $duration['hours'] * 100);
                    
                    try {
                        $stmt = $pdo->prepare("
                            INSERT INTO payments (booking_id, resource_id, amount, payment_method, payment_status, admin_id, payment_notes) 
                            VALUES (?, ?, ?, 'CHECKOUT_COMPLETE', 'COMPLETED', ?, ?)
                        ");
                        $stmt->execute([
                            $bookingId, 
                            $booking['resource_id'], 
                            $amount, 
                            $_SESSION['user_id'],
                            "Checkout completed for {$resourceName} - Duration: {$duration['formatted']}"
                        ]);
                    } catch (Exception $e) {
                        // Continue even if payment recording fails
                    }
                }
                
                redirect_with_message('grid.php', 'Checkout completed successfully!', 'success');
            } else {
                redirect_with_message('grid.php', 'Failed to complete checkout', 'error');
            }
            break;
            
        default:
            redirect_with_message('grid.php', 'Invalid action', 'error');
    }
} catch (Exception $e) {
    redirect_with_message('grid.php', 'Operation failed: ' . $e->getMessage(), 'error');
}
?>