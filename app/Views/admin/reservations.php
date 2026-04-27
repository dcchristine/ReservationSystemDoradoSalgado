<div class="admin-header">
    <h2>All Reservations</h2>
    <div class="admin-user">
        <div class="admin-avatar"><?php echo e(strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1))); ?></div>
        <span><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></span>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Reservations (<?php echo count($reservations); ?>)</h3>
        <form class="filter-bar" method="GET" action="<?php echo base_url('admin/reservations.php'); ?>">
            <input type="text" name="search" class="search-input" placeholder="Search guest name..." value="<?php echo e($search); ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="">All Status</option>
                <?php foreach (['Pending', 'Confirmed', 'Cancelled', 'Completed'] as $status): ?>
                <option value="<?php echo e($status); ?>" <?php echo $statusFilter === $status ? 'selected' : ''; ?>><?php echo e($status); ?></option>
                <?php endforeach; ?>
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
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo (int) $reservation['id']; ?></td>
                    <td><strong><?php echo e($reservation['guest_name']); ?></strong><br><small style="color:var(--text-muted);"><?php echo e($reservation['email']); ?></small></td>
                    <td><?php echo e($reservation['room_name']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($reservation['check_in'])); ?></td>
                    <td><?php echo date('M d, Y', strtotime($reservation['check_out'])); ?></td>
                    <td><?php echo (int) $reservation['num_days']; ?></td>
                    <td><?php echo e($reservation['payment_type']); ?></td>
                    <td>&#8369;<?php echo number_format((float) $reservation['base_price'], 2); ?></td>
                    <td>
                        <?php if ($reservation['discount_percent'] > 0): ?>
                            <span style="color:var(--success);">-<?php echo e($reservation['discount_percent']); ?>%</span>
                        <?php elseif ($reservation['additional_charge_percent'] > 0): ?>
                            <span style="color:var(--danger);">+<?php echo e($reservation['additional_charge_percent']); ?>%</span>
                        <?php else: ?>
                            <span style="color:var(--text-light);">-</span>
                        <?php endif; ?>
                    </td>
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
</div>
