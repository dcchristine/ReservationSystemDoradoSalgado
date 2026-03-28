<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();

$id = intval($_GET['id'] ?? 0);
$reservation = getReservationById($id);
if (!$reservation) { header('Location: reservations.php'); exit; }

$rooms = getAllRooms();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = getRoomById(intval($_POST['room_id']));
    $checkin = new DateTime($_POST['check_in']);
    $checkout = new DateTime($_POST['check_out']);
    $numDays = $checkout->diff($checkin)->days;
    $paymentType = $_POST['payment_type'];

    $pricing = calculateReservationPrice($room['price'], $numDays, $paymentType);

    $data = [
        'guest_name' => trim($_POST['guest_name']),
        'email' => trim($_POST['email']),
        'phone' => trim($_POST['phone']),
        'room_id' => intval($_POST['room_id']),
        'check_in' => $_POST['check_in'],
        'check_out' => $_POST['check_out'],
        'guests' => intval($_POST['guests']),
        'num_days' => $numDays,
        'payment_type' => $paymentType,
        'base_price' => $pricing['base_price'],
        'discount_percent' => $pricing['discount_percent'],
        'additional_charge_percent' => $pricing['additional_charge_percent'],
        'total_price' => $pricing['total_price'],
        'status' => $_POST['status']
    ];

    if (updateReservation($id, $data)) {
        $_SESSION['success'] = 'Reservation #' . $id . ' updated successfully!';
        header('Location: reservations.php');
        exit;
    } else {
        $error = 'Failed to update reservation.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation | Admin Panel</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/pages.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../assets/images/logo.png" alt="Logo">
                <div><h3 style="color:var(--white);">Dorado Salgado</h3><small>Admin Panel</small></div>
            </div>
            <nav>
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="reservations.php" class="active"><i class="fas fa-calendar-alt"></i> Reservations</a>
                <a href="contacts.php"><i class="fas fa-envelope"></i> Messages</a>
                <div class="sidebar-divider"></div>
                <a href="../index.php"><i class="fas fa-globe"></i> View Website</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h2>Edit Reservation #<?php echo $id; ?></h2>
                <a href="reservations.php" class="btn btn-sm btn-dark"><i class="fas fa-arrow-left"></i> Back</a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>

            <div class="admin-form-card">
                <h3>Reservation Details</h3>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Guest Name</label>
                            <input type="text" name="guest_name" class="form-control" value="<?php echo htmlspecialchars($reservation['guest_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($reservation['email']); ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($reservation['phone']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <select name="room_id" class="form-control" required>
                                <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room['id']; ?>" <?php echo $room['id'] == $reservation['room_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($room['capacity_type'] . ' ' . $room['room_type']); ?> (₱<?php echo number_format($room['price'], 2); ?>/day)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Check-in</label>
                            <input type="date" name="check_in" class="form-control" value="<?php echo $reservation['check_in']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Check-out</label>
                            <input type="date" name="check_out" class="form-control" value="<?php echo $reservation['check_out']; ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Guests</label>
                            <select name="guests" class="form-control">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $i == $reservation['guests'] ? 'selected' : ''; ?>><?php echo $i; ?> Guest<?php echo $i > 1 ? 's' : ''; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Type</label>
                            <select name="payment_type" class="form-control">
                                <?php foreach (['Cash', 'Check', 'Credit Card'] as $pt): ?>
                                <option value="<?php echo $pt; ?>" <?php echo $pt == $reservation['payment_type'] ? 'selected' : ''; ?>><?php echo $pt; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <?php foreach (['Pending','Confirmed','Cancelled','Completed'] as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $s == $reservation['status'] ? 'selected' : ''; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Special Requests</label>
                        <textarea name="special_requests" class="form-control"><?php echo htmlspecialchars($reservation['special_requests'] ?? ''); ?></textarea>
                    </div>

                    <!-- Current Pricing Info -->
                    <div class="price-summary" style="margin-bottom:20px;">
                        <div class="price-row"><span>Current Base Price:</span> <span>₱<?php echo number_format($reservation['base_price'], 2); ?></span></div>
                        <?php if ($reservation['discount_percent'] > 0): ?>
                        <div class="price-row"><span>Cash Discount:</span> <span style="color:#2ecc71;">-<?php echo $reservation['discount_percent']; ?>%</span></div>
                        <?php endif; ?>
                        <?php if ($reservation['additional_charge_percent'] > 0): ?>
                        <div class="price-row"><span>Additional Charge:</span> <span style="color:#e74c3c;">+<?php echo $reservation['additional_charge_percent']; ?>%</span></div>
                        <?php endif; ?>
                        <div class="price-total"><span>Current Total:</span> <span>₱<?php echo number_format($reservation['total_price'], 2); ?></span></div>
                    </div>
                    <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:20px;"><i class="fas fa-info-circle"></i> The total will be automatically recalculated based on room, dates, and payment type when you save.</p>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="reservations.php" class="btn btn-outline-gold" style="margin-left:10px;">Cancel</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
