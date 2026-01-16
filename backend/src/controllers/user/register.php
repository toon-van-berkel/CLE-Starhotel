<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/error.php';
const DEFAULT_ROLE_ID = 2; // Set standerd role ID to 2

$body = read_json_body();

$first = trim((string)($body['first_name'] ?? ''));
$last  = trim((string)($body['last_name'] ?? ''));
$email = strtolower(trim((string)($body['email'] ?? '')));
$phone = trim((string)($body['phone'] ?? ''));
$pass  = (string)($body['password'] ?? '');

if (strlen($first) < 2) json_error('First name too short');
if (strlen($last) < 2) json_error('Last name too short');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_error('Invalid email');
if (strlen($pass) < 8) json_error('Password must be at least 8 characters');

try {
    $pdo = db();

    $check = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $check->execute(['email' => $email]);
    if ($check->fetch()) json_error('Email already registered', 409);

    $hash = password_hash($pass, PASSWORD_DEFAULT); // Hash password

    $stmt = $pdo->prepare("
        INSERT INTO users (role_id, first_name, last_name, email, phone, password, created_at, last_seen)
        VALUES (:role_id, :first_name, :last_name, :email, :phone, :password, NOW(), NULL)
    ");

    $stmt->execute([
        'role_id' => DEFAULT_ROLE_ID,   // Set role id to 2 which is default
        'first_name' => $first,         // First name
        'last_name' => $last,           // Last name
        'email' => $email,              // email
        'phone' => $phone,              // empty string if none
        'password' => $hash             // Hashed password
    ]);

    json_ok(['ok' => true]);
} catch (Throwable $e) {
    error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()); // Temp error log
    json_error('Server error', 500);
}