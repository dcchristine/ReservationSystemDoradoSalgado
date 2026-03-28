<?php
session_start();
$pageTitle = 'My Bookings';
require_once 'includes/functions.php';
requireGuest();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel_reservation') {
    $reservationId = $_POST['reservation_id'] ?? 0;
    if ($reservationId) {
        cancelGuestReservation($reservationId, $_SESSION['guest_id']);
        $_SESSION['success_msg'] = "Reservation cancelled successfully.";
        header('Location: my-bookings.php');
        exit;
    }
}

$reservations = getGuestReservations($_SESSION['guest_id']);
include 'includes/header.php';
?>

<section class="page-header">
    <h2>My Bookings</h2>
    <div class="breadcrumb">
        <a href="index.php">Home</a> <span>/</span> <span>My Bookings</span>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:30px;">
            <div>
                <h3 style="font-family:var(--font-body);font-size:1.5rem;color:var(--navy);">
                    Welcome, <?php echo htmlspecialchars($_SESSION['guest_first_name']); ?>!
                </h3>
                <p style="color:var(--text-muted);">Here are your reservation history and upcoming stays.</p>
            </div>
            <a href="reservation.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Reservation</a>
        </div>

        <?php if (empty($reservations)): ?>
            <div class="admin-card" style="text-align:center;padding:60px 40px;">
                <div style="font-size:4rem;color:var(--cream-dark);margin-bottom:20px;"><i class="fas fa-calendar-times"></i></div>
                <h3 style="font-family:var(--font-body);color:var(--navy);margin-bottom:10px;">No Bookings Yet</h3>
                <p style="color:var(--text-muted);margin-bottom:24px;">You haven't made any reservations. Start by getting a quote!</p>
                <a href="reservation.php" class="btn btn-primary"><i class="fas fa-bed"></i> Browse Rooms & Book</a>
            </div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:20px;">
                <?php foreach ($reservations as $r): ?>
                <div class="admin-card" style="padding:28px;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">
                        <div style="flex:1;min-width:200px;">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                                <span class="badge badge-<?php echo strtolower($r['status']); ?>"><?php echo $r['status']; ?></span>
                                <span style="font-size:0.8rem;color:var(--text-light);">Booking #<?php echo $r['id']; ?></span>
                            </div>
                            <h4 style="font-family:var(--font-body);font-size:1.1rem;color:var(--navy);margin-bottom:8px;">
                                <i class="fas fa-bed" style="color:var(--gold);margin-right:6px;"></i>
                                <?php echo htmlspecialchars($r['room_name']); ?>
                            </h4>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:0.85rem;color:var(--text-muted);">
                                <div><i class="fas fa-calendar-check" style="color:var(--gold);width:16px;"></i> Check-in: <strong><?php echo date('M d, Y', strtotime($r['check_in'])); ?></strong></div>
                                <div><i class="fas fa-calendar-times" style="color:var(--gold);width:16px;"></i> Check-out: <strong><?php echo date('M d, Y', strtotime($r['check_out'])); ?></strong></div>
                                <div><i class="fas fa-moon" style="color:var(--gold);width:16px;"></i> Duration: <strong><?php echo $r['num_days']; ?> day<?php echo $r['num_days'] > 1 ? 's' : ''; ?></strong></div>
                                <div><i class="fas fa-users" style="color:var(--gold);width:16px;"></i> Guests: <strong><?php echo $r['guests']; ?></strong></div>
                                <div><i class="fas fa-credit-card" style="color:var(--gold);width:16px;"></i> Payment: <strong><?php echo htmlspecialchars($r['payment_type']); ?></strong></div>
                                <div><i class="fas fa-tag" style="color:var(--gold);width:16px;"></i> Rate: <strong>₱<?php echo number_format($r['room_rate'], 2); ?>/day</strong></div>
                            </div>
                        </div>
                        <div style="text-align:right;min-width:150px;">
                            <?php if ($r['discount_percent'] > 0): ?>
                                <div style="font-size:0.8rem;color:var(--success);margin-bottom:4px;">Cash Discount: -<?php echo $r['discount_percent']; ?>%</div>
                            <?php endif; ?>
                            <?php if ($r['additional_charge_percent'] > 0): ?>
                                <div style="font-size:0.8rem;color:var(--danger);margin-bottom:4px;"><?php echo $r['payment_type']; ?> Charge: +<?php echo $r['additional_charge_percent']; ?>%</div>
                            <?php endif; ?>
                            <div style="font-size:1.5rem;font-weight:700;color:var(--gold-dark);font-family:var(--font-heading);">
                                ₱<?php echo number_format($r['total_price'], 2); ?>
                            </div>
                            <div style="font-size:0.75rem;color:var(--text-light);margin-top:4px;">
                                Booked: <?php echo date('M d, Y', strtotime($r['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($r['special_requests']): ?>
                        <div style="margin-top:16px;padding-top:12px;border-top:1px solid var(--cream-dark);font-size:0.85rem;color:var(--text-muted);">
                            <i class="fas fa-comment" style="color:var(--gold);"></i> <em><?php echo htmlspecialchars($r['special_requests']); ?></em>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                    $today = new DateTime('today');
                    $checkInDate = new DateTime($r['check_in']);
                    if ($r['status'] !== 'Cancelled' && $checkInDate > $today): 
                    ?>
                        <div style="margin-top:16px;padding-top:12px;border-top:1px solid var(--cream-dark);text-align:right;">
                            <form method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to cancel this reservation? This action cannot be undone.');">
                                <input type="hidden" name="action" value="cancel_reservation">
                                <input type="hidden" name="reservation_id" value="<?php echo $r['id']; ?>">
                                <button type="submit" class="btn btn-sm" style="background:#fffafafa;border:1px solid #e74c3c;color:#e74c3c;padding:6px 12px;font-size:0.85rem;cursor:pointer;border-radius:4px;transition:0.2s;" onmouseover="this.style.background='#e74c3c';this.style.color='white';" onmouseout="this.style.background='#fffafafa';this.style.color='#e74c3c';">
                                    <i class="fas fa-times-circle"></i> Cancel Reservation
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
