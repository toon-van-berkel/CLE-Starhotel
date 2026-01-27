<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/http.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/auth.php';

// CORS (simpel). Zet je frontend origin hier hard als je wil.
header('Access-Control-Allow-Origin: ' . ($_SERVER['HTTP_ORIGIN'] ?? '*'));
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE,OPTIONS');
header('Content-Type: application/json; charset=utf-8');

// Session cookies voor auth
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
// lokaal dev vaak http, dus secure=false. In productie: true.
ini_set('session.cookie_secure', '0');

session_start();

// Zorg dat DB + tables bestaan (SQLite)
ensure_schema();
