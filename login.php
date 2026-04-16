<?php
session_start();

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection (update with your own connection details)
    $host = 'localhost';
    $db = 'your_database';
    $user = 'your_username';
    $pass = 'your_password';

    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare('SELECT password FROM users WHERE username = ?');
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password using bcrypt
        if (password_verify($_POST['password'], $hashed_password)) {
            // Password is correct, set session variable
            $_SESSION['username'] = $_POST['username'];
            echo 'Login successful!';
        } else {
            echo 'Invalid username or password.';
        }
    } else {
        echo 'Invalid username or password.';
    }

    $stmt->close();
    $conn->close();
}
?>

<form method='post'>
    <input type='text' name='username' placeholder='Username' required>
    <input type='password' name='password' placeholder='Password' required>
    <input type='submit' value='Login'>
</form>
