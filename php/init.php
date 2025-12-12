<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/auth.php';

$user = auth_current_user();

// $currentUser используется в header.php
if ($user) {
    $currentUser = [
        'login'        => $user['login'],
        'yellow_stars' => (int)($user['yellow'] ?? 0),
        'blue_stars'   => (int)($user['blue'] ?? 0),
    ];
} else {
    $currentUser = [
        'login'        => 'Гість',
        'yellow_stars' => 0,
        'blue_stars'   => 0,
    ];
}
