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

$stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
$stmt->execute([$id]);

json_response(['ok' => true]);
