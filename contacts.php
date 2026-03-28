<?php
$pageTitle = 'Contact Us';
require_once 'includes/functions.php';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        if (saveContactMessage(['name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message])) {
            $_SESSION['success'] = 'Your message has been sent successfully! We will get back to you shortly.';
        } else {
            $_SESSION['error'] = 'Failed to send message. Please try again.';
        }
    } else {
        $_SESSION['error'] = 'Please fill in all required fields.';
    }
    header('Location: contacts.php');
    exit;
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <h2>Contact Us</h2>
    <div class="breadcrumb">
        <a href="index.php">Home</a> <span>/</span> <span>Contacts</span>
    </div>
</section>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info-cards">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4>Our Location</h4>
                        <p>123 Luxury Avenue, Makati City<br>Metro Manila, Philippines 1200</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <h4>Phone Numbers</h4>
                        <p>Reservations: +63 (2) 8888-7777<br>Front Desk: +63 (2) 8888-7778</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h4>Email Address</h4>
                        <p>info@doradosalgado.com<br>reservations@doradosalgado.com</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <h4>Business Hours</h4>
                        <p>Front Desk: 24/7<br>Reservations: Mon-Sun 6AM-10PM</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-card">
                <h3>Send Us a Message</h3>
                <p class="form-subtitle">Have questions or feedback? We'd love to hear from you.</p>
                <form method="POST" action="contacts.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Your Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="How can we help?">
                    </div>
                    <div class="form-group">
                        <label>Message <span class="required">*</span></label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Write your message here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Message</button>
                </form>
            </div>
        </div>

        <!-- Map -->
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.802694380484!2d121.01389901536467!3d14.554729089829413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c90264a0ed01%3A0x2b066ed57830cace!2sMakati%2C%20Metro%20Manila%2C%20Philippines!5e0!3m2!1sen!2sus!4v1680000000000!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
