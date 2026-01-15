<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';

$body = read_json_body();
$email = strtolower(trim((string)($body['email'] ?? '')));
$pass  = (string)($body['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_error('Invalid email');
if ($pass === '') json_error('Missing password');

try {
    $pdo = db();

    $stmt = $pdo->prepare("
        SELECT id, role_id, first_name, last_name, email, phone, password, status_id
        FROM users
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($pass, (string)$user['password'])) {
        json_error('Invalid credentials', 401);
    }

    session_regenerate_id(true);

    $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = :id")
        ->execute(['id' => (int)$user['id']]);

    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'role_id' => (int)$user['role_id'],
        'first_name' => (string)$user['first_name'],
        'last_name' => (string)$user['last_name'],
        'email' => (string)$user['email'],
        'phone' => (string)($user['phone'] ?? ''),
        'status_id' => $user['status_id'] !== null ? (int)$user['status_id'] : null,
    ];

    json_ok(['ok' => true, 'user' => $_SESSION['user']]);
} catch (Throwable $e) {
    json_error('Server error', 500);
}
