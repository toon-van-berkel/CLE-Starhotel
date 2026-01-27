<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/_helpers.php';

$uid = currentUserId();
if (!$uid) jsonResponse(['ok' => false, 'error' => 'Not logged in'], 401);

$body = readJsonBody();

$id = (int)($body['id'] ?? 0);
if ($id <= 0) jsonResponse(['ok' => false, 'error' => 'Invalid id'], 400);

// allowed user-facing fields
$allowed = ['booked_from', 'booked_till', 'payment_method', 'room_id'];

$set = [];
$params = ['id' => $id, 'uid' => $uid];

// optional extra flags
$fields = [];

// basic validation if provided
if (isset($body['booked_from'])) {
	$bf = trim((string)$body['booked_from']);
	if (!$bf || !isValidDate($bf)) $fields['booked_from'] = 'Invalid date (YYYY-MM-DD)';
}
if (isset($body['booked_till'])) {
	$bt = trim((string)$body['booked_till']);
	if (!$bt || !isValidDate($bt)) $fields['booked_till'] = 'Invalid date (YYYY-MM-DD)';
}
if (!$fields && isset($body['booked_from'], $body['booked_till'])) {
	$bf = (string)$body['booked_from'];
	$bt = (string)$body['booked_till'];
	if ($bt < $bf) $fields['booked_till'] = 'Must be after booked_from';
}

if ($fields) {
	jsonResponse(['ok' => false, 'error' => 'Validation failed', 'fields' => $fields], 422);
}

foreach ($allowed as $k) {
	if (array_key_exists($k, $body)) {
		$set[] = "$k = :$k";
		$params[$k] = ($k === 'room_id') ? (int)$body[$k] : (string)$body[$k];
	}
}

if (!$set) jsonResponse(['ok' => false, 'error' => 'Nothing to update'], 400);

// pas aan als jouw status ids anders zijn
const CANCELLED_STATUS_ID = 3;

try {
	// Optional: if dates/room changed -> re-check availability
	// We'll do a safe re-check if any of these fields exist:
	$needsCheck = array_key_exists('room_id', $body) || array_key_exists('booked_from', $body) || array_key_exists('booked_till', $body);

	if ($needsCheck) {
		// get current record first
		$cur = $pdo->prepare("SELECT room_id, booked_from, booked_till, status_id FROM reservations WHERE id = :id AND user_id = :uid LIMIT 1");
		$cur->execute(['id' => $id, 'uid' => $uid]);
		$existing = $cur->fetch(PDO::FETCH_ASSOC) ?: null;

		if (!$existing) jsonResponse(['ok' => false, 'error' => 'Not found'], 404);

		$newRoom = array_key_exists('room_id', $body) ? (int)$body['room_id'] : (int)$existing['room_id'];
		$newFrom = array_key_exists('booked_from', $body) ? (string)$body['booked_from'] : (string)$existing['booked_from'];
		$newTill = array_key_exists('booked_till', $body) ? (string)$body['booked_till'] : (string)$existing['booked_till'];
		$statusId = (int)$existing['status_id'];

		// don't check if cancelled
		if ($statusId !== CANCELLED_STATUS_ID) {
			$check = $pdo->prepare("
				SELECT COUNT(*) AS c
				FROM reservations
				WHERE room_id = :room_id
				  AND status_id <> :cancelled
				  AND id <> :id
				  AND booked_from <= :till
				  AND booked_till >= :from
			");
			$check->execute([
				'room_id' => $newRoom,
				'cancelled' => CANCELLED_STATUS_ID,
				'id' => $id,
				'from' => $newFrom,
				'till' => $newTill
			]);
			$cnt = (int)($check->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);

			if ($cnt > 0) {
				jsonResponse([
					'ok' => false,
					'error' => 'Room not available for selected dates',
					'fields' => ['room_id' => 'Already booked in this date range']
				], 409);
			}
		}
	}

	$upd = $pdo->prepare("
		UPDATE reservations
		SET " . implode(", ", $set) . "
		WHERE id = :id AND user_id = :uid
	");
	$upd->execute($params);

	// return updated record + user
	$get = $pdo->prepare("
		SELECT
			r.id, r.user_id, r.status_id, r.checked_in, r.checked_in_at, r.checked_out_at,
			r.booked_at, r.booked_from, r.booked_till, r.payment_method, r.room_id,
			u.id AS u_id, u.first_name AS u_first_name, u.last_name AS u_last_name, u.email AS u_email, u.phone AS u_phone
		FROM reservations r
		JOIN users u ON u.id = r.user_id
		WHERE r.id = :id AND r.user_id = :uid
		LIMIT 1
	");
	$get->execute(['id' => $id, 'uid' => $uid]);
	$row = $get->fetch(PDO::FETCH_ASSOC) ?: null;

	if (!$row) jsonResponse(['ok' => false, 'error' => 'Not found'], 404);

	$record = normalizeReservationRow($row);
	jsonResponse(['ok' => true, 'record' => $record]);
} catch (Throwable $e) {
	jsonResponse(['ok' => false, 'error' => 'Server error'], 500);
}
