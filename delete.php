<?php
require 'config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare('DELETE FROM articles WHERE id=? AND user_id=?')
        ->execute([(int)$_POST['id'], $_SESSION['user']['id']]);
}
header('Location: dashboard.php');
