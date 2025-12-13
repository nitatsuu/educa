<?php
const MENTOR_REQ_FILE = __DIR__ . '/../data/mentorship_requests.txt';

function mentor_req_append(array $row): bool
{
    $line = json_encode($row, JSON_UNESCAPED_UNICODE);
    if ($line === false) return false;

    return file_put_contents(MENTOR_REQ_FILE, $line . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
}
