<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/storage_profiles.php';

$user = auth_current_user();
if (!$user) {
    header('Location: /auth.php');
    exit;
}

function norm_text(string $s, int $maxLen): string {
    $s = trim($s);
    $s = preg_replace('/\s+/', ' ', $s);
    if (mb_strlen($s) > $maxLen) $s = mb_substr($s, 0, $maxLen);
    return $s;
}

function parse_tags(string $raw): array {
    $raw = trim($raw);
    if ($raw === '') return [];

    // Разрешаем ввод: "#математика #англійська, python; c#"
    $raw = str_replace([",", ";"], " ", $raw);
    $parts = preg_split('/\s+/', $raw) ?: [];

    $tags = [];
    foreach ($parts as $t) {
        $t = trim($t);
        if ($t === '') continue;
        if ($t[0] === '#') $t = substr($t, 1);
        $t = mb_strtolower($t);
        $t = preg_replace('/[^0-9a-zа-щьюяєіїґ_+-]/iu', '', $t);
        if ($t === '') continue;
        $tags[$t] = true;
    }

    return array_values(array_keys($tags));
}

$login = $user['login'];

$display_name = norm_text((string)($_POST['display_name'] ?? ''), 40);
$bio         = norm_text((string)($_POST['bio'] ?? ''), 240);
$teaches     = norm_text((string)($_POST['teaches'] ?? ''), 120);
$learns      = norm_text((string)($_POST['learns'] ?? ''), 120);
$tags        = parse_tags((string)($_POST['tags'] ?? ''));

$profile = [
    'login'        => $login,
    'display_name' => $display_name !== '' ? $display_name : $login,
    'bio'          => $bio,
    'teaches'      => $teaches,
    'learns'       => $learns,
    'tags'         => $tags,
    'updated_at'   => date('c'),
];

profiles_upsert($login, $profile);

header('Location: /profile.php?saved=1');
exit;
