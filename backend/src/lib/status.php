<?php
declare(strict_types=1);

function get_status_id(string $type, string $name): int {
  static $cache = [];

  $key = $type . '::' . $name;
  if (isset($cache[$key])) return $cache[$key];

  $pdo = db();
  // Pas tabel/kolommen aan als ze anders heten (bv. statuses/status)
  $stmt = $pdo->prepare("SELECT id FROM statuses WHERE type = ? AND name = ? LIMIT 1");
  $stmt->execute([$type, $name]);
  $row = $stmt->fetch();

  if (!$row) {
    // fallback: als je dit liever hard faalt, zet dan 500
    $cache[$key] = 1;
    return 1;
  }

  $cache[$key] = (int)$row['id'];
  return $cache[$key];
}
