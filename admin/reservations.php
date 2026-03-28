<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$reservations = getAllReservations($search, $statusFilter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations | Admin Panel</title>
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
                <h2>All Reservations</h2>
                <div class="admin-user">
                    <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
                    <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h3>Reservations (<?php echo count($reservations); ?>)</h3>
                    <form class="filter-bar" method="GET">
                        <input type="text" name="search" class="search-input" placeholder="Search guest name..." value="<?php echo htmlspecialchars($search); ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="Pending" <?php echo $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Confirmed" <?php echo $statusFilter === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="Cancelled" <?php echo $statusFilter === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            <option value="Completed" <?php echo $statusFilter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>#</th><th>Guest</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Days</th><th>Payment</th><th>Base</th><th>Disc/Charge</th><th>Total</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr><td colspan="12" style="text-align:center; padding:40px; color:var(--text-muted);">No reservations found</td></tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $r): ?>
                            <tr>
                                <td><?php echo $r['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($r['guest_name']); ?></strong><br><small style="color:var(--text-muted);"><?php echo htmlspecialchars($r['email']); ?></small></td>
                                <td><?php echo htmlspecialchars($r['room_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($r['check_in'])); ?></td>
                                <td><?php echo date('M d, Y', strtotime($r['check_out'])); ?></td>
                                <td><?php echo $r['num_days']; ?></td>
                                <td><?php echo htmlspecialchars($r['payment_type']); ?></td>
                                <td>₱<?php echo number_format($r['base_price'], 2); ?></td>
                                <td>
                                    <?php if ($r['discount_percent'] > 0): ?>
                                        <span style="color:var(--success);">-<?php echo $r['discount_percent']; ?>%</span>
                                    <?php elseif ($r['additional_charge_percent'] > 0): ?>
                                        <span style="color:var(--danger);">+<?php echo $r['additional_charge_percent']; ?>%</span>
                                    <?php else: ?>
                                        <span style="color:var(--text-light);">—</span>
                                    <?php endif; ?>
                                </td>
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
            </div>
        </main>
    </div>
</body>
</html>
