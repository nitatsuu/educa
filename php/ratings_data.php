<?php

const RATINGS_FILE = __DIR__ . '/../data/ratings.json';

function ratings_load_all(): array
{
    if (!file_exists(RATINGS_FILE)) return [];

    $raw = file_get_contents(RATINGS_FILE);
    if ($raw === false) return [];

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}
