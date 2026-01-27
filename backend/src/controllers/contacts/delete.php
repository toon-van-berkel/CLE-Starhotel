<?php
declare(strict_types=1);

require_permission(6);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  json_response(['ok' => false, 'error' => 'Invalid contact id'], 400);
  exit;
}

$pdo = db();
$stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
$stmt->execute([$id]);

json_response(['ok' => true]);
