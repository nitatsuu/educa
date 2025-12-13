<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/storage_users.php';
require_once __DIR__ . '/merch_data.php';

$user = auth_current_user();
if (!$user) { header('Location: /auth.php'); exit; }

$id = trim($_POST['id'] ?? '');
$item = $id !== '' ? merch_find($id) : null;

if (!$item) {
    header('Location: /merch.php?err=bad');
    exit;
}

$price = (int)($item['price_blue'] ?? 0);
if ($price <= 0) {
    header('Location: /merch.php?err=bad');
    exit;
}

$login = $user['login'];
$current = users_find($login);
if (!$current) { header('Location: /auth.php'); exit; }

if ((int)($current['blue'] ?? 0) < $price) {
    header('Location: /merch.php?err=not_enough');
    exit;
}

// списываем
$current['blue'] = (int)$current['blue'] - $price;
users_update($login, $current);

// лог покупки (демо)
$purchase = [
    'ts' => date('c'),
    'buyer' => $login,
    'item_id' => $id,
    'title' => (string)($item['title'] ?? ''),
    'price_blue' => $price
];
file_put_contents(__DIR__ . '/../data/purchases.txt', json_encode($purchase, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);

header('Location: /merch.php?ok=1');
exit;
