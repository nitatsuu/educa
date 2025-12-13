<?php
require_once __DIR__ . '/auth.php';

$user = auth_current_user();
if (!$user) { header('Location: /auth.php'); exit; }
if (empty($user['premium'])) { header('Location: /profile.php'); exit; }

function norm(string $s, int $max): string {
  $s = trim($s);
  $s = preg_replace('/\s+/', ' ', $s);
  if (mb_strlen($s) > $max) $s = mb_substr($s, 0, $max);
  return $s;
}

function parse_tags(string $raw): array {
  $raw = str_replace([",",";"], " ", trim($raw));
  if ($raw === '') return [];
  $parts = preg_split('/\s+/', $raw) ?: [];
  $tags = [];
  foreach ($parts as $t){
    $t = trim($t);
    if ($t === '') continue;
    if ($t[0] === '#') $t = substr($t, 1);
    $t = mb_strtolower($t);
    $t = preg_replace('/[^0-9a-zа-щьюяєіїґ_+-]/iu', '', $t);
    if ($t !== '') $tags[$t] = true;
  }
  return array_values(array_keys($tags));
}

$title = norm($_POST['title'] ?? '', 80);
$desc  = norm($_POST['desc'] ?? '', 240);
$img   = (int)($_POST['img'] ?? 0);
$tags  = parse_tags($_POST['tags'] ?? '');

if ($title === '') {
  header('Location: /create_course.php');
  exit;
}

$course = [
  'id' => 'c_' . bin2hex(random_bytes(4)),
  'title' => $title,
  'desc' => $desc,
  'tags' => $tags,
  'img' => $img,
  'author' => $user['login'],
  'created_at' => date('c'),
];

$line = json_encode($course, JSON_UNESCAPED_UNICODE) . PHP_EOL;
file_put_contents(__DIR__ . '/../data/courses.txt', $line, FILE_APPEND | LOCK_EX);

header('Location: /tutors.php?course_added=1');
exit;
