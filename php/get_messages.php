<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/chat_storage.php';

$user = auth_current_user();
if (!$user) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'unauthorized'], JSON_UNESCAPED_UNICODE);
    exit;
}

$me = $user['login'];
$with = trim($_GET['with'] ?? '');

if ($with === '' || $with === $me) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'bad_with'], JSON_UNESCAPED_UNICODE);
    exit;
}

$messages = chat_read_pair($me, $with, 120);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['me' => $me, 'with' => $with, 'messages' => $messages], JSON_UNESCAPED_UNICODE);
