<?php

class Room
{
    public static function all(): array
    {
        $stmt = Database::connection()->query(
            "SELECT * FROM rooms
             ORDER BY FIELD(capacity_type,'Single','Double','Family'),
                      FIELD(room_type,'Regular','De Luxe','Suite')"
        );

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM rooms WHERE id = ?');
        $stmt->execute([$id]);
        $room = $stmt->fetch();

        return $room ?: null;
    }

    public static function groupedByCapacity(): array
    {
        $grouped = [];
        foreach (self::all() as $room) {
            $grouped[$room['capacity_type']][] = $room;
        }

        return $grouped;
    }
}
