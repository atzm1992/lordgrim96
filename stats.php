<?php
// stats.php – per-user training statistics (requires login)
session_start();
require "config.php";

if (empty($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

$currentYear = (int)date('Y');

// All users with their gym-day counts for the current year
$stmtAll = $pdo->prepare("
    SELECT u.username, COUNT(g.id) AS days
    FROM users u
    LEFT JOIN gym_days g
      ON g.user_id = u.id AND YEAR(g.date) = ?
    GROUP BY u.id, u.username
    ORDER BY days DESC, u.username ASC
");
$stmtAll->execute([$currentYear]);
$allUsers = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

// Monthly breakdown for the logged-in user
$stmtMonth = $pdo->prepare("
    SELECT MONTH(date) AS m, COUNT(*) AS cnt
    FROM gym_days
    WHERE user_id = ? AND YEAR(date) = ?
    GROUP BY MONTH(date)
    ORDER BY MONTH(date)
");
$stmtMonth->execute([$_SESSION['user_id'], $currentYear]);
$monthly = $stmtMonth->fetchAll(PDO::FETCH_KEY_PAIR);

$monthNames = ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];
$totalDays  = array_sum(array_column($allUsers, 'days'));
$loggedUser = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Gym Statistiken</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; text-align: center; }
    h1   { margin-bottom: 5px; }
    .subtitle { color: #666; margin-bottom: 20px; }
    .card { background: white; border-radius: 10px; padding: 20px; max-width: 500px; margin: 0 auto 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f9f9f9; font-weight: bold; }
    .medal { font-size: 1.2em; }
    .bar-wrap { background: #eee; border-radius: 4px; height: 14px; margin-top: 4px; }
    .bar { background: #4caf50; border-radius: 4px; height: 14px; }
    a.back { display: inline-block; margin-bottom: 16px; color: #4caf50; text-decoration: none; font-weight: bold; }
    a.back:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <a href="index.html" class="back">← Zurück zum Kalender</a>
  <h1>Gym Statistiken <?= $currentYear ?></h1>
  <p class="subtitle">Eingeloggt als <strong><?= htmlspecialchars($loggedUser) ?></strong></p>

  <div class="card">
    <h2>🏆 Benutzer-Vergleich</h2>
    <table>
      <tr><th>#</th><th>Name</th><th>Tage</th><th></th></tr>
      <?php foreach ($allUsers as $i => $u):
        $medal = $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : ''));
        $pct   = $totalDays > 0 ? round($u['days'] / max(array_column($allUsers, 'days')) * 100) : 0;
      ?>
      <tr>
        <td><?= $medal ?: ($i + 1) ?></td>
        <td><?= htmlspecialchars($u['username']) ?><?= $u['username'] === $loggedUser ? ' (du)' : '' ?></td>
        <td><?= (int)$u['days'] ?></td>
        <td style="width:120px">
          <div class="bar-wrap"><div class="bar" style="width:<?= $pct ?>%"></div></div>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <div class="card">
    <h2>📅 Deine Monate <?= $currentYear ?></h2>
    <table>
      <tr><th>Monat</th><th>Trainingstage</th></tr>
      <?php for ($m = 1; $m <= 12; $m++): $cnt = (int)($monthly[$m] ?? 0); ?>
      <tr>
        <td><?= $monthNames[$m - 1] ?></td>
        <td><?= $cnt ?> <?= str_repeat('🟩', min($cnt, 20)) ?></td>
      </tr>
      <?php endfor; ?>
    </table>
  </div>
</body>
</html>
