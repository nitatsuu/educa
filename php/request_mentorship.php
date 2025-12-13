<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/storage_users.php';
require_once __DIR__ . '/mentorship_storage.php';

$user = auth_current_user();
if (!$user) { header('Location: /auth.php'); exit; }

$me = $user['login'];
$mentor = trim($_POST['mentor'] ?? '');

if ($mentor === '' || $mentor === $me) {
    header('Location: /profile.php');
    exit;
}

if (!users_find($mentor)) {
    header('Location: /profile.php?u=' . urlencode($mentor) . '&err=nouser');
    exit;
}

mentor_req_append([
    'ts' => date('c'),
    'from' => $me,
    'to' => $mentor,
    'status' => 'pending'
]);

header('Location: /profile.php?u=' . urlencode($mentor) . '&req=1');
exit;
