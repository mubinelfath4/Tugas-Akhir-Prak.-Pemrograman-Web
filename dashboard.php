<?php
require 'config.php';
require_login();
$st = $pdo->prepare('SELECT a.*, c.name cat FROM articles a JOIN categories c ON c.id=a.category_id
                     WHERE a.user_id=? ORDER BY a.created_at DESC');
$st->execute([$_SESSION['user']['id']]);
$list = $st->fetchAll();
head('Dashboard');
?>
<h2 class="sec">Artikel Saya</h2>
<p><a class="btn" href="form.php">+ Tulis Artikel</a></p>
<table>
    <tr>
        <th>Judul</th>
        <th>Kategori</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($list as $a): ?>
    <tr>
        <td><a href="article.php?id=<?= $a['id'] ?>"><?= e($a['title']) ?></a></td>
        <td><?= e($a['cat']) ?></td>
        <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
        <td><a href="form.php?id=<?= $a['id'] ?>">Edit</a>
            <form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Hapus artikel ini?')">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <button class="link">Hapus</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$list): ?>
    <tr>
        <td colspan="4">Belum ada artikel.</td>
    </tr>
    <?php endif; ?>
</table>
    <?php foot(); ?>
