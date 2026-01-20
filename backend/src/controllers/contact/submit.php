<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Read JSON body (use your helper if present)
    if (function_exists('read_json_body')) {
        $body = read_json_body();
    } else {
        $raw = file_get_contents('php://input') ?: '';
        $body = json_decode($raw, true);
        if (!is_array($body)) $body = [];
    }

    $name    = trim((string)($body['name'] ?? ''));
    $email   = trim((string)($body['email'] ?? ''));
    $reason  = trim((string)($body['reason'] ?? ''));
    $title   = trim((string)($body['title'] ?? ''));
    $message = trim((string)($body['message'] ?? ''));

    // Optional: if user is logged in you can pass user_id, otherwise null
    $userId = $body['user_id'] ?? null;
    $userId = ($userId === null || $userId === '') ? null : (int)$userId;

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

    $pdo = db();

    $stmt = $pdo->prepare("
        INSERT INTO contact
            (name, email, reason, title, message, status_id, admin_handled_id, created_at, handled_at, user_id)
        VALUES
            (:name, :email, :reason, :title, :message, :status_id, NULL, NOW(), NULL, :user_id)
    ");

    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':reason', $reason, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    $stmt->bindValue(':status_id', $statusId, PDO::PARAM_INT);

    if ($userId === null) {
        $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    }

    $stmt->execute();

    echo json_encode([
        'ok' => true,
        'id' => (int)$pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Internal server error'
    ], JSON_UNESCAPED_UNICODE);
}
