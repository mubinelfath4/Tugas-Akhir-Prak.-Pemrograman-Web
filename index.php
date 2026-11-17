<?php
require 'config.php';
$where = []; $p = [];
if (!empty($_GET['cat'])) { $where[] = 'a.category_id = ?'; $p[] = (int)$_GET['cat']; }
if (!empty($_GET['q'])) { $where[] = 'a.title LIKE ?'; $p[] = '%' . $_GET['q'] . '%'; }
$sql = 'SELECT a.*, u.name author, c.name cat FROM articles a
        JOIN users u ON u.id=a.user_id JOIN categories c ON c.id=a.category_id'
     . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY a.created_at DESC';
$st = $pdo->prepare($sql); $st->execute($p);
$list = $st->fetchAll();
head('Beranda');
?>
<h2 class="sec">Berita Terbaru</h2>
<?php if (!$list): ?><p>Belum ada artikel.</p><?php endif; ?>
<div class="grid">
  <?php foreach ($list as $a): ?>
  <a class="card" href="article.php?id=<?= $a['id'] ?>">
    <?php if ($a['image_url']): ?><img src="<?= e($a['image_url']) ?>" alt="" referrerpolicy="no-referrer"><?php endif; ?>
    <div class="body"><span class="tag"><?= e($a['cat']) ?></span>
    <h3><?= e($a['title']) ?></h3>
    <small><?= e($a['author']) ?> &middot; <?= date('d M Y', strtotime($a['created_at'])) ?></small></div>
  </a>
  <?php endforeach; ?>
</div>
<?php foot(); ?>

