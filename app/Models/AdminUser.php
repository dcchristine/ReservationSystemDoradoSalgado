<?php

class AdminUser
{
    public static function authenticate(string $username, string $password): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM admin_users WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return null;
    }
}
