<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/storage_users.php';

$user = auth_current_user();
if (!$user) {
    header('Location: /auth.php');
    exit;
}

$login = $user['login'];
$user['premium'] = true;

users_update($login, $user);

header('Location: /exchange.php?premium=1');
exit;
