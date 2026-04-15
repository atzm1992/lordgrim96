<?php
require "config.php";

// JSON einlesen
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["user"], $data["date"])) {
  http_response_code(400);
  exit;
}

// Insert oder ignorieren (kein doppelter Tag)
$stmt = $pdo->prepare("
  INSERT IGNORE INTO gym_days (user_name, gym_date)
  VALUES (?, ?)
");

$stmt->execute([
  $data["user"],
  $data["date"]
]);

echo json_encode(["status" => "ok"]);
