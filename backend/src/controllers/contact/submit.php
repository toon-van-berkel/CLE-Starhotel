<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

$body = read_json_body();

try {
    $pdo = db();

    $name = trim((string) ($body['name'] ?? ''));
    $email = trim((string) ($body['email'] ?? ''));
    $reason = trim((string) ($body['reason'] ?? ''));
    $title = trim((string) ($body['title'] ?? ''));
    $message = trim((string) ($body['message'] ?? ''));
    $created_at = date('Y-m-d H:i:s');


    // Optional: if user is logged in you can pass user_id, otherwise null
    $userId = $body['user_id'] ?? null;
    $userId = ($userId === null || $userId === '') ? null : (int) $userId;

    // Choose "new/unhandled" status id
    $statusId = 1;

    // Validation (match your varchar lengths)
    $errors = [];

    if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 50) {
        $errors['name'] = 'Name must be 2-50 characters.';
    }
    if ($email === '' || mb_strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email (max 100 chars).';
    }
    if ($reason === '' || mb_strlen($reason) < 2 || mb_strlen($reason) > 100) {
        $errors['reason'] = 'Reason must be 2-100 characters.';
    }
    if ($title === '' || mb_strlen($title) < 2 || mb_strlen($title) > 50) {
        $errors['title'] = 'Title must be 2-50 characters.';
    }
    if ($message === '' || mb_strlen($message) < 10) {
        $errors['message'] = 'Message must be at least 10 characters.';
    }

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode([
            'ok' => false,
            'error' => 'Validation failed',
            'fields' => $errors
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }



    $stmt = $pdo->prepare("
        INSERT INTO contact (name , email , reason, title , message, created_at)
        VALUES (:name, :email, :reason, :title, :message, :created_at)
    ");

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':reason' => $reason,
        ':title' => $title,
        ':message' => $message,
        ':created_at' => $created_at,
    ]);

    json_ok([
        'ok' => true,
        'id' => (int) $pdo->lastInsertId(),
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Internal server error'
    ], JSON_UNESCAPED_UNICODE);
}