<?php
const MERCH_FILE = __DIR__ . '/../data/merch.json';

function merch_load_all(): array
{
    if (!file_exists(MERCH_FILE)) return [];
    $raw = file_get_contents(MERCH_FILE);
    if ($raw === false) return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function merch_find(string $id): ?array
{
    $all = merch_load_all();
    foreach ($all as $m) {
        if (($m['id'] ?? '') === $id) return $m;
    }
    return null;
}
