<?php
require "config.php";

header("Content-Type: application/json");

$user = isset($_GET["user"]) ? trim((string)$_GET["user"]) : "";

if ($user === "" || strlen($user) > 50 || !preg_match('/^[\w\-]+$/', $user)) {
  http_response_code(400);
  echo json_encode(["error" => "invalid user"]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT gym_date
  FROM gym_days
  WHERE user_name = ?
");

$stmt->execute([$user]);
$dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode(["dates" => $dates]);
