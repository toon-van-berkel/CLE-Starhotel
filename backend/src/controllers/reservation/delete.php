<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

// login required
$user = require_login();
$uid = (int)($user['id'] ?? 0);
if ($uid <= 0) json_error('Unauthorized', 401);

// db required
if (!isset($pdo) || !($pdo instanceof PDO)) {
	json_error('Database connection missing ($pdo)', 500);
}

$body = read_json_body();
$id = (int)($body['id'] ?? 0);

if ($id <= 0) {
	json_error('Invalid id', 422, ['fields' => ['id' => 'Reservation id is required']]);
}

try {
	// Check existence (optional but gives better errors)
	$stmt = $pdo->prepare("SELECT id FROM reservations WHERE id = :id LIMIT 1");
	$stmt->execute(['id' => $id]);
	$r = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$r) {
		json_error('Reservation not found', 404);
	}

	// HARD DELETE
	$del = $pdo->prepare("DELETE FROM reservations WHERE id = :id");
	$del->execute(['id' => $id]);

	if ($del->rowCount() < 1) {
		json_error('Delete failed', 500);
	}

	json_ok(['ok' => true, 'deleted_id' => $id]);
} catch (Throwable $e) {
	json_error('Server error', 500);
}
