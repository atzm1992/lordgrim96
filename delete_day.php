<?php
require "auth.php";
require "config.php";

$userId = require_auth();
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$date = (string)($data['date'] ?? '');

$dt = DateTime::createFromFormat("Y-m-d", $date);
if (!$dt || $dt->format("Y-m-d") !== $date) {
    http_response_code(400);
    echo json_encode(["error" => "invalid date"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM gym_days WHERE user_id = ? AND date = ?");
$stmt->execute([$userId, $date]);

echo json_encode(["status" => "ok"]);
