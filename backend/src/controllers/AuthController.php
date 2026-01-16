<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/bootstrap.php';

final class AuthController
{
    // Set your default "user" role here
    private const DEFAULT_ROLE_ID = 2;

    public static function register(): void
    {
        $body = read_json_body();

        $first = trim((string)($body['first_name'] ?? ''));
        $last  = trim((string)($body['last_name'] ?? ''));
        $email = strtolower(trim((string)($body['email'] ?? '')));
        $phone = trim((string)($body['phone'] ?? ''));
        $pass  = (string)($body['password'] ?? '');

        if ($first === '' || strlen($first) < 2) json_error('First name too short');
        if ($last === '' || strlen($last) < 2) json_error('Last name too short');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_error('Invalid email');
        if (strlen($pass) < 8) json_error('Password must be at least 8 characters');

        try {
            $pdo = db();

            $check = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $check->execute(['email' => $email]);
            if ($check->fetch()) json_error('Email already registered', 409);

            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (role_id, first_name, last_name, email, phone, password, status_id, created_at, last_seen)
                VALUES (:role_id, :first_name, :last_name, :email, :phone, :password, NULL, NOW(), NULL)
            ");

            $stmt->execute([
                'role_id' => self::DEFAULT_ROLE_ID,
                'first_name' => $first,
                'last_name' => $last,
                'email' => $email,
                'phone' => $phone !== '' ? $phone : null,
                'password' => $hash
            ]);

            json_ok(['ok' => true]);
        } catch (Throwable $e) {
            json_error('Server error', 500);
        }
    }

    public static function login(): void
    {
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
    }

    public static function me(): void
    {
        $user = require_login();

        // Optional: update last_seen when user pings /me
        try {
            $pdo = db();
            $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = :id")
                ->execute(['id' => (int)$user['id']]);
        } catch (Throwable $e) {}

        json_ok(['user' => $user]);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        json_ok(['ok' => true]);
    }
}
