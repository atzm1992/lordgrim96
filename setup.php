<?php
require "config.php";

// Tabelle anlegen, falls sie nicht existiert
$sql = "
CREATE TABLE IF NOT EXISTS gym_days (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(50) NOT NULL,
  gym_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_entry (user_name, gym_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

$pdo->exec($sql);

echo "✅ Datenbank ist bereit.";
