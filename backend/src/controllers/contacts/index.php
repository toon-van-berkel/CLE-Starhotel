<?php
declare(strict_types=1);

// Alleen admin mag alles zien
require_permission(6);

$pdo = db();
$items = $pdo->query("
  SELECT id, user_id, subject, message, status, created_at, updated_at
  FROM contacts
  ORDER BY id DESC
")->fetchAll();

json_response(['ok' => true, 'contacts' => $items]);
