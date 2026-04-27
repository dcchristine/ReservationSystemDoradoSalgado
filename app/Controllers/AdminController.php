<?php

class AdminController extends BaseController
{
    public function dashboard(): void
    {
        $this->requireAdmin();

        $recentReservations = array_slice(Reservation::all(), 0, 5);
        $this->render('admin/dashboard', [
            'pageTitle' => 'Dashboard',
            'activeAdmin' => 'dashboard',
            'stats' => Reservation::stats(),
            'recentReservations' => $recentReservations,
        ], 'layouts/admin');
    }

    public function reservations(): void
    {
        $this->requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $statusFilter = trim($_GET['status'] ?? '');

        $this->render('admin/reservations', [
            'pageTitle' => 'Reservations',
            'activeAdmin' => 'reservations',
            'search' => $search,
            'statusFilter' => $statusFilter,
            'reservations' => Reservation::all($search, $statusFilter),
        ], 'layouts/admin');
    }

    public function editReservation(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $reservation = Reservation::find($id);
        if (!$reservation) {
            redirect_to('admin/reservations.php');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room = Room::find((int) ($_POST['room_id'] ?? 0));
            if (!$room) {
                $error = 'Selected room was not found.';
            } elseif (($_POST['check_in'] ?? '') >= ($_POST['check_out'] ?? '')) {
                $error = 'Check-out must be after check-in.';
            } else {
                $checkin = new DateTime($_POST['check_in']);
                $checkout = new DateTime($_POST['check_out']);
                $numDays = $checkout->diff($checkin)->days;
                $paymentType = $_POST['payment_type'] ?? 'Cash';
                $pricing = Reservation::calculatePrice((float) $room['price'], $numDays, $paymentType);

                $data = [
                    'guest_name' => trim($_POST['guest_name'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'room_id' => (int) $_POST['room_id'],
                    'check_in' => $_POST['check_in'],
                    'check_out' => $_POST['check_out'],
                    'guests' => (int) ($_POST['guests'] ?? 1),
                    'num_days' => $numDays,
                    'payment_type' => $paymentType,
                    'special_requests' => trim($_POST['special_requests'] ?? ''),
                    'base_price' => $pricing['base_price'],
                    'discount_percent' => $pricing['discount_percent'],
                    'additional_charge_percent' => $pricing['additional_charge_percent'],
                    'total_price' => $pricing['total_price'],
                    'status' => $_POST['status'] ?? 'Pending',
                ];

                if (Reservation::update($id, $data)) {
                    $_SESSION['success'] = 'Reservation #' . $id . ' updated successfully!';
                    redirect_to('admin/reservations.php');
                }

                $error = 'Failed to update reservation.';
            }
        }

        $this->render('admin/edit_reservation', [
            'pageTitle' => 'Edit Reservation',
            'activeAdmin' => 'reservations',
            'id' => $id,
            'reservation' => $reservation,
            'rooms' => Room::all(),
            'error' => $error,
        ], 'layouts/admin');
    }

    public function deleteReservation(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $reservation = Reservation::find($id);
        if (!$reservation) {
            redirect_to('admin/reservations.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
            $deleted = Reservation::delete($id);
            $_SESSION[$deleted ? 'success' : 'error'] = $deleted
                ? 'Reservation #' . $id . ' has been deleted.'
                : 'Failed to delete reservation.';
            redirect_to('admin/reservations.php');
        }

        $this->render('admin/delete_reservation', [
            'pageTitle' => 'Delete Reservation',
            'activeAdmin' => 'reservations',
            'reservation' => $reservation,
        ], 'layouts/admin');
    }

    public function contacts(): void
    {
        $this->requireAdmin();

        if (isset($_GET['mark_read'])) {
            Contact::markRead((int) $_GET['mark_read']);
            redirect_to('admin/contacts.php');
        }

        $this->render('admin/contacts', [
            'pageTitle' => 'Messages',
            'activeAdmin' => 'contacts',
            'contacts' => Contact::all(),
        ], 'layouts/admin');
    }
}
