<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../config/db.php';

$body = read_json_body();
$email = strtolower(trim((string)($body['email'] ?? '')));
$pass  = (string)($body['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_error('Invalid email', 400);
if ($pass === '') json_error('Missing password', 400);

try {
    $pdo = db();

    // Fetch user (matches your users table)
    $stmt = $pdo->prepare("
        SELECT id, first_name, last_name, email, phone, password, status_id
        FROM users
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($pass, (string)$user['password'])) {
        json_error('Invalid credentials', 401);
    }

    // role_ids (many-to-many)
    $r = $pdo->prepare("SELECT role_id FROM user_has_roles WHERE user_id = :uid");
    $r->execute(['uid' => (int)$user['id']]);
    $role_ids = array_map('intval', $r->fetchAll(PDO::FETCH_COLUMN));

    // permission_ids via roles
    $p = $pdo->prepare("
        SELECT DISTINCT rhp.permission_id
        FROM user_has_roles uhr
        JOIN role_has_permissions rhp ON rhp.role_id = uhr.role_id
        WHERE uhr.user_id = :uid
    ");
    $p->execute(['uid' => (int)$user['id']]);
    $permission_ids = array_map('intval', $p->fetchAll(PDO::FETCH_COLUMN));

    // update last_seen
    $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = :id")
        ->execute(['id' => (int)$user['id']]);

    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'first_name' => (string)$user['first_name'],
        'last_name' => (string)$user['last_name'],
        'email' => (string)$user['email'],
        'phone' => (string)$user['phone'],
        'status_id' => $user['status_id'] !== null ? (int)$user['status_id'] : null,
        'role_ids' => $role_ids,
        'permission_ids' => $permission_ids,
    ];

    json_ok(['ok' => true, 'user' => $_SESSION['user']]);
} catch (Throwable $e) {
    json_error('Server error', 500);
}
