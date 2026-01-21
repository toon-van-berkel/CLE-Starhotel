<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? null;

try {
    $pdo = db();

    $stmt = $pdo->prepare('SELECT * FROM contact WHERE id = :id');
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();

    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        http_response_code(404);
        echo json_encode(['error' => 'Contact not found'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'record' => $record
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error'
    ], JSON_UNESCAPED_UNICODE);
}
