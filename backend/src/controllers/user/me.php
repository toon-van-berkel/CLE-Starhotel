<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';

$user = require_login();

// Update last seen
try {
    $pdo = db();
    $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = :id")
        ->execute(['id' => (int)$user['id']]);
} catch (Throwable $e) {}

json_ok(['user' => $user]);
