<?php

class Reservation
{
    public static function calculatePrice(float $roomPrice, int $numDays, string $paymentType): array
    {
        $basePrice = $roomPrice * $numDays;
        $discountPercent = 0;
        $additionalChargePercent = 0;

        if ($paymentType === 'Cash') {
            if ($numDays >= 6) {
                $discountPercent = 15;
            } elseif ($numDays >= 3) {
                $discountPercent = 10;
            }
        }

        if ($paymentType === 'Check') {
            $additionalChargePercent = 5;
        } elseif ($paymentType === 'Credit Card') {
            $additionalChargePercent = 10;
        }

        $discount = $basePrice * ($discountPercent / 100);
        $additionalCharge = $basePrice * ($additionalChargePercent / 100);

        return [
            'base_price' => $basePrice,
            'discount_percent' => $discountPercent,
            'additional_charge_percent' => $additionalChargePercent,
            'discount_amount' => $discount,
            'additional_charge_amount' => $additionalCharge,
            'total_price' => $basePrice - $discount + $additionalCharge,
        ];
    }

    public static function create(array $data): bool
    {
        $stmt = Database::connection()->prepare(
            "INSERT INTO reservations
             (guest_user_id, guest_name, email, phone, room_id, check_in, check_out, guests,
              num_days, payment_type, special_requests, base_price, discount_percent,
              additional_charge_percent, total_price, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')"
        );

        return $stmt->execute([
            $data['guest_user_id'] ?? null,
            $data['guest_name'],
            $data['email'],
            $data['phone'],
            $data['room_id'],
            $data['check_in'],
            $data['check_out'],
            $data['guests'],
            $data['num_days'],
            $data['payment_type'],
            $data['special_requests'] ?? null,
            $data['base_price'],
            $data['discount_percent'],
            $data['additional_charge_percent'],
            $data['total_price'],
        ]);
    }

    public static function all(string $search = '', string $status = ''): array
    {
        $sql = "SELECT r.*, rm.name AS room_name, rm.capacity_type, rm.room_type, rm.price AS room_rate
                FROM reservations r
                JOIN rooms rm ON r.room_id = rm.id
                WHERE 1=1";
        $params = [];

        if ($search !== '') {
            $sql .= ' AND (r.guest_name LIKE ? OR r.email LIKE ?)';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        if ($status !== '') {
            $sql .= ' AND r.status = ?';
            $params[] = $status;
        }

        $sql .= ' ORDER BY r.created_at DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            "SELECT r.*, rm.name AS room_name, rm.price AS room_price, rm.capacity_type, rm.room_type
             FROM reservations r
             JOIN rooms rm ON r.room_id = rm.id
             WHERE r.id = ?"
        );
        $stmt->execute([$id]);
        $reservation = $stmt->fetch();

        return $reservation ?: null;
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare(
            "UPDATE reservations
             SET guest_name=?, email=?, phone=?, room_id=?, check_in=?, check_out=?, guests=?,
                 num_days=?, payment_type=?, special_requests=?, base_price=?, discount_percent=?,
                 additional_charge_percent=?, total_price=?, status=?
             WHERE id=?"
        );

        return $stmt->execute([
            $data['guest_name'],
            $data['email'],
            $data['phone'],
            $data['room_id'],
            $data['check_in'],
            $data['check_out'],
            $data['guests'],
            $data['num_days'],
            $data['payment_type'],
            $data['special_requests'] ?? null,
            $data['base_price'],
            $data['discount_percent'],
            $data['additional_charge_percent'],
            $data['total_price'],
            $data['status'],
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM reservations WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function stats(): array
    {
        $db = Database::connection();

        return [
            'total' => $db->query('SELECT COUNT(*) FROM reservations')->fetchColumn(),
            'pending' => $db->query("SELECT COUNT(*) FROM reservations WHERE status='Pending'")->fetchColumn(),
            'confirmed' => $db->query("SELECT COUNT(*) FROM reservations WHERE status='Confirmed'")->fetchColumn(),
            'revenue' => $db->query("SELECT COALESCE(SUM(total_price),0) FROM reservations WHERE status IN ('Confirmed','Completed')")->fetchColumn(),
        ];
    }

    public static function forGuest(int $guestId): array
    {
        $stmt = Database::connection()->prepare(
            "SELECT r.*, rm.name AS room_name, rm.capacity_type, rm.room_type, rm.price AS room_rate
             FROM reservations r
             JOIN rooms rm ON r.room_id = rm.id
             WHERE r.guest_user_id = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->execute([$guestId]);

        return $stmt->fetchAll();
    }

    public static function cancelForGuest(int $reservationId, int $guestId): bool
    {
        $stmt = Database::connection()->prepare(
            "UPDATE reservations
             SET status = 'Cancelled'
             WHERE id = ?
               AND guest_user_id = ?
               AND status != 'Cancelled'
               AND check_in > CURDATE()"
        );

        return $stmt->execute([$reservationId, $guestId]);
    }
}
