<?php
declare(strict_types=1);

require_permission(6);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  json_response(['ok' => false, 'error' => 'Invalid contact id'], 400);
  exit;
}

$body = json_body();

$fields = [];
$params = [];

if (array_key_exists('subject', $body)) {
  $fields[] = "subject = ?";
  $params[] = trim((string)$body['subject']);
}
if (array_key_exists('message', $body)) {
  $fields[] = "message = ?";
  $params[] = trim((string)$body['message']);
}
if (array_key_exists('status', $body)) {
  $fields[] = "status = ?";
  $params[] = trim((string)$body['status']);
}

if (count($fields) === 0) {
  json_response(['ok' => false, 'error' => 'No fields to update'], 400);
  exit;
}

$fields[] = "updated_at = datetime('now')";
$params[] = $id;

$pdo = db();
$stmt = $pdo->prepare("UPDATE contacts SET " . implode(', ', $fields) . " WHERE id = ?");
$stmt->execute($params);

json_response(['ok' => true]);
