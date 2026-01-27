<?php
declare(strict_types=1);

$pdo = db();
$rooms = $pdo->query("SELECT id, title, description, price_per_night, capacity FROM rooms ORDER BY id ASC")->fetchAll();

json_response(['ok' => true, 'rooms' => $rooms]);
