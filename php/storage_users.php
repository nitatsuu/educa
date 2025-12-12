<?php

const USERS_FILE = __DIR__ . '/../data/users.txt';

function users_read_all(): array
{
    if (!file_exists(USERS_FILE)) {
        return [];
    }

    $lines = file(USERS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $users = [];

    foreach ($lines as $line) {
        $row = json_decode($line, true);
        if (!is_array($row) || empty($row['login'])) {
            continue;
        }
        $users[$row['login']] = $row;
    }

    return $users;
}

function users_find(string $login): ?array
{
    $users = users_read_all();
    return $users[$login] ?? null;
}

function users_append(array $user): bool
{
    $dir = dirname(USERS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $line = json_encode($user, JSON_UNESCAPED_UNICODE);
    if ($line === false) {
        return false;
    }

    // LOCK_EX чтобы не было гонок при записи
    return file_put_contents(USERS_FILE, $line . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
}

function users_update(string $login, array $newUser): bool
{
    $users = users_read_all();
    $users[$login] = $newUser;

    $lines = [];
    foreach ($users as $u) {
        $lines[] = json_encode($u, JSON_UNESCAPED_UNICODE);
    }

    return file_put_contents(USERS_FILE, implode(PHP_EOL, $lines) . PHP_EOL, LOCK_EX) !== false;
}
