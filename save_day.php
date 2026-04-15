<?php
require "config.php)"require "config.php";
);
$stmt->execute([
  $data["user"],
  $data["date"]
]);

echo json_encode(["status" => "ok"]);


$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare(
  "INSERT INTO gym_days (user_name, gym_date)
