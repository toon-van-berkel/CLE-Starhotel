<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	http_response_code(405);
	echo json_encode(['ok' => false, 'error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
	exit;
}

try {
	// Read JSON body
	if (function_exists('read_json_body')) {
		$body = read_json_body();
	} else {
		$raw = file_get_contents('php://input') ?: '';
		$body = json_decode($raw, true);
		if (!is_array($body)) $body = [];
	}

	$id = isset($body['id']) ? (int)$body['id'] : 0;
	if ($id <= 0) {
		http_response_code(400);
		echo json_encode(['ok' => false, 'error' => 'Missing or invalid id'], JSON_UNESCAPED_UNICODE);
		exit;
	}

	$pdo = db();

	// Load existing record
	$stmt = $pdo->prepare('SELECT * FROM contact WHERE id = :id');
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$existing = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$existing) {
		http_response_code(404);
		echo json_encode(['ok' => false, 'error' => 'Contact not found'], JSON_UNESCAPED_UNICODE);
		exit;
	}

	// Merge fields (partial update)
	$name    = array_key_exists('name', $body)    ? trim((string)$body['name'])    : (string)$existing['name'];
	$email   = array_key_exists('email', $body)   ? trim((string)$body['email'])   : (string)$existing['email'];
	$reason  = array_key_exists('reason', $body)  ? trim((string)$body['reason'])  : (string)$existing['reason'];
	$title   = array_key_exists('title', $body)   ? trim((string)$body['title'])   : (string)$existing['title'];
	$message = array_key_exists('message', $body) ? trim((string)$body['message']) : (string)$existing['message'];

	$statusId = array_key_exists('status_id', $body) ? (int)$body['status_id'] : (int)$existing['status_id'];

	$adminHandledId = array_key_exists('admin_handled_id', $body) ? $body['admin_handled_id'] : $existing['admin_handled_id'];
	$adminHandledId = ($adminHandledId === null || $adminHandledId === '' ? null : (int)$adminHandledId);

	$userId = array_key_exists('user_id', $body) ? $body['user_id'] : $existing['user_id'];
	$userId = ($userId === null || $userId === '' ? null : (int)$userId);

	// handled_at: send handled_at:true to set NOW(), handled_at:null to clear, omit to keep
	$handledAt = $existing['handled_at'];
	$handledNow = false;

	if (array_key_exists('handled_at', $body)) {
		if ($body['handled_at'] === true) {
			$handledNow = true;
		} elseif ($body['handled_at'] === null || $body['handled_at'] === '') {
			$handledAt = null;
		} else {
			$handledAt = (string)$body['handled_at'];
		}
	}

	// Validation (same as submit.php)
	$errors = [];

	if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 50) $errors['name'] = 'Name must be 2-50 characters.';
	if ($email === '' || mb_strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email (max 100 chars).';
	if ($reason === '' || mb_strlen($reason) < 2 || mb_strlen($reason) > 100) $errors['reason'] = 'Reason must be 2-100 characters.';
	if ($title === '' || mb_strlen($title) < 2 || mb_strlen($title) > 50) $errors['title'] = 'Title must be 2-50 characters.';
	if ($message === '' || mb_strlen($message) < 10) $errors['message'] = 'Message must be at least 10 characters.';

	if (!empty($errors)) {
		http_response_code(422);
		echo json_encode(['ok' => false, 'error' => 'Validation failed', 'fields' => $errors], JSON_UNESCAPED_UNICODE);
		exit;
	}

	$sql = "
		UPDATE contact SET
			name = :name,
			email = :email,
			reason = :reason,
			title = :title,
			message = :message,
			status_id = :status_id,
			admin_handled_id = :admin_handled_id,
			user_id = :user_id,
			handled_at = " . ($handledNow ? "NOW()" : ":handled_at") . "
		WHERE id = :id
	";

	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->bindValue(':name', $name, PDO::PARAM_STR);
	$stmt->bindValue(':email', $email, PDO::PARAM_STR);
	$stmt->bindValue(':reason', $reason, PDO::PARAM_STR);
	$stmt->bindValue(':title', $title, PDO::PARAM_STR);
	$stmt->bindValue(':message', $message, PDO::PARAM_STR);
	$stmt->bindValue(':status_id', $statusId, PDO::PARAM_INT);

	if ($adminHandledId === null) $stmt->bindValue(':admin_handled_id', null, PDO::PARAM_NULL);
	else $stmt->bindValue(':admin_handled_id', $adminHandledId, PDO::PARAM_INT);

	if ($userId === null) $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
	else $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

	if (!$handledNow) {
		if ($handledAt === null) $stmt->bindValue(':handled_at', null, PDO::PARAM_NULL);
		else $stmt->bindValue(':handled_at', $handledAt, PDO::PARAM_STR);
	}

	$stmt->execute();

	// Return updated record
	$stmt = $pdo->prepare('SELECT * FROM contact WHERE id = :id');
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$record = $stmt->fetch(PDO::FETCH_ASSOC);

	echo json_encode(['ok' => true, 'record' => $record], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode(['ok' => false, 'error' => 'Internal server error'], JSON_UNESCAPED_UNICODE);
}
