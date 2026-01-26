<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/config/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Rooms
if ($method === 'GET' && $uri === '/api/rooms') {
  require __DIR__ . '/../src/controllers/rooms/index.php';
  exit;
}
if ($method === 'GET' && preg_match('#^/api/rooms/room-(\d+)/?$#', $uri, $matches)) {
  $_GET['id'] = (int) $matches[1];
  require __DIR__ . '/../src/controllers/rooms/detail.php';
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
// Reservation
if ($method === 'POST' && $uri === '/api/reservation') {
  require __DIR__ . '/../src/controllers/reservation/reservation.php';
  exit;
}

if ($method === 'GET' && $uri === '/api/confirm') {
  require __DIR__ . '/../src/controllers/confirm/confirmation.php';
  exit;
}

// Contact
if ($method === 'GET' && $uri === '/api/contact') {
  require __DIR__ . '/../src/controllers/contact/index.php';
  exit;
}
if ($method === 'GET' && preg_match('#^/api/contact/contact-(\d+)/?$#', $uri, $matches)) {
  $_GET['id'] = (int) $matches[1];
  require __DIR__ . '/../src/controllers/contact/detail.php';
  exit;
}

if ($method === 'POST' && $uri === '/api/contact') {
  require __DIR__ . '/../src/controllers/contact/submit.php';
  exit;
}

if ($method === 'POST' && $uri === '/api/contact') {
  require __DIR__ . '/../src/controllers/contact/submit.php';
  exit;
}
if ($method === 'POST' && $uri === '/api/contact/update') {
  require __DIR__ . '/../src/controllers/contact/update.php';
  exit;
}
if ($method === 'POST' && $uri === '/api/contact/delete') {
  require __DIR__ . '/../src/controllers/contact/delete.php';
  exit;
}

// Reservations
if ($method === 'GET' && $uri === '/api/reservation') {
	require __DIR__ . '/../src/controllers/reservation/index.php';
	exit;
}
if ($method === 'GET' && preg_match('#^/api/reservation/reservation-(\d+)/?$#', $uri, $matches)) {
	$_GET['id'] = (int) $matches[1];
	require __DIR__ . '/../src/controllers/reservation/detail.php';
	exit;
}
if ($method === 'POST' && $uri === '/api/reservation') {
	require __DIR__ . '/../src/controllers/reservation/create.php';
	exit;
}
if ($method === 'POST' && $uri === '/api/reservation/update') {
	require __DIR__ . '/../src/controllers/reservation/update.php';
	exit;
}
if ($method === 'POST' && $uri === '/api/reservation/delete') {
	require __DIR__ . '/../src/controllers/reservation/delete.php';
	exit;
}

http_response_code(404);
echo json_encode(['error' => 'Not found'], JSON_UNESCAPED_UNICODE);
