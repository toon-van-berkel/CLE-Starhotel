<?php
declare(strict_types=1);

function current_user_id(): ?int {
  $id = $_SESSION['user_id'] ?? null;
  return is_int($id) ? $id : null;
}

function require_logged_in(): int {
  $id = current_user_id();
  if (!$id) {
    json_response(['ok' => false, 'error' => 'Not logged in'], 401);
    exit;
  }
  return $id;
}

function get_user_by_id(int $userId): ?array {
  $pdo = db();
  $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role_ids, permission_ids FROM users WHERE id = ?");
  $stmt->execute([$userId]);
  $user = $stmt->fetch();
  if (!$user) return null;

  $user['role_ids'] = json_decode($user['role_ids'] ?? '[]', true) ?: [];
  $user['permission_ids'] = json_decode($user['permission_ids'] ?? '[]', true) ?: [];
  return $user;
}

function require_permission(int $permissionId): array {
  $userId = require_logged_in();
  $user = get_user_by_id($userId);

  if (!$user) {
    json_response(['ok' => false, 'error' => 'User not found'], 401);
    exit;
  }

  $permissions = $user['permission_ids'] ?? [];
  if (!in_array($permissionId, $permissions, true)) {
    json_response(['ok' => false, 'error' => 'Forbidden (missing permission)'], 403);
    exit;
  }

  return $user;
}
