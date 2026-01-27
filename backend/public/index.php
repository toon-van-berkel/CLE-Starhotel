<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/config/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// OPTIONS preflight (CORS)
if ($method === 'OPTIONS') {
  http_response_code(204);
  exit;
}

/* -----------------------------
 * Rooms
 * ----------------------------- */
if ($method === 'GET' && $path === '/api/rooms') {
  require __DIR__ . '/../src/controllers/rooms/index.php';
  exit;
}
if ($method === 'GET' && preg_match('#^/api/rooms/room-(\d+)/?$#', $path, $m)) {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../src/controllers/rooms/detail.php';
  exit;
}

/* -----------------------------
 * Auth
 * ----------------------------- */
if ($method === 'POST' && $path === '/api/register') {
  require __DIR__ . '/../src/controllers/user/register.php';
  exit;
}
if ($method === 'POST' && $path === '/api/login') {
  require __DIR__ . '/../src/controllers/user/login.php';
  exit;
}
if ($method === 'POST' && $path === '/api/logout') {
  require __DIR__ . '/../src/controllers/user/logout.php';
  exit;
}
if ($method === 'GET' && $path === '/api/me') {
  require __DIR__ . '/../src/controllers/user/me.php';
  exit;
}

/* -----------------------------
 * Contacts (Tickets)
 * ----------------------------- */
if ($method === 'GET' && $path === '/api/contacts') {
  require __DIR__ . '/../src/controllers/contacts/index.php';
  exit;
}
if ($method === 'POST' && $path === '/api/contacts') {
  require __DIR__ . '/../src/controllers/contacts/create.php';
  exit;
}
if ($method === 'PUT' && preg_match('#^/api/contacts/update-(\d+)/?$#', $path, $m)) {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../src/controllers/contacts/update.php';
  exit;
}
if ($method === 'DELETE' && preg_match('#^/api/contacts/delete-(\d+)/?$#', $path, $m)) {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../src/controllers/contacts/delete.php';
  exit;
}

/* -----------------------------
 * Reservations
 * ----------------------------- */
if ($method === 'GET' && $path === '/api/reservations') {
  require __DIR__ . '/../src/controllers/reservations/index.php';
  exit;
}
if ($method === 'POST' && $path === '/api/reservations') {
  require __DIR__ . '/../src/controllers/reservations/create.php';
  exit;
}
if ($method === 'GET' && preg_match('#^/api/reservations/reservation-(\d+)/?$#', $path, $m)) {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../src/controllers/reservations/detail.php';
  exit;
}
if ($method === 'PUT' && preg_match('#^/api/reservations/update-(\d+)/?$#', $path, $m)) {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../src/controllers/reservations/update.php';
  exit;
}
if ($method === 'DELETE' && preg_match('#^/api/reservations/delete-(\d+)/?$#', $path, $m)) {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../src/controllers/reservations/delete.php';
  exit;
}

/* -----------------------------
 * Fallback
 * ----------------------------- */
json_response(['ok' => false, 'error' => 'Route not found', 'path' => $path], 404);
