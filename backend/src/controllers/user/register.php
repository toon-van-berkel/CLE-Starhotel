<?php
declare(strict_types=1);

$body = json_body();

$firstName = trim((string)($body['first_name'] ?? ''));
$lastName  = trim((string)($body['last_name'] ?? ''));
$email     = strtolower(trim((string)($body['email'] ?? '')));
$password  = (string)($body['password'] ?? '');

if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
  json_response(['ok' => false, 'error' => 'Missing fields'], 400);
  exit;
}

$pdo = db();

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Default user: permission_ids = []
// Wil je een admin user: zet permission_ids = [6] handmatig in DB of voeg hier toe op basis van email.
$roleIds = json_encode([]);
$permissionIds = json_encode([]);

try {
  $stmt = $pdo->prepare("
    INSERT INTO users (first_name, last_name, email, password_hash, role_ids, permission_ids)
    VALUES (?, ?, ?, ?, ?, ?)
  ");
  $stmt->execute([$firstName, $lastName, $email, $passwordHash, $roleIds, $permissionIds]);

  $userId = (int)$pdo->lastInsertId();
  $_SESSION['user_id'] = $userId;

  $user = get_user_by_id($userId);

  json_response(['ok' => true, 'user' => $user], 201);
} catch (PDOException $e) {
  json_response(['ok' => false, 'error' => 'Email already exists'], 409);
}
