<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/storage_users.php';

$user = auth_current_user();
if (!$user) {
    header('Location: /auth.php');
    exit;
}

$login = $user['login'];

// Правила обміну
$BLUE_COST = 1;
$YELLOW_GAIN = 1;

if (($user['blue'] ?? 0) < $BLUE_COST) {
    header('Location: /exchange.php?err=not_enough');
    exit;
}

// Оновлюємо баланс
$user['blue'] -= $BLUE_COST;
$user['yellow'] += $YELLOW_GAIN;

users_update($login, $user);

header('Location: /exchange.php?ok=1');
exit;
