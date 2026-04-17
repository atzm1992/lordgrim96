<?php
// auth.php – session helper
require_once __DIR__ . "/session_init.php";

function require_auth(): int {
    if (empty($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['error' => 'unauthenticated']);
        exit;
    }
    return (int)$_SESSION['user_id'];
}
