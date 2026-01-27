<?php
declare(strict_types=1);

$userId = require_logged_in();
$body = json_body();

$roomId = (int)($body['room_id'] ?? $body['roomId'] ?? 0);

$checkIn  = trim((string)($body['check_in']  ?? $body['booked_from'] ?? ''));
$checkOut = trim((string)($body['check_out'] ?? $body['booked_till'] ?? ''));

if ($roomId <= 0 || $checkIn === '' || $checkOut === '') {
  json_response(['ok' => false, 'error' => 'Missing room_id and/or dates'], 400);
  exit;
}

$pdo = db();

// controle room bestaat (pas tabel/kolom aan)
$stmt = $pdo->prepare("SELECT id FROM rooms WHERE id = ?");
$stmt->execute([$roomId]);
if (!$stmt->fetch()) {
  json_response(['ok' => false, 'error' => 'Room not found'], 404);
  exit;
}

$statusId = get_status_id('reservation', 'none');
$checkedIn = 0;
$bookedAt = date('Y-m-d H:i:s');

// DB wil vaak payment_method verplicht -> default maken
$paymentMethod = trim((string)($body['payment_method'] ?? 'unknown'));

// Pas kolommen aan aan jouw schema (jij had o.a. booked_from, booked_till)
$stmt = $pdo->prepare("
  INSERT INTO reservations (user_id, status_id, checked_in, booked_at, booked_from, booked_till, payment_method, room_id)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$userId, $statusId, $checkedIn, $bookedAt, $checkIn, $checkOut, $paymentMethod, $roomId]);

json_response(['ok' => true, 'id' => (int)$pdo->lastInsertId()], 201);
