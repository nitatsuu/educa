<?php
require_once __DIR__ . '/storage_users.php';
require_once __DIR__ . '/auth.php';

$login = trim($_POST['login'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($login === '' || $password === '') {
    header('Location: /auth.php');
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]{3,24}$/', $login)) {
    // простой формат логина
    header('Location: /auth.php');
    exit;
}

if (users_find($login)) {
    // логин занят
    header('Location: /auth.php');
    exit;
}

$user = [
    'login' => $login,
    'pass_hash' => password_hash($password, PASSWORD_DEFAULT),
    'yellow' => 0,
    'blue' => 0,
    'premium' => false,
    'created_at' => date('c'),
];

if (!users_append($user)) {
    header('Location: /auth.php');
    exit;
}

auth_login($login);
header('Location: /profile.php');
exit;
