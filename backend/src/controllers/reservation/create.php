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

$booked_from = trim((string)($body['booked_from'] ?? ''));
$booked_till = trim((string)($body['booked_till'] ?? ''));
$payment_method = trim((string)($body['payment_method'] ?? ''));
$room_id = (int)($body['room_id'] ?? 0);

$fields = [];

function is_valid_date(string $d): bool {
	$dt = DateTime::createFromFormat('Y-m-d', $d);
	return $dt && $dt->format('Y-m-d') === $d;
}

// Validate
if (!$booked_from || !is_valid_date($booked_from)) $fields['booked_from'] = 'Invalid date (YYYY-MM-DD)';
if (!$booked_till || !is_valid_date($booked_till)) $fields['booked_till'] = 'Invalid date (YYYY-MM-DD)';
if ($booked_from && $booked_till && $booked_till < $booked_from) $fields['booked_till'] = 'Must be after booked_from';
if ($room_id <= 0) $fields['room_id'] = 'Room is required';
if ($payment_method === '') $fields['payment_method'] = 'Payment method is required';

if ($fields) json_error('Validation failed', 422, ['fields' => $fields]);

// adjust if your DB uses another cancelled status id
const CANCELLED_STATUS_ID = 3;

try {
	// Overlap check
	$check = $pdo->prepare("
		SELECT COUNT(*) AS c
		FROM reservations
		WHERE room_id = :room_id
		  AND status_id <> :cancelled
		  AND booked_from <= :till
		  AND booked_till >= :from
	");
	$check->execute([
		'room_id' => $room_id,
		'cancelled' => CANCELLED_STATUS_ID,
		'from' => $booked_from,
		'till' => $booked_till
	]);

	$cnt = (int)($check->fetch()['c'] ?? 0);
	if ($cnt > 0) {
		json_error('Room not available for selected dates', 409, [
			'fields' => ['room_id' => 'Already booked in this date range']
		]);
	}

	// status_id is BIGINT → must be an int (NOT "pending")
	$insert = $pdo->prepare("
		INSERT INTO reservations (
			user_id, status_id, checked_in, checked_in_at, checked_out_at,
			booked_at, booked_from, booked_till, payment_method, room_id
		) VALUES (
			:user_id, :status_id, 0, NULL, NULL,
			NOW(), :booked_from, :booked_till, :payment_method, :room_id
		)
	");
	$insert->execute([
		'user_id' => $uid,
		'status_id' => 1,
		'booked_from' => $booked_from,
		'booked_till' => $booked_till,
		'payment_method' => $payment_method,
		'room_id' => $room_id
	]);

	json_ok(['ok' => true, 'id' => (int)$pdo->lastInsertId()], 201);
} catch (Throwable $e) {
	json_error('Server error', 500);
}
