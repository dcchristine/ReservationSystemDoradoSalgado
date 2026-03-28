<?php
session_start();
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guest_name = trim($_POST['guest_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $room_id = intval($_POST['room_id'] ?? 0);
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $guests = intval($_POST['guests'] ?? 1);
    $payment_type = $_POST['payment_type'] ?? 'Cash';
    $special_requests = trim($_POST['special_requests'] ?? '');

    // Validation
    $errors = [];
    if (empty($guest_name)) $errors[] = 'Full name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($phone)) $errors[] = 'Phone number is required.';
    if ($room_id <= 0) $errors[] = 'Please select a room.';
    if (empty($check_in) || empty($check_out)) $errors[] = 'Check-in and check-out dates are required.';
    if ($check_in >= $check_out) $errors[] = 'Check-out must be after check-in.';
    if ($guests < 1) $errors[] = 'At least 1 guest is required.';
    if (!in_array($payment_type, ['Cash', 'Check', 'Credit Card'])) $errors[] = 'Invalid payment type.';

    if (empty($errors)) {
        $room = getRoomById($room_id);
        if ($room) {
            $checkinDate = new DateTime($check_in);
            $checkoutDate = new DateTime($check_out);
            $numDays = $checkoutDate->diff($checkinDate)->days;

            $pricing = calculateReservationPrice($room['price'], $numDays, $payment_type);

            $data = [
                'guest_user_id' => $_SESSION['guest_id'] ?? null,
                'guest_name' => $guest_name,
                'email' => $email,
                'phone' => $phone,
                'room_id' => $room_id,
                'check_in' => $check_in,
                'check_out' => $check_out,
                'guests' => $guests,
                'num_days' => $numDays,
                'payment_type' => $payment_type,
                'base_price' => $pricing['base_price'],
                'discount_percent' => $pricing['discount_percent'],
                'additional_charge_percent' => $pricing['additional_charge_percent'],
                'total_price' => $pricing['total_price'],
                'special_requests' => $special_requests
            ];

            if (createReservation($data)) {
                $msg = "Reservation submitted successfully!<br>";
                $msg .= "Room: {$room['name']}<br>";
                $msg .= "Days: {$numDays} | Payment: {$payment_type}<br>";
                $msg .= "Rate/day: ₱" . number_format($room['price'], 2) . "<br>";
                $msg .= "Base Price: ₱" . number_format($pricing['base_price'], 2);
                if ($pricing['discount_percent'] > 0) {
                    $msg .= "<br>Cash Discount ({$pricing['discount_percent']}%): -₱" . number_format($pricing['discount_amount'], 2);
                }
                if ($pricing['additional_charge_percent'] > 0) {
                    $msg .= "<br>Additional Charge ({$pricing['additional_charge_percent']}%): +₱" . number_format($pricing['additional_charge_amount'], 2);
                }
                $msg .= "<br><strong>Total: ₱" . number_format($pricing['total_price'], 2) . "</strong>";
                $_SESSION['success'] = $msg;
            } else {
                $_SESSION['error'] = 'Failed to submit reservation. Please try again.';
            }
        } else {
            $_SESSION['error'] = 'Selected room not found.';
        }
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

header('Location: ../reservation.php');
exit;
?>
