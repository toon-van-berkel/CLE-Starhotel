<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/config/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Rooms
if ($method === 'GET' && $uri === '/api/rooms') {
  require __DIR__ . '/../src/controllers/rooms/index.php';
  exit;
}

// User auth
if ($method === 'POST' && $uri === '/api/register') {
  require __DIR__ . '/../src/controllers/user/register.php';
  exit;
}
if ($method === 'POST' && $uri === '/api/login') {
  require __DIR__ . '/../src/controllers/user/login.php';
  exit;
}
if ($method === 'POST' && $uri === '/api/logout') {
  require __DIR__ . '/../src/controllers/user/logout.php';
  exit;
}
if ($method === 'GET' && $uri === '/api/me') {
  require __DIR__ . '/../src/controllers/user/me.php';
  exit;
}

http_response_code(404);
echo json_encode(['error' => 'Not found'], JSON_UNESCAPED_UNICODE);
