<?php
declare(strict_types=1);

$userId = current_user_id();
if (!$userId) {
  json_response(['ok' => true, 'user' => null]);
  exit;
}

$pdo = db();

// pas kolommen aan aan jouw users tabel
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, status_id FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
  json_response(['ok' => true, 'user' => null]);
  exit;
}

$user['role_ids'] = get_user_role_ids($userId);
$user['permission_ids'] = get_user_permission_ids($userId);

json_response(['ok' => true, 'user' => $user]);
