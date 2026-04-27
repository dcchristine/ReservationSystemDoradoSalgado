<div style="min-height:calc(100vh - 60px);display:flex;align-items:center;justify-content:center;">
    <div class="confirm-card">
        <div class="confirm-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h3>Delete Reservation</h3>
        <p>Are you sure you want to delete the reservation for <strong><?php echo e($reservation['guest_name']); ?></strong>?<br>
        Room: <?php echo e($reservation['room_name']); ?> | Total: &#8369;<?php echo number_format((float) $reservation['total_price'], 2); ?><br>
        <small>This action cannot be undone.</small></p>
        <form method="POST" action="<?php echo base_url('admin/delete_reservation.php?id=' . (int) $reservation['id']); ?>" class="confirm-actions">
            <input type="hidden" name="confirm_delete" value="1">
            <button type="submit" class="btn btn-primary" style="background:var(--danger);box-shadow:none;"><i class="fas fa-trash"></i> Yes, Delete</button>
            <a href="<?php echo base_url('admin/reservations.php'); ?>" class="btn btn-outline-gold">Cancel</a>
        </form>
    </div>
</div>
