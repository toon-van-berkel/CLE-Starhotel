<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? null;

try {
    $pdo = db();

    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = :id');
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();

    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        http_response_code(404);
        echo json_encode(['error' => 'Room not found']);
        exit;
    }

    echo json_encode([
        'record' => $room
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error'
    ]);
}