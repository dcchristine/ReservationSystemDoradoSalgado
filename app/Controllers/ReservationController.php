<?php

class ReservationController extends BaseController
{
    private array $capacityLabels = ['Single', 'Double', 'Family'];

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        }

        $modalState = [
            'showSuccessModal' => false,
            'showErrorModal' => false,
            'modalMessage' => '',
        ];

        if (isset($_SESSION['success'])) {
            $modalState['showSuccessModal'] = true;
            $modalState['modalMessage'] = $_SESSION['success'];
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            $modalState['showErrorModal'] = true;
            $modalState['modalMessage'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $this->render('pages/reservation', array_merge($modalState, [
            'pageTitle' => 'Reservation',
            'activePage' => 'reservation',
            'roomsByCapacity' => Room::groupedByCapacity(),
            'capacityLabels' => $this->capacityLabels,
            'isLoggedIn' => $this->guestLoggedIn(),
        ]));
    }

    private function store(): void
    {
        if (!$this->guestLoggedIn()) {
            $_SESSION['redirect_after_login'] = base_url('reservation.php');
            redirect_to('login.php');
        }

        $guestName = trim($_POST['guest_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $roomId = (int) ($_POST['room_id'] ?? 0);
        $checkIn = $_POST['check_in'] ?? '';
        $checkOut = $_POST['check_out'] ?? '';
        $guests = (int) ($_POST['guests'] ?? 1);
        $paymentType = $_POST['payment_type'] ?? 'Cash';
        $specialRequests = trim($_POST['special_requests'] ?? '');

        $errors = [];
        if ($guestName === '') {
            $errors[] = 'Full name is required.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }
        if ($phone === '') {
            $errors[] = 'Phone number is required.';
        }
        if ($roomId <= 0) {
            $errors[] = 'Please select a room.';
        }
        if ($checkIn === '' || $checkOut === '') {
            $errors[] = 'Check-in and check-out dates are required.';
        } elseif ($checkIn >= $checkOut) {
            $errors[] = 'Check-out must be after check-in.';
        }
        if ($guests < 1) {
            $errors[] = 'At least 1 guest is required.';
        }
        if (!in_array($paymentType, ['Cash', 'Check', 'Credit Card'], true)) {
            $errors[] = 'Invalid payment type.';
        }

        if ($errors !== []) {
            $_SESSION['error'] = implode('<br>', $errors);
            redirect_to('reservation.php');
        }

        $room = Room::find($roomId);
        if (!$room) {
            $_SESSION['error'] = 'Selected room not found.';
            redirect_to('reservation.php');
        }

        $checkinDate = new DateTime($checkIn);
        $checkoutDate = new DateTime($checkOut);
        $numDays = $checkoutDate->diff($checkinDate)->days;
        $pricing = Reservation::calculatePrice((float) $room['price'], $numDays, $paymentType);

        $saved = Reservation::create([
            'guest_user_id' => $_SESSION['guest_id'],
            'guest_name' => $guestName,
            'email' => $email,
            'phone' => $phone,
            'room_id' => $roomId,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => $guests,
            'num_days' => $numDays,
            'payment_type' => $paymentType,
            'special_requests' => $specialRequests,
            'base_price' => $pricing['base_price'],
            'discount_percent' => $pricing['discount_percent'],
            'additional_charge_percent' => $pricing['additional_charge_percent'],
            'total_price' => $pricing['total_price'],
        ]);

        if ($saved) {
            $message = 'Reservation submitted successfully!<br>';
            $message .= 'Room: ' . e($room['name']) . '<br>';
            $message .= 'Days: ' . $numDays . ' | Payment: ' . e($paymentType) . '<br>';
            $message .= 'Rate/day: &#8369;' . number_format((float) $room['price'], 2) . '<br>';
            $message .= 'Base Price: &#8369;' . number_format($pricing['base_price'], 2);

            if ($pricing['discount_percent'] > 0) {
                $message .= '<br>Cash Discount (' . $pricing['discount_percent'] . '%): -&#8369;' . number_format($pricing['discount_amount'], 2);
            }
            if ($pricing['additional_charge_percent'] > 0) {
                $message .= '<br>Additional Charge (' . $pricing['additional_charge_percent'] . '%): +&#8369;' . number_format($pricing['additional_charge_amount'], 2);
            }

            $message .= '<br><strong>Total: &#8369;' . number_format($pricing['total_price'], 2) . '</strong>';
            $_SESSION['success'] = $message;
        } else {
            $_SESSION['error'] = 'Failed to submit reservation. Please try again.';
        }

        redirect_to('reservation.php');
    }
}
