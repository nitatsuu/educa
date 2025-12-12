<?php

const TUTORS_FILE = __DIR__ . '/../data/tutors.json';

function tutors_load_all(): array
{
    if (!file_exists(TUTORS_FILE)) return [];

    $raw = file_get_contents(TUTORS_FILE);
    if ($raw === false) return [];

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}
