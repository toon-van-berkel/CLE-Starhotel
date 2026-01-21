<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../config/db.php';

$user = require_login();

try {
    $pdo = db();
    $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = :id")
        ->execute(['id' => (int)$user['id']]);
} catch (Throwable $e) {}

json_ok(['ok' => true, 'user' => $user]);
