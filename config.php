<?php
// config.php

$DB_HOST = "__DB_HOST__";
$DB_NAME = "__DB_NAME__";
$DB_USER = "__DB_USER__";
$DB_PASS = "__DB_PASS__"; // alle Platzhalter werden beim Deploy durch die GitHub Secrets ersetzt

try {
  $pdo = new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  http_response_code(500);
  if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
  }
  echo json_encode(['error' => 'database unavailable']);
  exit;
}
