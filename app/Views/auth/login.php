<section class="page-header">
    <h2>Welcome Back</h2>
    <div class="breadcrumb">
        <a href="<?php echo base_url('index.php'); ?>">Home</a> <span>/</span> <span>Login / Register</span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:500px;">
        <div style="display:flex;gap:0;margin-bottom:30px;border-radius:var(--radius-sm);overflow:hidden;border:2px solid var(--cream-dark);">
            <button onclick="switchTab('login')" id="tabLogin" class="tab-btn" style="flex:1;padding:14px;border:none;cursor:pointer;font-family:var(--font-body);font-weight:600;font-size:0.95rem;transition:all 0.3s;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            <button onclick="switchTab('register')" id="tabRegister" class="tab-btn" style="flex:1;padding:14px;border:none;cursor:pointer;font-family:var(--font-body);font-weight:600;font-size:0.95rem;transition:all 0.3s;">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo e($error); ?></div>
        <?php endif; ?>

        <div id="loginForm" class="auth-form-card" style="display:none;">
            <div style="text-align:center;margin-bottom:30px;">
                <img src="<?php echo asset('assets/images/logo.png'); ?>" alt="Logo" style="height:60px;margin:0 auto 16px;border-radius:4px;">
                <h3 style="font-family:var(--font-body);font-size:1.3rem;color:var(--navy);">Sign In to Your Account</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;">Access your bookings and make reservations</p>
            </div>
            <form method="POST" action="<?php echo base_url('login.php'); ?>">
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
                <a href="<?php echo base_url('admin/login.php'); ?>" style="color:var(--text-light);font-size:0.8rem;"><i class="fas fa-shield-alt"></i> Admin Login</a>
            </div>
        </div>

        <div id="registerForm" class="auth-form-card" style="display:none;">
            <div style="text-align:center;margin-bottom:30px;">
                <img src="<?php echo asset('assets/images/logo.png'); ?>" alt="Logo" style="height:60px;margin:0 auto 16px;border-radius:4px;">
                <h3 style="font-family:var(--font-body);font-size:1.3rem;color:var(--navy);">Create Your Account</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;">Join us for a seamless booking experience</p>
            </div>
            <form method="POST" action="<?php echo base_url('login.php'); ?>">
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
switchTab('<?php echo e($tab); ?>');
</script>
