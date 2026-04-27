<?php
require_once __DIR__ . '/../app/bootstrap.php';

function getAllRooms(): array
{
    return Room::all();
}

function getRoomById($id): ?array
{
    return Room::find((int) $id);
}

function getRoomsByCapacity($capacityType): array
{
    return array_values(array_filter(Room::all(), function ($room) use ($capacityType) {
        return $room['capacity_type'] === $capacityType;
    }));
}

function calculateReservationPrice($roomPrice, $numDays, $paymentType): array
{
    return Reservation::calculatePrice((float) $roomPrice, (int) $numDays, (string) $paymentType);
}

function createReservation($data): bool
{
    return Reservation::create($data);
}

function getAllReservations($search = '', $status = ''): array
{
    return Reservation::all((string) $search, (string) $status);
}

function getReservationById($id): ?array
{
    return Reservation::find((int) $id);
}

function updateReservation($id, $data): bool
{
    return Reservation::update((int) $id, $data);
}

function deleteReservation($id): bool
{
    return Reservation::delete((int) $id);
}

function getReservationStats(): array
{
    return Reservation::stats();
}

function saveContactMessage($data): bool
{
    return Contact::create($data);
}

function getAllContacts(): array
{
    return Contact::all();
}

function markContactRead($id): bool
{
    return Contact::markRead((int) $id);
}

function adminLogin($username, $password): ?array
{
    return AdminUser::authenticate((string) $username, (string) $password);
}

function isAdminLoggedIn(): bool
{
    return isset($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        redirect_to('admin/login.php');
    }
}

function registerGuest($data): bool
{
    return Guest::create($data);
}

function guestLogin($email, $password): ?array
{
    return Guest::authenticate((string) $email, (string) $password);
}

function isGuestLoggedIn(): bool
{
    return isset($_SESSION['guest_id']);
}

function requireGuest(): void
{
    if (!isGuestLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? base_url('reservation.php');
        redirect_to('login.php');
    }
}

function getGuestById($id): ?array
{
    $stmt = Database::connection()->prepare('SELECT * FROM guests WHERE id = ?');
    $stmt->execute([(int) $id]);
    $guest = $stmt->fetch();

    return $guest ?: null;
}

function guestEmailExists($email): bool
{
    return Guest::emailExists((string) $email);
}

function getGuestReservations($guestId): array
{
    return Reservation::forGuest((int) $guestId);
}

function cancelGuestReservation($reservationId, $guestId): bool
{
    return Reservation::cancelForGuest((int) $reservationId, (int) $guestId);
}
