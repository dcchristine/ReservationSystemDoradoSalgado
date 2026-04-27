<?php

class Guest
{
    public static function create(array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO guests (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)'
        );

        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            password_hash($data['password'], PASSWORD_BCRYPT),
        ]);
    }

    public static function authenticate(string $email, string $password): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM guests WHERE email = ?');
        $stmt->execute([$email]);
        $guest = $stmt->fetch();

        if ($guest && password_verify($password, $guest['password'])) {
            return $guest;
        }

        return null;
    }

    public static function emailExists(string $email): bool
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM guests WHERE email = ?');
        $stmt->execute([$email]);

        return (int) $stmt->fetchColumn() > 0;
    }
}
