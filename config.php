<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=kabarin;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function logged_in() { return isset($_SESSION['user']); }
function require_login() {
    if (!logged_in()) { header('Location: login.php'); exit; }
}
function head($title = 'kabarin') {
    global $pdo;
    $cats = $pdo->query('SELECT * FROM categories')->fetchAll();
    ?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> - Kabarin</title>
<link rel="stylesheet" href="style.css"></head>
<body>
  <header class="top">
  <div class="wrap bar">
    <a class="logo" href="index.php">kabarin</a>
    <form class="search" action="index.php"><input name="q" placeholder="Cari berita..." value="<?= e($_GET['q'] ?? '') ?>"></form>
    <nav>
      <?php if (logged_in()): ?>
        <a href="dashboard.php">Halo, <?= e($_SESSION['user']['name']) ?></a>
        <a class="btn" href="form.php">+ Tulis</a>
        <a href="logout.php">Keluar</a>
      <?php else: ?>
        <a href="login.php">Masuk</a><a class="btn" href="register.php">Daftar</a>
      <?php endif; ?>
    </nav>
  </div>
  <div class="wrap cats"><a href="index.php">Terbaru</a>
    <?php foreach ($cats as $c): ?><a href="index.php?cat=<?= $c['id'] ?>"><?= e($c['name']) ?></a><?php endforeach; ?>
  </div>
  </header>
  <main class="wrap">
<?php }
function foot() { echo '</main><footer class="wrap foot">&copy; ' . date('Y') . ' Kabarin - Proyek Akhir</footer></body></html>'; }
