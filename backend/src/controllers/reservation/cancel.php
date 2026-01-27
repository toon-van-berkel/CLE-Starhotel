<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

$user = require_login();
$uid = (int)($user['id'] ?? 0);
if ($uid <= 0) json_error('Unauthorized', 401);

if (!isset($pdo) || !($pdo instanceof PDO)) {
	json_error('Database connection missing ($pdo)', 500);
}

$body = read_json_body();
$id = (int)($body['id'] ?? 0);

if ($id <= 0) {
	json_error('Invalid id', 422, ['fields' => ['id' => 'Reservation id is required']]);
}

// ✅ adjust if your cancelled status is different
const CANCELLED_STATUS_ID = 51;

try {
	// Load reservation
	$stmt = $pdo->prepare("
		SELECT id, user_id, status_id, checked_in
		FROM reservations
		WHERE id = :id
		LIMIT 1
	");
	$stmt->execute(['id' => $id]);
	$r = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$r) {
		json_error('Reservation not found', 404);
	}

	// Owner-only cancel (change to admin/owner later if you want)
	if ((int)$r['user_id'] !== $uid) {
		json_error('Forbidden', 403);
	}

	// Optional: do not allow cancel after check-in
	if ((int)$r['checked_in'] === 1) {
		json_error('Cannot cancel: already checked in', 409);
	}

	// Idempotent
	if ((int)$r['status_id'] === CANCELLED_STATUS_ID) {
		json_ok(['ok' => true, 'cancelled_id' => $id]);
	}

	$upd = $pdo->prepare("
		UPDATE reservations
		SET status_id = :cancelled
		WHERE id = :id
	");
	$upd->execute([
		'cancelled' => CANCELLED_STATUS_ID,
		'id' => $id
	]);

	json_ok(['ok' => true, 'cancelled_id' => $id]);
} catch (Throwable $e) {
	json_error('Server error', 500);
}
