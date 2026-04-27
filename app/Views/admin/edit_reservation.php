<div class="admin-header">
    <h2>Edit Reservation #<?php echo (int) $id; ?></h2>
    <a href="<?php echo base_url('admin/reservations.php'); ?>" class="btn btn-sm btn-dark"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo e($error); ?></div>
<?php endif; ?>

<div class="admin-form-card">
    <h3>Reservation Details</h3>
    <form method="POST" action="<?php echo base_url('admin/edit_reservation.php?id=' . (int) $id); ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Guest Name</label>
                <input type="text" name="guest_name" class="form-control" value="<?php echo e($reservation['guest_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo e($reservation['email']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" name="phone" class="form-control" value="<?php echo e($reservation['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label>Room</label>
                <select name="room_id" class="form-control" required>
                    <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo (int) $room['id']; ?>" <?php echo $room['id'] == $reservation['room_id'] ? 'selected' : ''; ?>>
                        <?php echo e($room['capacity_type'] . ' ' . $room['room_type']); ?> (&#8369;<?php echo number_format((float) $room['price'], 2); ?>/day)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Check-in</label>
                <input type="date" name="check_in" class="form-control" value="<?php echo e($reservation['check_in']); ?>" required>
            </div>
            <div class="form-group">
                <label>Check-out</label>
                <input type="date" name="check_out" class="form-control" value="<?php echo e($reservation['check_out']); ?>" required>
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
                    <?php foreach (['Cash', 'Check', 'Credit Card'] as $paymentType): ?>
                    <option value="<?php echo e($paymentType); ?>" <?php echo $paymentType == $reservation['payment_type'] ? 'selected' : ''; ?>><?php echo e($paymentType); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <?php foreach (['Pending', 'Confirmed', 'Cancelled', 'Completed'] as $status): ?>
                <option value="<?php echo e($status); ?>" <?php echo $status == $reservation['status'] ? 'selected' : ''; ?>><?php echo e($status); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Special Requests</label>
            <textarea name="special_requests" class="form-control"><?php echo e($reservation['special_requests'] ?? ''); ?></textarea>
        </div>

        <div class="price-summary" style="margin-bottom:20px;">
            <div class="price-row"><span>Current Base Price:</span> <span>&#8369;<?php echo number_format((float) $reservation['base_price'], 2); ?></span></div>
            <?php if ($reservation['discount_percent'] > 0): ?>
            <div class="price-row"><span>Cash Discount:</span> <span style="color:#2ecc71;">-<?php echo e($reservation['discount_percent']); ?>%</span></div>
            <?php endif; ?>
            <?php if ($reservation['additional_charge_percent'] > 0): ?>
            <div class="price-row"><span>Additional Charge:</span> <span style="color:#e74c3c;">+<?php echo e($reservation['additional_charge_percent']); ?>%</span></div>
            <?php endif; ?>
            <div class="price-total"><span>Current Total:</span> <span>&#8369;<?php echo number_format((float) $reservation['total_price'], 2); ?></span></div>
        </div>
        <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:20px;"><i class="fas fa-info-circle"></i> The total will be automatically recalculated based on room, dates, and payment type when you save.</p>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        <a href="<?php echo base_url('admin/reservations.php'); ?>" class="btn btn-outline-gold" style="margin-left:10px;">Cancel</a>
    </form>
</div>
