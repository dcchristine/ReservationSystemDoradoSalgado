<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();

$id = intval($_GET['id'] ?? 0);
$reservation = getReservationById($id);
if (!$reservation) { header('Location: reservations.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if (deleteReservation($id)) {
        $_SESSION['success'] = 'Reservation #' . $id . ' has been deleted.';
    } else {
        $_SESSION['error'] = 'Failed to delete reservation.';
    }
    header('Location: reservations.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Reservation | Admin Panel</title>
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

        <main class="admin-main" style="display:flex; align-items:center; justify-content:center;">
            <div class="confirm-card">
                <div class="confirm-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h3>Delete Reservation</h3>
                <p>Are you sure you want to delete the reservation for <strong><?php echo htmlspecialchars($reservation['guest_name']); ?></strong>?<br>
                Room: <?php echo htmlspecialchars($reservation['room_name']); ?> | Total: ₱<?php echo number_format($reservation['total_price'], 2); ?><br>
                <small>This action cannot be undone.</small></p>
                <form method="POST" class="confirm-actions">
                    <input type="hidden" name="confirm_delete" value="1">
                    <button type="submit" class="btn btn-primary" style="background:var(--danger);box-shadow:none;"><i class="fas fa-trash"></i> Yes, Delete</button>
                    <a href="reservations.php" class="btn btn-outline-gold">Cancel</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
