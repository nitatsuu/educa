<?php

const PROFILES_FILE = __DIR__ . '/../data/profiles.txt';

function profiles_read_all(): array
{
    if (!file_exists(PROFILES_FILE)) return [];

    $lines = file(PROFILES_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $profiles = [];

    foreach ($lines as $line) {
        $row = json_decode($line, true);
        if (!is_array($row) || empty($row['login'])) continue;
        $profiles[$row['login']] = $row;
    }

    return $profiles;
}

function profiles_find(string $login): ?array
{
    $all = profiles_read_all();
    return $all[$login] ?? null;
}

function profiles_upsert(string $login, array $profile): bool
{
    $all = profiles_read_all();
    $all[$login] = $profile;

    $lines = [];
    foreach ($all as $p) {
        $lines[] = json_encode($p, JSON_UNESCAPED_UNICODE);
    }

    return file_put_contents(PROFILES_FILE, implode(PHP_EOL, $lines) . PHP_EOL, LOCK_EX) !== false;
}

