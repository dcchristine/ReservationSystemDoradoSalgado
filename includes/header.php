<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dorado Salgado Grand Hotel - Experience luxury and comfort at its finest. Book your stay today.">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?>Dorado Salgado Grand Hotel</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/components.css">
    <link rel="stylesheet" href="styles/pages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <img src="assets/images/logo.png" alt="Dorado Salgado Grand Hotel Logo">
                <div class="brand-text">
                    <h1>Dorado Salgado</h1>
                    <span>Grand Hotel</span>
                </div>
            </a>
            <div class="nav-links" id="navLinks">
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a>
                <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">Company Profile</a>
                <a href="reservation.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'active' : ''; ?>">Reservation</a>
                <a href="contacts.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contacts.php' ? 'active' : ''; ?>">Contacts</a>

                <?php if (isset($_SESSION['guest_id'])): ?>
                    <a href="my-bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'my-bookings.php' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> My Bookings</a>
                    <div class="nav-user-menu" style="display:flex;align-items:center;gap:8px;margin-left:10px;">
                        <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;color:var(--white);font-weight:600;font-size:0.8rem;">
                            <?php echo strtoupper(substr($_SESSION['guest_first_name'] ?? 'G', 0, 1)); ?>
                        </div>
                        <span style="color:rgba(255,255,255,0.8);font-size:0.85rem;"><?php echo htmlspecialchars($_SESSION['guest_first_name'] ?? 'Guest'); ?></span>
                        <a href="logout.php" style="color:rgba(255,255,255,0.6);font-size:0.8rem;margin-left:4px;" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-sm" style="margin-left:10px;"><i class="fas fa-user"></i> Get Started</a>
                <?php endif; ?>
            </div>
            <div class="hamburger" id="hamburger" onclick="toggleMenu()">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('open');
            document.getElementById('hamburger').classList.toggle('active');
        }
    </script>
