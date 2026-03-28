<?php
// Generate correct bcrypt hash for admin123 and update the database
require_once __DIR__ . '/config/database.php';

$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

// Update the admin password
$stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
$result = $stmt->execute([$hash]);

if ($result) {
    echo "Admin password updated successfully!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<br><a href='admin/login.php'>Go to Admin Login</a>";
} else {
    echo "Failed to update. Inserting new admin user...<br>";
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, full_name) VALUES ('admin', ?, 'System Administrator')");
    $stmt->execute([$hash]);
    echo "Admin user created!<br>";
    echo "<a href='admin/login.php'>Go to Admin Login</a>";
}
?>
