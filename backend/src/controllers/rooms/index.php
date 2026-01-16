<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db();

    $stmt = $pdo->prepare("SELECT * FROM rooms");
    $stmt->execute();

    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'records' => $rooms
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'records' => [],
        'error' => 'Internal server error'
    ], JSON_UNESCAPED_UNICODE);
}