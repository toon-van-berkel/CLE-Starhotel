<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

$body = read_json_body();

try {
    $pdo = db();

    $name = trim((string) ($body['name'] ?? ''));

    if ($name === '') {
        json_error('Missing required field: name', 400);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO contact (name)
        VALUES (:name)
    ");

    $stmt->execute([
        ':name' => $name
    ]);

    json_ok([
        'ok' => true,
        'id' => (int) $pdo->lastInsertId(),
    ]);
} catch (Throwable $e) {
    json_error('Server error', 500);
}