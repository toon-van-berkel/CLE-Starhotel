<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

// Must be logged in (admin check optional)
$user = require_login();

// OPTIONAL: Add an admin check if you have roles
// Example if your session user has "role_ids":
// if (!in_array(1, (array)($user['role_ids'] ?? []), true)) json_error('Forbidden', 403);

// Ensure PDO exists
if (!isset($pdo) || !($pdo instanceof PDO)) {
    json_error('Database connection missing ($pdo)', 500);
}

try {
    $stmt = $pdo->query("
        SELECT
            r.id, r.user_id, r.status_id, r.checked_in, r.checked_in_at, r.checked_out_at,
            r.booked_at, r.booked_from, r.booked_till, r.payment_method, r.room_id,
            u.id AS u_id, u.first_name AS u_first_name, u.last_name AS u_last_name,
            u.email AS u_email, u.phone AS u_phone
        FROM reservations r
        JOIN users u ON u.id = r.user_id
        ORDER BY r.booked_at DESC
    ");

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    foreach ($records as &$r) {
        // Ensure correct types
        $r['id'] = (int)$r['id'];
        $r['user_id'] = (int)$r['user_id'];
        $r['status_id'] = (int)$r['status_id'];
        $r['checked_in'] = (int)$r['checked_in'] ? 1 : 0;
        $r['room_id'] = (int)$r['room_id'];

        // frontend expects this
        $r['room_ids'] = [(int)$r['room_id']];

        // attach user object
        $r['user'] = [
            'id' => (int)$r['u_id'],
            'first_name' => (string)$r['u_first_name'],
            'last_name' => (string)$r['u_last_name'],
            'email' => (string)$r['u_email'],
            'phone' => (string)$r['u_phone'],
        ];

        // remove join columns
        unset($r['u_id'], $r['u_first_name'], $r['u_last_name'], $r['u_email'], $r['u_phone']);
    }

    json_ok(['records' => $records]);
} catch (Throwable $e) {
    json_error('Server error', 500);
}
