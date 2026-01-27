<?php
declare(strict_types=1);

function db(): PDO {
  static $pdo = null;
  if ($pdo instanceof PDO) return $pdo;

  $dataDir = __DIR__ . '/../../data';
  if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
  }

  $dbPath = $dataDir . '/starhotel.sqlite';
  $pdo = new PDO('sqlite:' . $dbPath);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  return $pdo;
}

function ensure_schema(): void {
  $pdo = db();

  $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      first_name TEXT NOT NULL,
      last_name TEXT NOT NULL,
      email TEXT NOT NULL UNIQUE,
      password_hash TEXT NOT NULL,
      role_ids TEXT NOT NULL DEFAULT '[]',
      permission_ids TEXT NOT NULL DEFAULT '[]',
      created_at TEXT NOT NULL DEFAULT (datetime('now'))
    );
  ");

  $pdo->exec("
    CREATE TABLE IF NOT EXISTS rooms (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      title TEXT NOT NULL,
      description TEXT NOT NULL DEFAULT '',
      price_per_night REAL NOT NULL DEFAULT 0,
      capacity INTEGER NOT NULL DEFAULT 1
    );
  ");

  $pdo->exec("
    CREATE TABLE IF NOT EXISTS contacts (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER NULL,
      subject TEXT NOT NULL,
      message TEXT NOT NULL,
      status TEXT NOT NULL DEFAULT 'open',
      created_at TEXT NOT NULL DEFAULT (datetime('now')),
      updated_at TEXT NULL,
      FOREIGN KEY(user_id) REFERENCES users(id)
    );
  ");

  $pdo->exec("
    CREATE TABLE IF NOT EXISTS reservations (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER NOT NULL,
      room_id INTEGER NOT NULL,
      check_in TEXT NOT NULL,
      check_out TEXT NOT NULL,
      status TEXT NOT NULL DEFAULT 'active',
      created_at TEXT NOT NULL DEFAULT (datetime('now')),
      updated_at TEXT NULL,
      FOREIGN KEY(user_id) REFERENCES users(id),
      FOREIGN KEY(room_id) REFERENCES rooms(id)
    );
  ");

  // Seed rooms (alleen als leeg)
  $count = (int)$pdo->query("SELECT COUNT(*) AS c FROM rooms")->fetch()['c'];
  if ($count === 0) {
    $stmt = $pdo->prepare("INSERT INTO rooms (title, description, price_per_night, capacity) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Room 1', 'Simple room', 79.0, 2]);
    $stmt->execute(['Room 2', 'Nice view', 99.0, 2]);
    $stmt->execute(['Room 3', 'Family room', 129.0, 4]);
  }
}
