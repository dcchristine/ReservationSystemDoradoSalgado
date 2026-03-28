<?php
session_start();
$pageTitle = 'Reservation';
require_once 'includes/functions.php';
$rooms = getAllRooms();
$isLoggedIn = isGuestLoggedIn();

// Group rooms by capacity type
$roomsByCapacity = [];
foreach ($rooms as $room) {
    $roomsByCapacity[$room['capacity_type']][] = $room;
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <h2>Reservation</h2>
    <div class="breadcrumb">
        <a href="index.php">Home</a> <span>/</span> <span>Reservation</span>
    </div>
</section>

<!-- Pricing Info -->
<?php
$showSuccessModal = false;
$showErrorModal = false;
$modalMessage = '';
if (isset($_SESSION['success'])) {
    $showSuccessModal = true;
    $modalMessage = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $showErrorModal = true;
    $modalMessage = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<section class="section" style="padding-bottom:0;">
    <div class="container">

        <!-- Rate Tables -->
        <div class="section-header">
            <h2>Room Rates & Pricing</h2>
            <p class="subtitle">View our rates before booking</p>
            <div class="gold-line"></div>
        </div>
        <div style="display:grid; grid-template-columns:1.5fr 1fr; gap:30px; margin-bottom:60px;">
            <div class="admin-card">
                <div class="admin-card-header"><h3><i class="fas fa-bed" style="color:var(--gold);margin-right:8px;"></i> Room Rates (per day)</h3></div>
                <table class="admin-table">
                    <thead><tr><th>Room Capacity</th><th>Room Type</th><th>Rate/Day</th></tr></thead>
                    <tbody>
                        <?php
                        $capacityLabels = ['Single', 'Double', 'Family'];
                        foreach ($capacityLabels as $cap):
                            $capRooms = $roomsByCapacity[$cap] ?? [];
                            foreach ($capRooms as $i => $room):
                        ?>
                        <tr>
                            <?php if ($i === 0): ?>
                            <td rowspan="<?php echo count($capRooms); ?>" style="font-weight:600; vertical-align:middle;"><?php echo $cap; ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars($room['room_type']); ?></td>
                            <td style="font-weight:600; color:var(--gold-dark);">₱<?php echo number_format($room['price'], 2); ?></td>
                        </tr>
                        <?php endforeach; endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div>
                <div class="admin-card" style="margin-bottom:20px;">
                    <div class="admin-card-header"><h3><i class="fas fa-credit-card" style="color:var(--gold);margin-right:8px;"></i> Payment Charges</h3></div>
                    <table class="admin-table">
                        <thead><tr><th>Payment Type</th><th>Additional Charge</th></tr></thead>
                        <tbody>
                            <tr><td>Cash</td><td><span class="badge badge-confirmed">No add'l charge</span></td></tr>
                            <tr><td>Check</td><td><span class="badge badge-pending">+ 5%</span></td></tr>
                            <tr><td>Credit Card</td><td><span class="badge badge-cancelled">+ 10%</span></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="admin-card">
                    <div class="admin-card-header"><h3><i class="fas fa-tags" style="color:var(--gold);margin-right:8px;"></i> Cash Discounts</h3></div>
                    <table class="admin-table">
                        <thead><tr><th>Discount</th><th>Duration</th></tr></thead>
                        <tbody>
                            <tr><td><span class="badge badge-confirmed">10% discount</span></td><td>3–5 days</td></tr>
                            <tr><td><span class="badge badge-confirmed">15% discount</span></td><td>6 days and above</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reservation Form -->
<section class="section reservation-section" style="padding-top:20px;">
    <div class="container">
        <div class="reservation-wrapper">
            <!-- Room Selection -->
            <div class="room-selection">
                <div class="section-header" style="text-align:left; margin-bottom:20px;">
                    <h2 style="font-size:1.8rem;">Select Your Room</h2>
                    <p class="subtitle">Choose capacity and room type</p>
                </div>
                <!-- Tabs -->
                <div style="display:flex;gap:0;margin-bottom:20px;border-radius:var(--radius-sm);overflow:hidden;border:2px solid var(--cream-dark);">
                    <?php foreach ($capacityLabels as $index => $cap): ?>
                        <button type="button" onclick="switchRoomTab('<?php echo $cap; ?>')" id="tabRoom<?php echo $cap; ?>" style="flex:1;padding:12px 5px;border:none;cursor:pointer;font-family:var(--font-body);font-weight:600;font-size:0.9rem;transition:all 0.3s; <?php echo $index === 0 ? 'background:linear-gradient(135deg,var(--gold),var(--gold-dark));color:var(--white);' : 'background:var(--white);color:var(--text-muted);'; ?>">
                            <i class="fas fa-<?php echo $cap === 'Single' ? 'user' : ($cap === 'Double' ? 'user-friends' : 'users'); ?>" style="margin-right:4px;"></i> 
                            <span class="hide-mobile" style="display:inline-block;"><?php echo $cap; ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Tab Content -->
                <?php foreach ($capacityLabels as $index => $cap): ?>
                    <div id="roomTab<?php echo $cap; ?>" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                        <?php foreach (($roomsByCapacity[$cap] ?? []) as $room): ?>
                        <div class="room-select-card" data-room-id="<?php echo $room['id']; ?>" data-room-price="<?php echo $room['price']; ?>" data-room-name="<?php echo htmlspecialchars($room['name']); ?>" onclick="selectRoom(this)">
                            <img src="<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                            <div class="room-select-info">
                                <h4><?php echo htmlspecialchars($room['room_type']); ?></h4>
                                <p><?php echo htmlspecialchars($room['capacity_type']); ?> · <?php echo htmlspecialchars($room['room_type']); ?></p>
                                <div class="price">₱<?php echo number_format($room['price'], 2); ?> <small>/ day</small></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <script>
                function switchRoomTab(selectedCap) {
                    const caps = ['Single', 'Double', 'Family'];
                    caps.forEach(cap => {
                        const tabBtn = document.getElementById('tabRoom' + cap);
                        const tabContent = document.getElementById('roomTab' + cap);
                        
                        if (cap === selectedCap) {
                            tabContent.style.display = 'block';
                            tabBtn.style.background = 'linear-gradient(135deg,var(--gold),var(--gold-dark))';
                            tabBtn.style.color = 'var(--white)';
                        } else {
                            tabContent.style.display = 'none';
                            tabBtn.style.background = 'var(--white)';
                            tabBtn.style.color = 'var(--text-muted)';
                        }
                    });
                }
                </script>
            </div>

            <!-- Reservation Form -->
            <div class="reservation-form">
                <h3>Guest Details</h3>
                <form action="includes/reservation_process.php" method="POST" id="reservationForm">
                    <input type="hidden" name="room_id" id="room_id" required>

                    <?php if ($isLoggedIn): ?>
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="guest_name" class="form-control" placeholder="Enter your full name" required value="<?php echo htmlspecialchars(($_SESSION['guest_first_name'] ?? '') . ' ' . ($_SESSION['guest_last_name'] ?? '')); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com" required value="<?php echo htmlspecialchars($_SESSION['guest_email'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" class="form-control" placeholder="09XX XXX XXXX" required>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Check-in Date <span class="required">*</span></label>
                            <input type="date" name="check_in" id="check_in" class="form-control" min="<?php echo date('Y-m-d'); ?>" required onchange="calculatePrice()">
                        </div>
                        <div class="form-group">
                            <label>Check-out Date <span class="required">*</span></label>
                            <input type="date" name="check_out" id="check_out" class="form-control" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required onchange="calculatePrice()">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Number of Guests <span class="required">*</span></label>
                            <select name="guests" class="form-control" required>
                                <option value="1">1 Guest</option>
                                <option value="2" selected>2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                                <option value="5">5 Guests</option>
                                <option value="6">6 Guests</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type of Payment <span class="required">*</span></label>
                            <select name="payment_type" id="payment_type" class="form-control" required onchange="togglePaymentFields()">
                                <option value="Cash">Cash — No add'l charge</option>
                                <option value="Check">Check — +5% charge</option>
                                <option value="Credit Card">Credit Card — +10% charge</option>
                            </select>
                        </div>
                    </div>

                    <?php if ($isLoggedIn): ?>
                    <!-- Check Payment Details -->
                    <div id="checkDetails" style="display:none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Check Number <span class="required">*</span></label>
                                <input type="text" name="check_number" id="check_number" class="form-control" placeholder="Enter check number">
                            </div>
                            <div class="form-group">
                                <label>Bank Name <span class="required">*</span></label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter bank name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Check Date <span class="required">*</span></label>
                            <input type="date" name="check_date" id="check_date" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <!-- Credit Card Payment Details -->
                    <div id="creditCardDetails" style="display:none;">
                        <div class="form-group">
                            <label>Card Number <span class="required">*</span></label>
                            <input type="text" name="card_number" id="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" maxlength="19">
                        </div>
                        <div class="form-group">
                            <label>Cardholder Name <span class="required">*</span></label>
                            <input type="text" name="card_holder" id="card_holder" class="form-control" placeholder="Name on card">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiry Date <span class="required">*</span></label>
                                <input type="month" name="card_expiry" id="card_expiry" class="form-control" min="<?php echo date('Y-m'); ?>">
                            </div>
                            <div class="form-group">
                                <label>CVV <span class="required">*</span></label>
                                <input type="text" name="card_cvv" id="card_cvv" class="form-control" placeholder="XXX" maxlength="4">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($isLoggedIn): ?>
                    <div class="form-group">
                        <label>Special Requests</label>
                        <textarea name="special_requests" class="form-control" placeholder="Any special requests or preferences..."></textarea>
                    </div>
                    <?php endif; ?>

                    <!-- Price Summary -->
                    <div class="price-summary" id="priceSummary" style="display:none;">
                        <div class="price-row"><span>Selected Room:</span> <span id="summaryRoom">-</span></div>
                        <div class="price-row"><span>Rate per Day:</span> <span id="summaryRate">-</span></div>
                        <div class="price-row"><span>Number of Days:</span> <span id="summaryDays">-</span></div>
                        <div class="price-row"><span>Base Price:</span> <span id="summaryBase">-</span></div>
                        <div class="price-row" id="discountRow" style="display:none;"><span id="discountLabel">Cash Discount:</span> <span id="summaryDiscount" style="color:#2ecc71;">-</span></div>
                        <div class="price-row" id="chargeRow" style="display:none;"><span id="chargeLabel">Additional Charge:</span> <span id="summaryCharge" style="color:#e74c3c;">-</span></div>
                        <div class="price-total"><span>Total:</span> <span id="summaryTotal">₱0.00</span></div>
                    </div>

                    <?php if ($isLoggedIn): ?>
                        <button type="button" onclick="showConfirmModal()" class="btn btn-primary btn-lg" style="width:100%; justify-content:center;"><i class="fas fa-calendar-check"></i> Confirm Reservation</button>
                    <?php else: ?>
                        <div class="alert alert-info" style="margin-top:10px;"><i class="fas fa-info-circle"></i> Login or register to complete your reservation and add special requests.</div>
                        <a href="login.php" class="btn btn-primary btn-lg" style="width:100%; justify-content:center;"><i class="fas fa-sign-in-alt"></i> Login / Register to Book</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
let selectedPrice = 0;
let selectedRoomName = '';

function selectRoom(card) {
    document.querySelectorAll('.room-select-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('room_id').value = card.dataset.roomId;
    selectedPrice = parseFloat(card.dataset.roomPrice);
    selectedRoomName = card.dataset.roomName;
    calculatePrice();
}

function formatPeso(amount) {
    return '₱' + amount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function calculatePrice() {
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;
    const paymentType = document.getElementById('payment_type').value;
    const summary = document.getElementById('priceSummary');

    if (selectedPrice > 0 && checkIn && checkOut) {
        const days = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24));
        if (days > 0) {
            const basePrice = selectedPrice * days;
            let discountPercent = 0;
            let additionalChargePercent = 0;

            // Cash discounts
            if (paymentType === 'Cash') {
                if (days >= 6) discountPercent = 15;
                else if (days >= 3) discountPercent = 10;
            }

            // Payment surcharges
            if (paymentType === 'Check') additionalChargePercent = 5;
            else if (paymentType === 'Credit Card') additionalChargePercent = 10;

            const discountAmount = basePrice * (discountPercent / 100);
            const chargeAmount = basePrice * (additionalChargePercent / 100);
            const totalPrice = basePrice - discountAmount + chargeAmount;

            document.getElementById('summaryRoom').textContent = selectedRoomName;
            document.getElementById('summaryRate').textContent = formatPeso(selectedPrice) + ' / day';
            document.getElementById('summaryDays').textContent = days + (days === 1 ? ' day' : ' days');
            document.getElementById('summaryBase').textContent = formatPeso(basePrice);

            // Discount row
            const discountRow = document.getElementById('discountRow');
            if (discountPercent > 0) {
                document.getElementById('discountLabel').textContent = 'Cash Discount (' + discountPercent + '%):';
                document.getElementById('summaryDiscount').textContent = '- ' + formatPeso(discountAmount);
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }

            // Charge row
            const chargeRow = document.getElementById('chargeRow');
            if (additionalChargePercent > 0) {
                document.getElementById('chargeLabel').textContent = paymentType + ' Charge (' + additionalChargePercent + '%):';
                document.getElementById('summaryCharge').textContent = '+ ' + formatPeso(chargeAmount);
                chargeRow.style.display = 'flex';
            } else {
                chargeRow.style.display = 'none';
            }

            document.getElementById('summaryTotal').textContent = formatPeso(totalPrice);
            summary.style.display = 'block';
        } else {
            summary.style.display = 'none';
        }
    }
}

// Form validation
document.getElementById('reservationForm').addEventListener('submit', function(e) {
    if (!document.getElementById('room_id').value) {
        e.preventDefault();
        alert('Please select a room first.');
        return;
    }
    const pt = document.getElementById('payment_type').value;
    if (pt === 'Check') {
        if (!document.getElementById('check_number').value || !document.getElementById('bank_name').value || !document.getElementById('check_date').value) {
            e.preventDefault();
            alert('Please fill in all check payment details.');
            return;
        }
    }
    if (pt === 'Credit Card') {
        if (!document.getElementById('card_number').value || !document.getElementById('card_holder').value || !document.getElementById('card_expiry').value || !document.getElementById('card_cvv').value) {
            e.preventDefault();
            alert('Please fill in all credit card details.');
            return;
        }
    }
});

// Show/hide payment detail fields
function togglePaymentFields() {
    const pt = document.getElementById('payment_type').value;
    const checkEl = document.getElementById('checkDetails');
    const ccEl = document.getElementById('creditCardDetails');
    if (checkEl) checkEl.style.display = (pt === 'Check') ? 'block' : 'none';
    if (ccEl) ccEl.style.display = (pt === 'Credit Card') ? 'block' : 'none';
    calculatePrice();
}

document.getElementById('payment_type').addEventListener('change', togglePaymentFields);

// Card number formatting (XXXX XXXX XXXX XXXX)
const cardEl = document.getElementById('card_number');
if (cardEl) {
    cardEl.addEventListener('input', function(e) {
        let val = e.target.value.replace(/\D/g, '').substring(0, 16);
        e.target.value = val.replace(/(.{4})/g, '$1 ').trim();
    });
}

// CVV: numbers only
const cvvEl = document.getElementById('card_cvv');
if (cvvEl) {
    cvvEl.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
    });
}
// Show confirmation modal
function showConfirmModal() {
    const form = document.getElementById('reservationForm');
    if (!document.getElementById('room_id').value) {
        alert('Please select a room first.');
        return;
    }
    // Check required fields
    const requiredFields = form.querySelectorAll('[required]');
    let allValid = true;
    requiredFields.forEach(f => { if (!f.value) { f.reportValidity(); allValid = false; } });
    if (!allValid) return;

    const pt = document.getElementById('payment_type').value;
    if (pt === 'Check') {
        if (!document.getElementById('check_number').value || !document.getElementById('bank_name').value || !document.getElementById('check_date').value) {
            alert('Please fill in all check payment details.');
            return;
        }
    }
    if (pt === 'Credit Card') {
        if (!document.getElementById('card_number').value || !document.getElementById('card_holder').value || !document.getElementById('card_expiry').value || !document.getElementById('card_cvv').value) {
            alert('Please fill in all credit card details.');
            return;
        }
    }

    // Build confirmation summary
    const guestName = form.querySelector('[name="guest_name"]').value;
    const email = form.querySelector('[name="email"]').value;
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;
    const days = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24));
    const total = document.getElementById('summaryTotal')?.textContent || '—';

    document.getElementById('confirmGuestName').textContent = guestName;
    document.getElementById('confirmEmail').textContent = email;
    document.getElementById('confirmRoom').textContent = selectedRoomName;
    document.getElementById('confirmDates').textContent = checkIn + ' to ' + checkOut + ' (' + days + ' days)';
    document.getElementById('confirmPayment').textContent = pt;
    document.getElementById('confirmTotal').textContent = total;

    document.getElementById('confirmModal').style.display = 'flex';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

function submitReservation() {
    document.getElementById('confirmModal').style.display = 'none';
    document.getElementById('reservationForm').submit();
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
}

function closeErrorModal() {
    document.getElementById('errorModal').style.display = 'none';
}

// Auto-show success/error modal on page load
<?php if ($showSuccessModal): ?>
window.addEventListener('DOMContentLoaded', () => { document.getElementById('successModal').style.display = 'flex'; });
<?php endif; ?>
<?php if ($showErrorModal): ?>
window.addEventListener('DOMContentLoaded', () => { document.getElementById('errorModal').style.display = 'flex'; });
<?php endif; ?>
</script>

<!-- CONFIRMATION MODAL -->
<div id="confirmModal" class="modal-overlay" style="display:none;">
    <div class="modal-card fade-in-up">
        <div class="modal-icon" style="background:rgba(212,168,84,0.1);"><i class="fas fa-clipboard-check" style="color:var(--gold);font-size:2rem;"></i></div>
        <h3>Confirm Your Reservation</h3>
        <p style="color:var(--text-muted);margin-bottom:20px;">Please review your booking details below:</p>
        <div class="modal-details">
            <div class="modal-detail-row"><span>Guest Name:</span><strong id="confirmGuestName">—</strong></div>
            <div class="modal-detail-row"><span>Email:</span><strong id="confirmEmail">—</strong></div>
            <div class="modal-detail-row"><span>Room:</span><strong id="confirmRoom">—</strong></div>
            <div class="modal-detail-row"><span>Dates:</span><strong id="confirmDates">—</strong></div>
            <div class="modal-detail-row"><span>Payment:</span><strong id="confirmPayment">—</strong></div>
            <div class="modal-detail-row total"><span>Total:</span><strong id="confirmTotal" style="color:var(--gold);font-size:1.2rem;">—</strong></div>
        </div>
        <div class="modal-actions">
            <button onclick="submitReservation()" class="btn btn-primary"><i class="fas fa-check"></i> Yes, Book Now</button>
            <button onclick="closeConfirmModal()" class="btn btn-outline-gold">Cancel</button>
        </div>
    </div>
</div>

<!-- SUCCESS MODAL -->
<div id="successModal" class="modal-overlay" style="display:none;">
    <div class="modal-card fade-in-up">
        <div class="modal-icon" style="background:rgba(46,204,113,0.1);"><i class="fas fa-check-circle" style="color:var(--success);font-size:2.5rem;"></i></div>
        <h3 style="color:var(--success);">Reservation Successful!</h3>
        <div style="text-align:left;margin:20px 0;font-size:0.9rem;color:var(--text-muted);line-height:1.8;">
            <?php if ($showSuccessModal) echo $modalMessage; ?>
        </div>
        <div class="modal-actions">
            <a href="reservation.php" class="btn btn-primary"><i class="fas fa-check"></i> Done</a>
            <a href="index.php" class="btn btn-outline-gold"><i class="fas fa-home"></i> Go to Home</a>
        </div>
    </div>
</div>

<!-- ERROR MODAL -->
<div id="errorModal" class="modal-overlay" style="display:none;">
    <div class="modal-card fade-in-up">
        <div class="modal-icon" style="background:rgba(231,76,60,0.1);"><i class="fas fa-exclamation-circle" style="color:var(--danger);font-size:2.5rem;"></i></div>
        <h3 style="color:var(--danger);">Reservation Failed</h3>
        <div style="margin:20px 0;font-size:0.9rem;color:var(--text-muted);">
            <?php if ($showErrorModal) echo $modalMessage; ?>
        </div>
        <div class="modal-actions">
            <button onclick="closeErrorModal()" class="btn btn-primary">Try Again</button>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6);
    display:flex; align-items:center; justify-content:center; z-index:9999;
    backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px);
}
.modal-card {
    background:var(--white); border-radius:var(--radius-lg); padding:40px; max-width:480px;
    width:90%; text-align:center; box-shadow:var(--shadow-xl); position:relative;
}
.modal-icon {
    width:80px; height:80px; border-radius:50%; display:flex; align-items:center;
    justify-content:center; margin:0 auto 20px;
}
.modal-card h3 { font-family:var(--font-body); font-size:1.4rem; color:var(--navy); margin-bottom:8px; }
.modal-details {
    background:var(--cream); border-radius:var(--radius-md); padding:20px;
    margin-bottom:24px; text-align:left;
}
.modal-detail-row {
    display:flex; justify-content:space-between; padding:8px 0;
    border-bottom:1px solid var(--cream-dark); font-size:0.9rem;
}
.modal-detail-row:last-child { border-bottom:none; }
.modal-detail-row span { color:var(--text-muted); }
.modal-detail-row.total { padding-top:12px; margin-top:4px; border-top:2px solid var(--cream-dark); border-bottom:none; }
.modal-actions { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
</style>

<?php include 'includes/footer.php'; ?>
