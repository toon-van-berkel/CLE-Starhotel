<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/_helpers.php';

$uid = currentUserId();
if (!$uid) jsonResponse(['record' => null, 'error' => 'Not logged in'], 401);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) jsonResponse(['record' => null, 'error' => 'Invalid id'], 400);

try {
	$stmt = $pdo->prepare("
		SELECT
			r.id, r.user_id, r.status_id, r.checked_in, r.checked_in_at, r.checked_out_at,
			r.booked_at, r.booked_from, r.booked_till, r.payment_method, r.room_id,
			u.id AS u_id, u.first_name AS u_first_name, u.last_name AS u_last_name, u.email AS u_email, u.phone AS u_phone
		FROM reservations r
		JOIN users u ON u.id = r.user_id
		WHERE r.id = :id AND r.user_id = :uid
		LIMIT 1
	");
	$stmt->execute(['id' => $id, 'uid' => $uid]);

	$row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
	$record = $row ? normalizeReservationRow($row) : null;

	jsonResponse(['record' => $record]);
} catch (Throwable $e) {
	jsonResponse(['record' => null, 'error' => 'Server error'], 500);
}
