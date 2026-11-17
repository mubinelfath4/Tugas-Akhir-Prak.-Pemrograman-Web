<?php
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); $email = trim($_POST['email']); $pw = $_POST['password'];
    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pw) < 6) {
        $err = 'Isi nama, email valid, dan password minimal 6 karakter.';
    } else {
        $c = $pdo->prepare('SELECT id FROM users WHERE email=?'); $c->execute([$email]);
        if ($c->fetch()) $err = 'Email sudah terdaftar.';
        else {
            $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)')
                ->execute([$name, $email, password_hash($pw, PASSWORD_DEFAULT)]);
            header('Location: login.php?registered=1'); exit;
        }
    }
}
head('Daftar');
?>
<form class="box" method="post"><h2>Daftar</h2>
    <?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
    <label>Nama</label>
    <input name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button class="btn">Daftar</button>
    <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
</form>
<?php foot(); ?>
