<?php
declare(strict_types=1);

$userId = require_logged_in();
$user = get_user_by_id($userId);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  json_response(['ok' => false, 'error' => 'Invalid reservation id'], 400);
  exit;
}

$pdo = db();
$stmt = $pdo->prepare("SELECT id, user_id FROM reservations WHERE id = ?");
$stmt->execute([$id]);
$existing = $stmt->fetch();

if (!$existing) {
  json_response(['ok' => false, 'error' => 'Reservation not found'], 404);
  exit;
}

$isAdmin = in_array(6, $user['permission_ids'] ?? [], true);
$isOwner = ((int)$existing['user_id'] === $userId);
if (!$isAdmin && !$isOwner) {
  json_response(['ok' => false, 'error' => 'Forbidden'], 403);
  exit;
}

$body = json_body();

$fields = [];
$params = [];

if (array_key_exists('room_id', $body)) {
  $fields[] = "room_id = ?";
  $params[] = (int)$body['room_id'];
}
if (array_key_exists('check_in', $body)) {
  $fields[] = "check_in = ?";
  $params[] = trim((string)$body['check_in']);
}
if (array_key_exists('check_out', $body)) {
  $fields[] = "check_out = ?";
  $params[] = trim((string)$body['check_out']);
}
if (array_key_exists('status', $body) && $isAdmin) {
  $fields[] = "status = ?";
  $params[] = trim((string)$body['status']);
}

if (count($fields) === 0) {
  json_response(['ok' => false, 'error' => 'No fields to update'], 400);
  exit;
}

$fields[] = "updated_at = datetime('now')";
$params[] = $id;

$stmt = $pdo->prepare("UPDATE reservations SET " . implode(', ', $fields) . " WHERE id = ?");
$stmt->execute($params);

json_response(['ok' => true]);
