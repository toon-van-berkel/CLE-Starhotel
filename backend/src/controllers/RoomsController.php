<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

final class RoomsController
{
    public static function index(): void
    {
        try {
            $pdo = db();

            // No user input, no filtering
            $stmt = $pdo->query("
                SELECT id, number, max_capacity, current_capacity, floor, wing, location
                FROM rooms
                ORDER BY floor, wing, number
            ");

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $rooms = array_map(static fn($r) => [
                'id' => (int)$r['id'],
                'number' => (int)$r['number'],
                'max_capacity' => (int)$r['max_capacity'],
                'current_capacity' => (int)$r['current_capacity'],
                'floor' => (int)$r['floor'],
                'wing' => (string)$r['wing'],
                'location' => (string)$r['location'],
            ], $rows);

            echo json_encode(['rooms' => $rooms], JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['rooms' => [], 'error' => $e->getMessage()]);
        }
    }
}
