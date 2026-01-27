<?php
declare(strict_types=1);

function json_body(): array {
  $raw = file_get_contents('php://input') ?: '';
  if ($raw === '') return [];
  $data = json_decode($raw, true);
  if (!is_array($data)) {
    json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    exit;
  }
  return $data;
}

function json_response(array $data, int $status = 200): void {
  http_response_code($status);
  echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
