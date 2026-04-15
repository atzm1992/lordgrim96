<?php
// config.php

$DB_HOST = "localhost";
$DB_NAME = "atzblltz_gymbro";
$DB_USER = "atzblltz_gymbro";
$DB_PASS = "XXXXXXX"; // echtes Passwort hier eintragen

try {
  $pdo = new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  die("Datenbankverbindung fehlgeschlagen");
}
