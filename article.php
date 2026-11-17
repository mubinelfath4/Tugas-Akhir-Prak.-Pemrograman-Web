<?php
require 'config.php';
$st = $pdo->prepare('SELECT a.*, u.name author, c.name cat FROM articles a
  JOIN users u ON u.id=a.user_id JOIN categories c ON c.id=a.category_id WHERE a.id=?');
$st->execute([(int)($_GET['id'] ?? 0)]);
$a = $st->fetch();
if (!$a) { http_response_code(404); exit('Artikel tidak ditemukan'); }
head($a['title']);
?>
  <article class="read">
    <span class="tag"><?= e($a['cat']) ?></span>
    <h1><?= e($a['title']) ?></h1>
    <small><?= e($a['author']) ?> &middot; <?= date('d M Y H:i', strtotime($a['created_at'])) ?></small>
    <?php if ($a['image_url']): ?><img src="<?= e($a['image_url']) ?>" alt="" referrerpolicy="no-referrer"><?php endif; ?>
    <div class="content"><?= nl2br(e($a['content'])) ?></div>
    <?php if (logged_in() && $_SESSION['user']['id'] == $a['user_id']): ?>
      <p><a class="btn" href="form.php?id=<?= $a['id'] ?>">Edit</a></p>
    <?php endif; ?>
  </article>
  <?php foot(); ?>
