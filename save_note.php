<?php
require "auth.php";
require "config.php";

$userId = require_auth();
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$date  = (string)($data['date']  ?? '');
$notes = substr(trim((string)($data['notes'] ?? '')), 0, 500); // max 500 chars (matches client-side maxlength)

$dt = DateTime::createFromFormat("Y-m-d", $date);
if (!$dt || $dt->format("Y-m-d") !== $date) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid date']);
    exit;
}

$stmt = $pdo->prepare("UPDATE gym_days SET notes = ? WHERE user_id = ? AND date = ?");
$stmt->execute([$notes, $userId, $date]);

if ($stmt->rowCount() === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'day not found']);
    exit;
}

echo json_encode(['status' => 'ok']);
