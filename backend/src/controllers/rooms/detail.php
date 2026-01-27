<?php
declare(strict_types=1);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  json_response(['ok' => false, 'error' => 'Invalid room id'], 400);
  exit;
}

$pdo = db();
$stmt = $pdo->prepare("SELECT id, title, description, price_per_night, capacity FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch();

if (!$room) {
  json_response(['ok' => false, 'error' => 'Room not found'], 404);
  exit;
}

json_response(['ok' => true, 'room' => $room]);
