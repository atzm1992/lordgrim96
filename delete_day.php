<?php
require "config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data) || !isset($data["user"], $data["date"])) {
  http_response_code(400);
  echo json_encode(["error" => "missing fields"]);
  exit;
}

$user = trim((string)$data["user"]);
$date = (string)$data["date"];

if ($user === "" || strlen($user) > 50 || !preg_match('/^[\w\-]+$/', $user)) {
  http_response_code(400);
  echo json_encode(["error" => "invalid user"]);
  exit;
}

$dt = DateTime::createFromFormat("Y-m-d", $date);
if (!$dt || $dt->format("Y-m-d") !== $date) {
  http_response_code(400);
  echo json_encode(["error" => "invalid date"]);
  exit;
}

$stmt = $pdo->prepare("
  DELETE FROM gym_days
  WHERE user_name = ? AND gym_date = ?
");

$stmt->execute([$user, $date]);

echo json_encode(["status" => "ok"]);
