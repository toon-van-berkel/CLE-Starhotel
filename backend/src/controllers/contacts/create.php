<?php
declare(strict_types=1);

$userId = require_logged_in();
$body = json_body();

$subject = trim((string)($body['subject'] ?? $body['title'] ?? ''));
$message = trim((string)($body['message'] ?? ''));

if ($subject === '' || $message === '') {
  json_response(['ok' => false, 'error' => 'Missing subject/title or message'], 400);
  exit;
}

$pdo = db();

// naam/email afleiden uit users (zodat frontend simpel kan blijven)
$stmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$u = $stmt->fetch();
if (!$u) {
  json_response(['ok' => false, 'error' => 'User not found'], 401);
  exit;
}

$name = trim($u['first_name'] . ' ' . $u['last_name']);
$email = (string)$u['email'];
$reason = trim((string)($body['reason'] ?? 'general'));

// status_id via lookup (geen hardcode)
$statusId = get_status_id('contact', 'none');

// created_at invullen (als DB geen default heeft)
$createdAt = date('Y-m-d H:i:s');

// Pas kolommen aan aan jouw schema (hier gebaseerd op jouw dump)
$stmt = $pdo->prepare("
  INSERT INTO contact (user_id, name, email, reason, title, message, status_id, created_at)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$userId, $name, $email, $reason, $subject, $message, $statusId, $createdAt]);

json_response(['ok' => true, 'id' => (int)$pdo->lastInsertId()], 201);
