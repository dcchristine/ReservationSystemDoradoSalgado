<?php
require_once __DIR__ . '/../app/bootstrap.php';

$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);
$db = Database::connection();

$stmt = $db->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
$updated = $stmt->execute([$hash]);

if ($updated && $stmt->rowCount() > 0) {
    echo 'Admin password updated successfully!<br>';
} else {
    $stmt = $db->prepare(
        "INSERT INTO admin_users (username, password, full_name)
         VALUES ('admin', ?, 'System Administrator')
         ON DUPLICATE KEY UPDATE password = VALUES(password)"
    );
    $stmt->execute([$hash]);
    echo 'Admin user created or refreshed successfully!<br>';
}

echo 'Username: admin<br>';
echo 'Password: admin123<br>';
echo '<br><a href="' . e(base_url('admin/login.php')) . '">Go to Admin Login</a>';
