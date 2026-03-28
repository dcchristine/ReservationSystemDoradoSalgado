<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$contacts = getAllContacts();

// Mark as read
if (isset($_GET['mark_read'])) {
    markContactRead(intval($_GET['mark_read']));
    header('Location: contacts.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Admin Panel</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/pages.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../assets/images/logo.png" alt="Logo">
                <div><h3 style="color:var(--white);">Dorado Salgado</h3><small>Admin Panel</small></div>
            </div>
            <nav>
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="reservations.php"><i class="fas fa-calendar-alt"></i> Reservations</a>
                <a href="contacts.php" class="active"><i class="fas fa-envelope"></i> Messages</a>
                <div class="sidebar-divider"></div>
                <a href="../index.php"><i class="fas fa-globe"></i> View Website</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h2>Contact Messages</h2>
                <div class="admin-user">
                    <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
                    <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h3>Messages (<?php echo count($contacts); ?>)</h3>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contacts)): ?>
                            <tr><td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">No messages yet</td></tr>
                        <?php else: ?>
                            <?php foreach ($contacts as $c): ?>
                            <tr>
                                <td><?php echo $c['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($c['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($c['email']); ?></td>
                                <td><?php echo htmlspecialchars($c['subject'] ?: '-'); ?></td>
                                <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo htmlspecialchars($c['message']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($c['created_at'])); ?></td>
                                <td><span class="badge badge-<?php echo $c['is_read'] ? 'read' : 'unread'; ?>"><?php echo $c['is_read'] ? 'Read' : 'Unread'; ?></span></td>
                                <td>
                                    <?php if (!$c['is_read']): ?>
                                        <a href="contacts.php?mark_read=<?php echo $c['id']; ?>" class="btn-edit"><i class="fas fa-check"></i> Mark Read</a>
                                    <?php else: ?>
                                        <span style="color:var(--text-light);font-size:0.8rem;">Read</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
