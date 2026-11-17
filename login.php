<?php
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = $pdo->prepare('SELECT * FROM users WHERE email=?');
    $st->execute([trim($_POST['email'])]);
    $u = $st->fetch();
    if ($u && password_verify($_POST['password'], $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name']];
        header('Location: dashboard.php'); exit;
    }
    $err = 'Email atau password salah.';
}
head('Masuk');
?>
<form class="box" method="post"><h2>Masuk</h2>
    <?php if (isset($_GET['registered'])): ?><p class="ok">Pendaftaran berhasil, silakan masuk.</p><?php endif; ?>
    <?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button class="btn">Masuk</button>
    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
</form>
<?php foot(); ?>
