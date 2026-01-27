<?php
declare(strict_types=1);

$body = json_body();
$email = strtolower(trim((string)($body['email'] ?? '')));
$password = (string)($body['password'] ?? '');

if ($email === '' || $password === '') {
  json_response(['ok' => false, 'error' => 'Missing email or password'], 400);
  exit;
}

$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$userRow = $stmt->fetch();

if (!$userRow || !password_verify($password, (string)$userRow['password_hash'])) {
  json_response(['ok' => false, 'error' => 'Invalid credentials'], 401);
  exit;
}

$_SESSION['user_id'] = (int)$userRow['id'];

$user = get_user_by_id((int)$userRow['id']);
json_response(['ok' => true, 'user' => $user]);
