<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

$body = read_json_body();
$id = $body['id'] ?? null;

try {
    if ($id) {
        // print_r($id);
        $pdo = db();

        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();

        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'record' => $room
        ], JSON_UNESCAPED_UNICODE);

        exit;

    } else {
        $pdo = db();

        $stmt = $pdo->prepare("SELECT * FROM rooms");
        $stmt->execute();

        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'records' => $rooms
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'records' => [],
        'error' => 'Internal server error'
    ], JSON_UNESCAPED_UNICODE);
}

