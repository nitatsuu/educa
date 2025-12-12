<?php

const MSG_FILE = __DIR__ . '/../data/messages.txt';

function chat_append(array $msg): bool
{
    $line = json_encode($msg, JSON_UNESCAPED_UNICODE);
    if ($line === false) return false;

    return file_put_contents(MSG_FILE, $line . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
}

function chat_read_pair(string $a, string $b, int $limit = 100): array
{
    if (!file_exists(MSG_FILE)) return [];

    $lines = file(MSG_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $out = [];

    // идём с конца, чтобы быстро набрать последние сообщения
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $row = json_decode($lines[$i], true);
        if (!is_array($row)) continue;

        $from = (string)($row['from'] ?? '');
        $to   = (string)($row['to'] ?? '');

        $isPair =
            ($from === $a && $to === $b) ||
            ($from === $b && $to === $a);

        if ($isPair) {
            $out[] = $row;
            if (count($out) >= $limit) break;
        }
    }

    return array_reverse($out);
}
