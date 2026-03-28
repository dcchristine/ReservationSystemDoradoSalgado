<?php
require_once __DIR__ . '/../config/database.php';

// ---- ROOMS ----
function getAllRooms() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM rooms ORDER BY FIELD(capacity_type,'Single','Double','Family'), FIELD(room_type,'Regular','De Luxe','Suite')");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRoomById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getRoomsByCapacity($capacityType) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE capacity_type = ? ORDER BY FIELD(room_type,'Regular','De Luxe','Suite')");
    $stmt->execute([$capacityType]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---- PRICE CALCULATION ----
function calculateReservationPrice($roomPrice, $numDays, $paymentType) {
    $basePrice = $roomPrice * $numDays;
    $discountPercent = 0;
    $additionalChargePercent = 0;

    // Cash discounts
    if ($paymentType === 'Cash') {
        if ($numDays >= 6) {
            $discountPercent = 15;
        } elseif ($numDays >= 3) {
            $discountPercent = 10;
        }
    }

    // Payment surcharges
    if ($paymentType === 'Check') {
        $additionalChargePercent = 5;
    } elseif ($paymentType === 'Credit Card') {
        $additionalChargePercent = 10;
    }

    $discount = $basePrice * ($discountPercent / 100);
    $additionalCharge = $basePrice * ($additionalChargePercent / 100);
    $totalPrice = $basePrice - $discount + $additionalCharge;

    return [
        'base_price' => $basePrice,
        'discount_percent' => $discountPercent,
        'additional_charge_percent' => $additionalChargePercent,
        'discount_amount' => $discount,
        'additional_charge_amount' => $additionalCharge,
        'total_price' => $totalPrice
    ];
}

// ---- RESERVATIONS ----
function createReservation($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO reservations (guest_user_id, guest_name, email, phone, room_id, check_in, check_out, guests, num_days, payment_type, base_price, discount_percent, additional_charge_percent, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    return $stmt->execute([
        $data['guest_user_id'] ?? null,
        $data['guest_name'], $data['email'], $data['phone'], $data['room_id'],
        $data['check_in'], $data['check_out'], $data['guests'], $data['num_days'],
        $data['payment_type'], $data['base_price'], $data['discount_percent'],
        $data['additional_charge_percent'], $data['total_price']
    ]);
}

function getAllReservations($search = '', $status = '') {
    global $pdo;
    $sql = "SELECT r.*, rm.name as room_name, rm.capacity_type, rm.room_type, rm.price as room_rate FROM reservations r JOIN rooms rm ON r.room_id = rm.id WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (r.guest_name LIKE ? OR r.email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($status) {
        $sql .= " AND r.status = ?";
        $params[] = $status;
    }
    $sql .= " ORDER BY r.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReservationById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT r.*, rm.name as room_name, rm.price as room_price, rm.capacity_type, rm.room_type FROM reservations r JOIN rooms rm ON r.room_id = rm.id WHERE r.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateReservation($id, $data) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE reservations SET guest_name=?, email=?, phone=?, room_id=?, check_in=?, check_out=?, guests=?, num_days=?, payment_type=?, base_price=?, discount_percent=?, additional_charge_percent=?, total_price=?, status=? WHERE id=?");
    return $stmt->execute([
        $data['guest_name'], $data['email'], $data['phone'], $data['room_id'],
        $data['check_in'], $data['check_out'], $data['guests'], $data['num_days'],
        $data['payment_type'], $data['base_price'], $data['discount_percent'],
        $data['additional_charge_percent'], $data['total_price'], $data['status'], $id
    ]);
}

function deleteReservation($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    return $stmt->execute([$id]);
}

function getReservationStats() {
    global $pdo;
    $stats = [];
    $stats['total'] = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
    $stats['pending'] = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status='Pending'")->fetchColumn();
    $stats['confirmed'] = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status='Confirmed'")->fetchColumn();
    $stats['revenue'] = $pdo->query("SELECT COALESCE(SUM(total_price),0) FROM reservations WHERE status IN ('Confirmed','Completed')")->fetchColumn();
    return $stats;
}

// ---- CONTACTS ----
function saveContactMessage($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$data['name'], $data['email'], $data['subject'], $data['message']]);
}

function getAllContacts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function markContactRead($id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?");
    return $stmt->execute([$id]);
}

// ---- ADMIN AUTH ----
function adminLogin($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($password, $admin['password'])) {
        return $admin;
    }
    return false;
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// ---- GUEST AUTH ----
function registerGuest($data) {
    global $pdo;
    $hash = password_hash($data['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO guests (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$data['first_name'], $data['last_name'], $data['email'], $data['phone'], $hash]);
}

function guestLogin($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM guests WHERE email = ?");
    $stmt->execute([$email]);
    $guest = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($guest && password_verify($password, $guest['password'])) {
        return $guest;
    }
    return false;
}

function isGuestLoggedIn() {
    return isset($_SESSION['guest_id']);
}

function requireGuest() {
    if (!isGuestLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}

function getGuestById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM guests WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function guestEmailExists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM guests WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}

function getGuestReservations($guestId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT r.*, rm.name as room_name, rm.capacity_type, rm.room_type, rm.price as room_rate FROM reservations r JOIN rooms rm ON r.room_id = rm.id WHERE r.guest_user_id = ? ORDER BY r.created_at DESC");
    $stmt->execute([$guestId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function cancelGuestReservation($reservationId, $guestId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE reservations SET status = 'Cancelled' WHERE id = ? AND guest_user_id = ? AND status != 'Cancelled' AND check_in > CURDATE()");
    return $stmt->execute([$reservationId, $guestId]);
}
?>
