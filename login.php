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

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'missing credentials']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'invalid credentials']);
    exit;
}

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $username;

echo json_encode(['status' => 'ok', 'username' => $username]);
