<div class="admin-header">
    <h2>Dashboard</h2>
    <div class="admin-user">
        <div class="admin-avatar"><?php echo e(strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1))); ?></div>
        <span><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></span>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
        <div class="stat-value"><?php echo (int) $stats['total']; ?></div>
        <div class="stat-label">Total Reservations</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-value"><?php echo (int) $stats['pending']; ?></div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card confirmed">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value"><?php echo (int) $stats['confirmed']; ?></div>
        <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-card revenue">
        <div class="stat-icon"><i class="fas fa-peso-sign"></i></div>
        <div class="stat-value">&#8369;<?php echo number_format((float) $stats['revenue'], 2); ?></div>
        <div class="stat-label">Total Revenue</div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Recent Reservations</h3>
        <a href="<?php echo base_url('admin/reservations.php'); ?>" class="btn btn-sm btn-outline-gold">View All</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>Guest</th><th>Room</th><th>Days</th><th>Payment</th><th>Total</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if (empty($recentReservations)): ?>
                <tr><td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">No reservations yet</td></tr>
            <?php else: ?>
                <?php foreach ($recentReservations as $reservation): ?>
                <tr>
                    <td><strong><?php echo e($reservation['guest_name']); ?></strong><br><small style="color:var(--text-muted);"><?php echo e($reservation['email']); ?></small></td>
                    <td><?php echo e($reservation['room_name']); ?></td>
                    <td><?php echo (int) $reservation['num_days']; ?></td>
                    <td><?php echo e($reservation['payment_type']); ?></td>
                    <td><strong>&#8369;<?php echo number_format((float) $reservation['total_price'], 2); ?></strong></td>
                    <td><span class="badge badge-<?php echo e(strtolower($reservation['status'])); ?>"><?php echo e($reservation['status']); ?></span></td>
                    <td class="actions">
                        <a href="<?php echo base_url('admin/edit_reservation.php?id=' . (int) $reservation['id']); ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="<?php echo base_url('admin/delete_reservation.php?id=' . (int) $reservation['id']); ?>" class="btn-delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
