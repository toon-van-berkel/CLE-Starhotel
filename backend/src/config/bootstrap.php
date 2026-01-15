<?php
declare(strict_types=1);

require_once __DIR__ . '/env.php';
loadEnv(dirname(__DIR__, 2) . '/.env'); // backend/.env

$frontendOrigin = env('FRONTEND_ORIGIN', 'http://localhost:5173');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($origin === $frontendOrigin) {
  header("Access-Control-Allow-Origin: $frontendOrigin");
  header('Access-Control-Allow-Credentials: true');
}

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
header('Access-Control-Max-Age: 86400'); // cache preflight for 1 day
header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
  http_response_code(204);
  exit;
}

// Sessions
ini_set('session.use_strict_mode', '1');

$cookieSecure = env_bool('COOKIE_SECURE', false);
$cookieSameSite = env('COOKIE_SAMESITE', 'Lax');

session_set_cookie_params([
    'httponly' => true,
    'secure' => $cookieSecure,
    'samesite' => $cookieSameSite,
]);

session_start();

function json_ok(array $data = []): void {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

function json_error(string $message, int $code = 400, array $extra = []): void {
    http_response_code($code);
    echo json_encode(['error' => $message] + $extra, JSON_UNESCAPED_UNICODE);
    exit;
}

function read_json_body(): array {
    $raw = file_get_contents('php://input') ?: '';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

// Temperary gonna move this
function require_login(): array {
    if (!isset($_SESSION['user'])) json_error('Unauthorized', 401);
    return $_SESSION['user'];
}