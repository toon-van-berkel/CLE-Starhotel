<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', '', $params['secure'] ?? false, $params['httponly'] ?? true);
}
session_destroy();

json_ok(['ok' => true]);
