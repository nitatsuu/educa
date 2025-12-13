<?php

const COURSES_FILE = __DIR__ . '/../data/courses.txt';

function courses_load_all(): array
{
    $out = [];

    // 1) читаем созданные курсы (JSONL)
    if (file_exists(COURSES_FILE)) {
        $lines = file(COURSES_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $row = json_decode($line, true);
            if (!is_array($row) || empty($row['id']) || empty($row['title'])) continue;
            $out[] = $row;
        }
    }

    // 2) если пусто — добавим демо-курсы, чтобы интерфейс не был пустым
    if (count($out) === 0) {
        $out = [
            [
                'id' => 'c_demo_1',
                'title' => 'Python з нуля',
                'desc' => 'Основи, практика та маленькі проєкти.',
                'tags' => ['python', 'програмування'],
                'img' => 0,
                'author' => 'platform',
                'created_at' => date('c'),
            ],
            [
                'id' => 'c_demo_2',
                'title' => 'Англійська: Speaking',
                'desc' => 'Розмовна практика та словник під твій рівень.',
                'tags' => ['англійська', 'speaking'],
                'img' => 1,
                'author' => 'platform',
                'created_at' => date('c'),
            ],
            [
                'id' => 'c_demo_3',
                'title' => 'Математика: підготовка',
                'desc' => 'Задачі, формули, розбір тем без води.',
                'tags' => ['математика', 'алгебра'],
                'img' => 2,
                'author' => 'platform',
                'created_at' => date('c'),
            ],
        ];
    }

    return $out;
}

function courses_find_by_id(string $id): ?array
{
    $all = courses_load_all();
    foreach ($all as $c) {
        if (($c['id'] ?? '') === $id) return $c;
    }
    return null;
}
