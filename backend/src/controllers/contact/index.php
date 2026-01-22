<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db();

    $stmt = $pdo->prepare('SELECT * FROM contact ORDER BY created_at DESC');
    $stmt->execute();

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'records' => $records
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'records' => [],
        'error' => 'Internal server error'
    ], JSON_UNESCAPED_UNICODE);
}