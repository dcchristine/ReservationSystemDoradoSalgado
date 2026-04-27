<div class="admin-login-page">
    <div class="admin-login-card fade-in-up">
        <img src="<?php echo asset('assets/images/logo.png'); ?>" alt="Hotel Logo">
        <h2>Admin Panel</h2>
        <p class="login-subtitle">Sign in to manage reservations</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo e($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo base_url('admin/login.php'); ?>">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:10px;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        <p style="margin-top:24px; font-size:0.85rem; color:var(--text-muted);">
            <a href="<?php echo base_url('index.php'); ?>" style="color:var(--gold);"><i class="fas fa-arrow-left"></i> Back to Website</a>
        </p>
    </div>
</div>
