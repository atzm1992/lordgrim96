<?php
// auth.php – session helper

function require_auth(): int {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['error' => 'unauthenticated']);
        exit;
    }
    return (int)$_SESSION['user_id'];
}
