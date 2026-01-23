<?php
declare(strict_types=1);

// If you already load env somewhere else, keep that — but CORS must run BEFORE output.
require_once __DIR__ . '/env.php';
loadEnv(dirname(__DIR__, 2) . '/.env'); // backend/.env

// ---------- CORS ----------
$allowedOrigins = array_filter(array_map('trim', explode(',', (string)env(
    'FRONTEND_ORIGINS',
    'http://localhost:5173,http://127.0.0.1:5173'
))));

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin && in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: {$origin}");
    header('Vary: Origin');
    header('Access-Control-Allow-Credentials: true');
}

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');

// Preflight must exit early
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ---------- Sessions (cross-site cookie for localhost -> https domain) ----------
$isHttps =
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? '') == 443)
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

session_name('starhotel_session');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'none'
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ---------- JSON helpers ----------
header('Content-Type: application/json; charset=utf-8');

function json_ok(array $data = [], int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(string $message, int $code = 400, array $extra = []): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message] + $extra, JSON_UNESCAPED_UNICODE);
    exit;
}

function read_json_body(): array {
    $raw = file_get_contents('php://input') ?: '';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function require_login(): array {
    if (!isset($_SESSION['user'])) json_error('Unauthorized', 401);
    return $_SESSION['user'];
}
