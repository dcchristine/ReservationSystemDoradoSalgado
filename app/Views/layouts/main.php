<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dorado Salgado Grand Hotel - Experience luxury and comfort at its finest. Book your stay today.">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' | ' : ''; ?>Dorado Salgado Grand Hotel</title>
    <link rel="stylesheet" href="<?php echo asset('styles/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('styles/components.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('styles/pages.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('styles/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="<?php echo base_url('index.php'); ?>" class="navbar-brand">
                <img src="<?php echo asset('assets/images/logo.png'); ?>" alt="Dorado Salgado Grand Hotel Logo">
                <div class="brand-text">
                    <h1>Dorado Salgado</h1>
                    <span>Grand Hotel</span>
                </div>
            </a>
            <div class="nav-links" id="navLinks">
                <a href="<?php echo base_url('index.php'); ?>" class="<?php echo ($activePage ?? '') === 'home' ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo base_url('profile.php'); ?>" class="<?php echo ($activePage ?? '') === 'profile' ? 'active' : ''; ?>">Company Profile</a>
                <a href="<?php echo base_url('reservation.php'); ?>" class="<?php echo ($activePage ?? '') === 'reservation' ? 'active' : ''; ?>">Reservation</a>
                <a href="<?php echo base_url('contacts.php'); ?>" class="<?php echo ($activePage ?? '') === 'contacts' ? 'active' : ''; ?>">Contacts</a>

                <?php if (isset($_SESSION['guest_id'])): ?>
                    <a href="<?php echo base_url('my-bookings.php'); ?>" class="<?php echo ($activePage ?? '') === 'bookings' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> My Bookings</a>
                    <div class="nav-user-menu" style="display:flex;align-items:center;gap:8px;margin-left:10px;">
                        <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;color:var(--white);font-weight:600;font-size:0.8rem;">
                            <?php echo e(strtoupper(substr($_SESSION['guest_first_name'] ?? 'G', 0, 1))); ?>
                        </div>
                        <span style="color:rgba(255,255,255,0.8);font-size:0.85rem;"><?php echo e($_SESSION['guest_first_name'] ?? 'Guest'); ?></span>
                        <a href="<?php echo base_url('logout.php'); ?>" style="color:rgba(255,255,255,0.6);font-size:0.8rem;margin-left:4px;" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo base_url('login.php'); ?>" class="btn btn-primary btn-sm" style="margin-left:10px;"><i class="fas fa-user"></i> Get Started</a>
                <?php endif; ?>
            </div>
            <div class="hamburger" id="hamburger" onclick="toggleMenu()">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <?php echo $content; ?>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="<?php echo asset('assets/images/logo.png'); ?>" alt="Dorado Salgado Grand Hotel">
                    <p>Experience the pinnacle of luxury and hospitality at Dorado Salgado Grand Hotel. Where every moment is crafted for your comfort.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-tripadvisor"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo base_url('index.php'); ?>">Home</a></li>
                        <li><a href="<?php echo base_url('profile.php'); ?>">About Us</a></li>
                        <li><a href="<?php echo base_url('reservation.php'); ?>">Book a Room</a></li>
                        <li><a href="<?php echo base_url('contacts.php'); ?>">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Our Rooms</h4>
                    <ul>
                        <li><a href="<?php echo base_url('reservation.php'); ?>">Regular Room</a></li>
                        <li><a href="<?php echo base_url('reservation.php'); ?>">De Luxe Room</a></li>
                        <li><a href="<?php echo base_url('reservation.php'); ?>">Suite</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Luxury Avenue, Manila, Philippines</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+63 (2) 8888-7777</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@doradosalgado.com</span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Dorado Salgado Grand Hotel. All rights reserved.</p>
            </div>
        </div>
    </footer>

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
</body>
</html>
