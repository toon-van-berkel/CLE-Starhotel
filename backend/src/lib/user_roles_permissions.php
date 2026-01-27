<?php
declare(strict_types=1);

function get_user_role_ids(int $userId): array {
  $pdo = db();
  $stmt = $pdo->prepare("SELECT role_id FROM user_has_roles WHERE user_id = ?");
  $stmt->execute([$userId]);
  return array_map(fn($r) => (int)$r['role_id'], $stmt->fetchAll() ?: []);
}

function get_user_permission_ids(int $userId): array {
  $pdo = db();
  $stmt = $pdo->prepare("
    SELECT DISTINCT rhp.permission_id
    FROM role_has_permissions rhp
    JOIN user_has_roles uhr ON uhr.role_id = rhp.role_id
    WHERE uhr.user_id = ?
  ");
  $stmt->execute([$userId]);
  return array_map(fn($r) => (int)$r['permission_id'], $stmt->fetchAll() ?: []);
}
