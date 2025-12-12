<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/chat_storage.php';

$user = auth_current_user();
if (!$user) {
    http_response_code(401);
    exit;
}

$me = $user['login'];
$with = trim($_POST['with'] ?? '');
$text = trim($_POST['text'] ?? '');

if ($with === '' || $with === $me || $text === '') {
    http_response_code(400);
    exit;
}

// лёгкая нормализация
$text = preg_replace('/\s+/', ' ', $text);
if (mb_strlen($text) > 500) $text = mb_substr($text, 0, 500);

$msg = [
    'ts' => date('c'),
    'from' => $me,
    'to' => $with,
    'text' => $text,
];

chat_append($msg);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
