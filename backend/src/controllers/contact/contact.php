<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Behandel Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$host = '127.0.0.1';
$db = 'cle2';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!empty($data['name']) && !empty($data['email']) && !empty($data['reason']) && !empty($data['title']) && !empty($data['message'])) {

        $sql = "INSERT INTO contact (name, email, reason, title, message, created_at, status_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['reason'],
                $data['title'],
                $data['message'],
                $data['created_at'],
                1 // Standaard status_id
            ]);

            echo json_encode([
                "status" => "success",
                "message" => "Bedankt! Je bericht is opgeslagen in de tabel 'contact'."
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "SQL Fout: " . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Naam en email zijn verplicht."]);
    }
}