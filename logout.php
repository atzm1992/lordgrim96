<?php
require "session_init.php";
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '',
              $params['secure'] ?? false, $params['httponly'] ?? false);
}
session_destroy();
header("Content-Type: application/json");
echo json_encode(['status' => 'ok']);
