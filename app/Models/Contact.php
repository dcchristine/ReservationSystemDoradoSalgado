<?php

class Contact
{
    public static function create(array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)'
        );

        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['subject'],
            $data['message'],
        ]);
    }

    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM contacts ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function markRead(int $id): bool
    {
        $stmt = Database::connection()->prepare('UPDATE contacts SET is_read = 1 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
