<?php
require_once __DIR__ . '/storage_users.php';

function ensure_tutor_user(string $login): void
{
    $u = users_find($login);
    if ($u) return;

    $u = [
        'login' => $login,
        'pass_hash' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
        'yellow' => 0,
        'blue' => 0,
        'premium' => false,
        'system' => true,
        'created_at' => date('c'),
    ];

    users_append($u);
}
