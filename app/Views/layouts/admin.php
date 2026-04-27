<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Admin'); ?> | Admin Panel</title>
    <link rel="stylesheet" href="<?php echo asset('styles/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('styles/pages.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('styles/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="<?php echo asset('assets/images/logo.png'); ?>" alt="Logo">
                <div><h3 style="color:var(--white);">Dorado Salgado</h3><small>Admin Panel</small></div>
            </div>
            <nav>
                <a href="<?php echo base_url('admin/dashboard.php'); ?>" class="<?php echo ($activeAdmin ?? '') === 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="<?php echo base_url('admin/reservations.php'); ?>" class="<?php echo ($activeAdmin ?? '') === 'reservations' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Reservations</a>
                <a href="<?php echo base_url('admin/contacts.php'); ?>" class="<?php echo ($activeAdmin ?? '') === 'contacts' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Messages</a>
                <div class="sidebar-divider"></div>
                <a href="<?php echo base_url('index.php'); ?>"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?php echo base_url('admin/logout.php'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <?php echo $content; ?>
        </main>
    </div>
</body>
</html>
