<?php
declare(strict_types=1);

$userId = require_logged_in();
$user = get_user_by_id($userId);

$pdo = db();

$isAdmin = in_array(6, $user['permission_ids'] ?? [], true);

if ($isAdmin) {
  $stmt = $pdo->query("
    SELECT id, user_id, room_id, check_in, check_out, status, created_at, updated_at
    FROM reservations
    ORDER BY id DESC
  ");
  $items = $stmt->fetchAll();
} else {
  $stmt = $pdo->prepare("
    SELECT id, user_id, room_id, check_in, check_out, status, created_at, updated_at
    FROM reservations
    WHERE user_id = ?
    ORDER BY id DESC
  ");
  $stmt->execute([$userId]);
  $items = $stmt->fetchAll();
}

json_response(['ok' => true, 'reservations' => $items]);
