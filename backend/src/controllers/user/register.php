<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../config/db.php';

const DEFAULT_ROLE_ID = 2; // your "Default" role is id=2 in DB

$body  = read_json_body();

$first = trim((string)($body['first_name'] ?? ''));
$last  = trim((string)($body['last_name'] ?? ''));
$email = strtolower(trim((string)($body['email'] ?? '')));
$phone = trim((string)($body['phone'] ?? ''));
$pass  = (string)($body['password'] ?? '');

$fields = [];
if (strlen($first) < 2) $fields['first_name'] = 'First name too short';
if (strlen($last) < 2) $fields['last_name'] = 'Last name too short';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $fields['email'] = 'Invalid email';
if (strlen($phone) < 6) $fields['phone'] = 'Phone is required';
if (strlen($pass) < 8) $fields['password'] = 'Password must be at least 8 characters';

if ($fields) json_error('Validation failed', 422, ['fields' => $fields]);

try {
    $pdo = db();

    // Email uniqueness
    $exists = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $exists->execute(['email' => $email]);
    if ($exists->fetch()) {
        json_error('Email already in use', 409, ['fields' => ['email' => 'Email already in use']]);
    }

    $hash = password_hash($pass, PASSWORD_BCRYPT);

    $pdo->beginTransaction();

    // Insert user (created_at NOT NULL, phone NOT NULL, status_id default 1)
    $ins = $pdo->prepare("
        INSERT INTO users (first_name, last_name, email, phone, password, status_id, created_at, last_seen)
        VALUES (:first, :last, :email, :phone, :pass, 1, NOW(), NOW())
    ");
    $ins->execute([
        'first' => $first,
        'last' => $last,
        'email' => $email,
        'phone' => $phone,
        'pass'  => $hash
    ]);

    $userId = (int)$pdo->lastInsertId();

    // Assign role in user_has_roles
    $role = $pdo->prepare("
        INSERT INTO user_has_roles (user_id, role_id)
        VALUES (:uid, :rid)
    ");
    $role->execute(['uid' => $userId, 'rid' => DEFAULT_ROLE_ID]);

    $pdo->commit();

    json_ok(['ok' => true, 'id' => $userId], 201);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    json_error('Server error', 500);
}
