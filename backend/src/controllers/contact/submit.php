<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	http_response_code(405);
	echo json_encode(['ok' => false, 'error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
	exit;
}

$body = read_json_body();

try {
	$pdo = db();

	$name = trim((string) ($body['name'] ?? ''));
	$email = trim((string) ($body['email'] ?? ''));
	$reason = trim((string) ($body['reason'] ?? ''));
	$title = trim((string) ($body['title'] ?? ''));
	$message = trim((string) ($body['message'] ?? ''));

	// DB timestamp (avoid ISO string in DB)
	$created_at = date('Y-m-d H:i:s');

	// Optional fields
	$userId = $body['user_id'] ?? null;
	$userId = ($userId === null || $userId === '') ? null : (int) $userId;

	// New/unhandled status id
	$statusId = 1;

	// Validation (match your varchar lengths)
	$errors = [];

	if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 50) {
		$errors['name'] = 'Name must be 2-50 characters.';
	}
	if ($email === '' || mb_strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = 'Invalid email (max 100 chars).';
	}
	if ($reason === '' || mb_strlen($reason) < 2 || mb_strlen($reason) > 100) {
		$errors['reason'] = 'Reason must be 2-100 characters.';
	}
	if ($title === '' || mb_strlen($title) < 2 || mb_strlen($title) > 50) {
		$errors['title'] = 'Title must be 2-50 characters.';
	}
	if ($message === '' || mb_strlen($message) < 10) {
		$errors['message'] = 'Message must be at least 10 characters.';
	}

	if (!empty($errors)) {
		http_response_code(422);
		echo json_encode([
			'ok' => false,
			'error' => 'Validation failed',
			'fields' => $errors
		], JSON_UNESCAPED_UNICODE);
		exit;
	}

	// ✅ FIX: include status_id + user_id
	$stmt = $pdo->prepare("
		INSERT INTO contact (name, email, reason, title, message, created_at, status_id, user_id)
		VALUES (:name, :email, :reason, :title, :message, :created_at, :status_id, :user_id)
	");

	$stmt->execute([
		':name' => $name,
		':email' => $email,
		':reason' => $reason,
		':title' => $title,
		':message' => $message,
		':created_at' => $created_at,
		':status_id' => $statusId,
		':user_id' => $userId,
	]);

	echo json_encode([
		'ok' => true,
		'id' => (int) $pdo->lastInsertId(),
	], JSON_UNESCAPED_UNICODE);
	exit;

} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode([
		'ok' => false,
		'error' => 'Internal server error',
		// zet deze tijdelijk aan als je de echte SQL error wilt zien:
		// 'debug' => $e->getMessage()
	], JSON_UNESCAPED_UNICODE);
	exit;
}
