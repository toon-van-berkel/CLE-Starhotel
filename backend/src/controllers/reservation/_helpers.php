<?php
declare(strict_types=1);

function jsonResponse(array $data, int $status = 200): void {
	http_response_code($status);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data);
	exit;
}

function readJsonBody(): array {
	$raw = file_get_contents('php://input');
	if (!$raw) return [];
	$data = json_decode($raw, true);
	return is_array($data) ? $data : [];
}

function currentUserId(): ?int {
	if (session_status() !== PHP_SESSION_ACTIVE) {
		@session_start();
	}

	// common patterns
	if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['id'])) {
		return (int) $_SESSION['user']['id'];
	}
	if (isset($_SESSION['user_id'])) {
		return (int) $_SESSION['user_id'];
	}

	return null;
}

function isValidDate(string $d): bool {
	$dt = DateTime::createFromFormat('Y-m-d', $d);
	return $dt && $dt->format('Y-m-d') === $d;
}

function normalizeReservationRow(array $r): array {
	$r['id'] = (int)$r['id'];
	$r['user_id'] = (int)$r['user_id'];
	$r['status_id'] = (int)$r['status_id'];
	$r['checked_in'] = (int)$r['checked_in'] ? 1 : 0;
	$r['room_id'] = (int)$r['room_id'];
	$r['room_ids'] = [(int)$r['room_id']];

	$r['user'] = [
		'id' => (int)$r['u_id'],
		'first_name' => (string)$r['u_first_name'],
		'last_name' => (string)$r['u_last_name'],
		'email' => (string)$r['u_email'],
		'phone' => (string)($r['u_phone'] ?? ''),
	];

	unset($r['u_id'], $r['u_first_name'], $r['u_last_name'], $r['u_email'], $r['u_phone']);
	return $r;
}
