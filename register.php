<?php
require "session_init.php";
require "config.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$username = trim((string)($data['username'] ?? ''));
$password = (string)($data['password'] ?? '');

if ($username === '' || strlen($username) > 50 || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'invalid input']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'password too short (min 8 characters)']);
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed]);
    $userId = (int)$pdo->lastInsertId();

    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;

    echo json_encode(['status' => 'ok', 'username' => $username]);
} catch (PDOException $e) {
    http_response_code(409);
    echo json_encode(['error' => 'username already taken']);
}
