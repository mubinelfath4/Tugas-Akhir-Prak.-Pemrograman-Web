<?php
require 'config.php';
require_login();
$uid = $_SESSION['user']['id'];
$id = (int)($_GET['id'] ?? 0);
$a = ['title' => '', 'image_url' => '', 'content' => '', 'category_id' => 0];
if ($id) {
    $st = $pdo->prepare('SELECT * FROM articles WHERE id=? AND user_id=?');
    $st->execute([$id, $uid]);
    $a = $st->fetch();
    if (!$a) { http_response_code(403); exit('Tidak boleh mengedit artikel ini'); }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = [trim($_POST['title']), trim($_POST['image_url']), trim($_POST['content']), (int)$_POST['category_id']];
    if ($id) {
        $pdo->prepare('UPDATE articles SET title=?, image_url=?, content=?, category_id=? WHERE id=? AND user_id=?')
            ->execute([...$d, $id, $uid]);
    } else {
        $pdo->prepare('INSERT INTO articles (title,image_url,content,category_id,user_id) VALUES (?,?,?,?,?)')
            ->execute([...$d, $uid]);
    }
    header('Location: dashboard.php'); exit;
}
$cats = $pdo->query('SELECT * FROM categories')->fetchAll();
head($id ? 'Edit Artikel' : 'Tulis Artikel');
?>
<form class="box wide" method="post"><h2><?= $id ? 'Edit' : 'Tulis' ?> Artikel</h2>
    <label>Judul</label>
    <input name="title" value="<?= e($a['title']) ?>" required>
    <label>Kategori</label>
    <select name="category_id"><?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $c['id'] == $a['category_id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>URL Gambar (opsional)</label>
    <input name="image_url" value="<?= e($a['image_url']) ?>">
    <label>Isi</label>
    <textarea name="content" rows="12" required><?= e($a['content']) ?></textarea>
    <button class="btn">Simpan</button>
</form>
<?php foot(); ?>
