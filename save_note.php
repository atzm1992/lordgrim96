<?php

session_start();

// Database connection
db_host = 'localhost';
db_user = 'username';
db_password = 'password';
db_database = 'database';

$conn = new mysqli($db_host, $db_user, $db_password, $db_database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get the submitted data
$date = $_POST['date'];
$note = $_POST['note'];

// Validate date
$current_date = date('Y-m-d H:i:s');
if ($date < $current_date) {
    die('Past dates are not allowed.');
}

// Insert into gym_days table
$sql = "INSERT INTO gym_days (user_id, date, note) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iss', $user_id, $date, $note);

if ($stmt->execute()) {
    echo 'Note saved successfully.';
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>

