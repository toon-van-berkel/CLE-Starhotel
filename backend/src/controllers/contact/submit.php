<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

$body = read_json_body();

try {
    $pdo = db();

    $name = trim((string) ($body['name'] ?? ''));
    $email = trim((string) ($body['email'] ?? ''));
    $reason = trim((string) ($body['reason'] ?? ''));


    if ($name === '') {
        json_error('Missing required field: name', 400);
        exit;
    }

    if ($email === '') {
        json_error('Missing required field: email', 400);
        exit;
    }
    if ($reason === '') {
        json_error('Missing required field: reason', 400);
        exit;
    }


    $stmt = $pdo->prepare("
        INSERT INTO contact (name , email , reason)
        VALUES (:name, :email, :reason)
    ");

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':reason' => $reason,
    ]);

    json_ok([
        'ok' => true,
        'id' => (int) $pdo->lastInsertId(),
    ]);
} catch (Throwable $e) {
    json_error('Server error', 500);
}