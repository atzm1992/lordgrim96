<?php
require "auth.php";
require "config.php";

$userId = require_auth();
header("Content-Type: application/json");

$stmt = $pdo->prepare("SELECT date, notes FROM gym_days WHERE user_id = ? ORDER BY date");
$stmt->execute([$userId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dates = [];
$notes = [];
foreach ($rows as $row) {
    $dates[] = $row['date'];
    if ($row['notes'] !== null && $row['notes'] !== '') {
        $notes[$row['date']] = $row['notes'];
    }
}

$stmtG = $pdo->prepare("SELECT monthly_goal FROM user_goals WHERE user_id = ?");
$stmtG->execute([$userId]);
$goal = $stmtG->fetchColumn();

echo json_encode([
    'dates'         => $dates,
    'notes'         => $notes,
    'monthly_goal'  => $goal !== false ? (int)$goal : 12,
    'username'      => $_SESSION['username'],
]);
