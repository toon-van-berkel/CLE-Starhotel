<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/_helpers.php';

$uid = currentUserId();
if (!$uid) jsonResponse(['ok' => false, 'error' => 'Not logged in'], 401);

$body = readJsonBody();

$id = (int)($body['id'] ?? 0);
if ($id <= 0) jsonResponse(['ok' => false, 'error' => 'Invalid id'], 400);

// pas aan als jouw status ids anders zijn
const CANCELLED_STATUS_ID = 3;

try {
	$stmt = $pdo->prepare("
		UPDATE reservations
		SET status_id = :cancelled
		WHERE id = :id AND user_id = :uid
	");
	$stmt->execute(['cancelled' => CANCELLED_STATUS_ID, 'id' => $id, 'uid' => $uid]);

	jsonResponse(['ok' => true]);
} catch (Throwable $e) {
	jsonResponse(['ok' => false, 'error' => 'Server error'], 500);
}
