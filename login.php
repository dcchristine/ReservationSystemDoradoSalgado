<?php
session_start();
require_once 'includes/functions.php';

if (isGuestLoggedIn()) {
    header('Location: my-bookings.php');
    exit;
}

$error = '';
$tab = $_GET['tab'] ?? 'login';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $guest = guestLogin($email, $password);
    if ($guest) {
        $_SESSION['guest_id'] = $guest['id'];
        $_SESSION['guest_first_name'] = $guest['first_name'];
        $_SESSION['guest_last_name'] = $guest['last_name'];
        $_SESSION['guest_email'] = $guest['email'];
        $redirect = $_SESSION['redirect_after_login'] ?? 'my-bookings.php';
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Invalid email or password.';
        $tab = 'login';
    }
}

// Handle Register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
        $tab = 'register';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
        $tab = 'register';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
        $tab = 'register';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
        $tab = 'register';
    } elseif (guestEmailExists($email)) {
        $error = 'An account with this email already exists.';
        $tab = 'register';
    } else {
        if (registerGuest(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone' => $phone, 'password' => $password])) {
            $guest = guestLogin($email, $password);
            if ($guest) {
                $_SESSION['guest_id'] = $guest['id'];
                $_SESSION['guest_first_name'] = $guest['first_name'];
                $_SESSION['guest_last_name'] = $guest['last_name'];
                $_SESSION['guest_email'] = $guest['email'];
                $redirect = $_SESSION['redirect_after_login'] ?? 'my-bookings.php';
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $redirect);
                exit;
            }
        } else {
            $error = 'Registration failed. Please try again.';
            $tab = 'register';
        }
    }
}

$pageTitle = 'Login';
include 'includes/header.php';
?>

<section class="page-header">
    <h2>Welcome Back</h2>
    <div class="breadcrumb">
        <a href="index.php">Home</a> <span>/</span> <span>Login / Register</span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:500px;">

        <!-- Tab Buttons -->
        <div style="display:flex;gap:0;margin-bottom:30px;border-radius:var(--radius-sm);overflow:hidden;border:2px solid var(--cream-dark);">
            <button onclick="switchTab('login')" id="tabLogin" class="tab-btn" style="flex:1;padding:14px;border:none;cursor:pointer;font-family:var(--font-body);font-weight:600;font-size:0.95rem;transition:all 0.3s;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            <button onclick="switchTab('register')" id="tabRegister" class="tab-btn" style="flex:1;padding:14px;border:none;cursor:pointer;font-family:var(--font-body);font-weight:600;font-size:0.95rem;transition:all 0.3s;">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <div id="loginForm" class="auth-form-card" style="display:none;">
            <div style="text-align:center;margin-bottom:30px;">
                <img src="assets/images/logo.png" alt="Logo" style="height:60px;margin:0 auto 16px;border-radius:4px;">
                <h3 style="font-family:var(--font-body);font-size:1.3rem;color:var(--navy);">Sign In to Your Account</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;">Access your bookings and make reservations</p>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label><i class="fas fa-envelope" style="color:var(--gold);margin-right:6px;"></i> Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock" style="color:var(--gold);margin-right:6px;"></i> Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px;">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            <p style="text-align:center;margin-top:20px;font-size:0.9rem;color:var(--text-muted);">
                Don't have an account? <a href="#" onclick="switchTab('register');return false;" style="color:var(--gold);font-weight:600;">Register here</a>
            </p>
            <div style="text-align:center;margin-top:16px;padding-top:16px;border-top:1px solid var(--cream-dark);">
                <a href="admin/login.php" style="color:var(--text-light);font-size:0.8rem;"><i class="fas fa-shield-alt"></i> Admin Login</a>
            </div>
        </div>

        <!-- Register Form -->
        <div id="registerForm" class="auth-form-card" style="display:none;">
            <div style="text-align:center;margin-bottom:30px;">
                <img src="assets/images/logo.png" alt="Logo" style="height:60px;margin:0 auto 16px;border-radius:4px;">
                <h3 style="font-family:var(--font-body);font-size:1.3rem;color:var(--navy);">Create Your Account</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;">Join us for a seamless booking experience</p>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" name="first_name" class="form-control" placeholder="Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" name="last_name" class="form-control" placeholder="Dela Cruz" required>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope" style="color:var(--gold);margin-right:6px;"></i> Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone" style="color:var(--gold);margin-right:6px;"></i> Phone Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="09XX XXX XXXX">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock" style="color:var(--gold);margin-right:6px;"></i> Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required minlength="6">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock" style="color:var(--gold);margin-right:6px;"></i> Confirm Password <span class="required">*</span></label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px;">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
            <p style="text-align:center;margin-top:20px;font-size:0.9rem;color:var(--text-muted);">
                Already have an account? <a href="#" onclick="switchTab('login');return false;" style="color:var(--gold);font-weight:600;">Sign in here</a>
            </p>
        </div>

    </div>
</section>

<style>
.auth-form-card {
    background:var(--white); border-radius:var(--radius-lg); padding:40px;
    box-shadow:var(--shadow-md); animation:fadeInUp 0.4s ease;
}
.tab-btn.active {
    background:linear-gradient(135deg,var(--gold),var(--gold-dark)) !important;
    color:var(--white) !important;
}
.tab-btn {
    background:var(--white);
    color:var(--text-muted);
}
</style>

<script>
function switchTab(tab) {
    document.getElementById('loginForm').style.display = tab === 'login' ? 'block' : 'none';
    document.getElementById('registerForm').style.display = tab === 'register' ? 'block' : 'none';
    document.getElementById('tabLogin').classList.toggle('active', tab === 'login');
    document.getElementById('tabRegister').classList.toggle('active', tab === 'register');
}
switchTab('<?php echo $tab; ?>');
</script>

<?php include 'includes/footer.php'; ?>
