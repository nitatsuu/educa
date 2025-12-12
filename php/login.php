<?php
require_once __DIR__ . '/storage_users.php';
require_once __DIR__ . '/auth.php';

$login = trim($_POST['login'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($login === '' || $password === '') {
    header('Location: /auth.php');
    exit;
}

$user = users_find($login);
if (!$user) {
    header('Location: /auth.php');
    exit;
}

$hash = $user['pass_hash'] ?? '';
if (!is_string($hash) || !password_verify($password, $hash)) {
    header('Location: /auth.php');
    exit;
}

auth_login($login);
header('Location: /index.php');
exit;
