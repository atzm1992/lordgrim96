<?php
require "config.php";

$pdo->exec("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS gym_days (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  date DATE NOT NULL,
  notes TEXT,
  UNIQUE KEY uq_user_date (user_id, date),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS user_goals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  monthly_goal INT NOT NULL DEFAULT 12,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

echo "Setup complete.\n";