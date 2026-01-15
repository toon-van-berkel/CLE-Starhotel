<?php
declare(strict_types=1);

require_once __DIR__ . '/env.php';

loadEnv(dirname(__DIR__, 2) . '/.env'); // backend/.env

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $host = env('DB_HOST', '127.0.0.1');
    $db   = env('DB_NAME', 'test');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');
    $charset = env('DB_CHARSET', 'utf8mb4');

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
