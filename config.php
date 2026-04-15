<?php
// config.php

$DB_HOST = "localhost";
$DB_NAME = "atzblltz_gymbro";
$DB_USER = "atzblltz_gymbro";
$DB_PASS = "__DB_PASSWORD__"; // wird beim Deploy durch GitHub secret DB_PASSWORD ersetzt

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
