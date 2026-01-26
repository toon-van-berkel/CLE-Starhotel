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

// ---------- Sessions ----------
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

// ---------- DB (PDO) ----------
// Expected .env keys (choose whichever you already use, this supports both styles):
// DB_HOST, DB_NAME, DB_USER, DB_PASS  (recommended)
// OR DATABASE_HOST, DATABASE_NAME, DATABASE_USER, DATABASE_PASS

$dbHost = (string) env('DB_HOST', env('DATABASE_HOST', '127.0.0.1'));
$dbName = (string) env('DB_NAME', env('DATABASE_NAME', 'starhotel'));
$dbUser = (string) env('DB_USER', env('DATABASE_USER', 'root'));
$dbPass = (string) env('DB_PASS', env('DATABASE_PASS', ''));
$dbPort = (string) env('DB_PORT', '3306');

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (Throwable $e) {
    // Do not leak details in production
    json_error('Database connection failed', 500);
}
