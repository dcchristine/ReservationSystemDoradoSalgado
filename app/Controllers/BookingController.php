<?php

class BookingController extends BaseController
{
    public function index(): void
    {
        $this->requireGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'cancel_reservation') {
            $reservationId = (int) ($_POST['reservation_id'] ?? 0);
            if ($reservationId > 0) {
                Reservation::cancelForGuest($reservationId, (int) $_SESSION['guest_id']);
                $_SESSION['success_msg'] = 'Reservation cancelled successfully.';
            }
            redirect_to('my-bookings.php');
        }

        $this->render('bookings/index', [
            'pageTitle' => 'My Bookings',
            'activePage' => 'bookings',
            'reservations' => Reservation::forGuest((int) $_SESSION['guest_id']),
        ]);
    }
}
