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
$stmt = $pdo->prepare("
  SELECT id, user_id, room_id, check_in, check_out, status, created_at, updated_at
  FROM reservations
  WHERE id = ?
");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
  json_response(['ok' => false, 'error' => 'Reservation not found'], 404);
  exit;
}

$isAdmin = in_array(6, $user['permission_ids'] ?? [], true);
$isOwner = ((int)$item['user_id'] === $userId);

if (!$isAdmin && !$isOwner) {
  json_response(['ok' => false, 'error' => 'Forbidden'], 403);
  exit;
}

json_response(['ok' => true, 'reservation' => $item]);
