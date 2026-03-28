<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$stats = getReservationStats();
$recentReservations = getAllReservations();
$recentReservations = array_slice($recentReservations, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
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
                <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="reservations.php"><i class="fas fa-calendar-alt"></i> Reservations</a>
                <a href="contacts.php"><i class="fas fa-envelope"></i> Messages</a>
                <div class="sidebar-divider"></div>
                <a href="../index.php"><i class="fas fa-globe"></i> View Website</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h2>Dashboard</h2>
                <div class="admin-user">
                    <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
                    <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card total">
                    <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Total Reservations</div>
                </div>
                <div class="stat-card pending">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-value"><?php echo $stats['pending']; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card confirmed">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-value"><?php echo $stats['confirmed']; ?></div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card revenue">
                    <div class="stat-icon"><i class="fas fa-peso-sign"></i></div>
                    <div class="stat-value">₱<?php echo number_format($stats['revenue'], 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h3>Recent Reservations</h3>
                    <a href="reservations.php" class="btn btn-sm btn-outline-gold">View All</a>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr><th>Guest</th><th>Room</th><th>Days</th><th>Payment</th><th>Total</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentReservations)): ?>
                            <tr><td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">No reservations yet</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentReservations as $r): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($r['guest_name']); ?></strong><br><small style="color:var(--text-muted);"><?php echo htmlspecialchars($r['email']); ?></small></td>
                                <td><?php echo htmlspecialchars($r['room_name']); ?></td>
                                <td><?php echo $r['num_days']; ?></td>
                                <td><?php echo htmlspecialchars($r['payment_type']); ?></td>
                                <td><strong>₱<?php echo number_format($r['total_price'], 2); ?></strong></td>
                                <td><span class="badge badge-<?php echo strtolower($r['status']); ?>"><?php echo $r['status']; ?></span></td>
                                <td class="actions">
                                    <a href="edit_resertvation.php?id=<?php echo $r['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="delete_reservation.php?id=<?php echo $r['id']; ?>" class="btn-delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
