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
	$stmt = $pdo->prepare('DELETE FROM contact WHERE id = :id');
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

	if ($stmt->rowCount() === 0) {
		http_response_code(404);
		echo json_encode(['ok' => false, 'error' => 'Contact not found'], JSON_UNESCAPED_UNICODE);
		exit;
	}

	echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
	http_response_code(500);
	echo json_encode(['ok' => false, 'error' => 'Internal server error'], JSON_UNESCAPED_UNICODE);
}
