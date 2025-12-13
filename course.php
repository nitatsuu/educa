<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/courses_data.php';

$id = trim($_GET['id'] ?? '');
$course = $id !== '' ? courses_find_by_id($id) : null;

if (!$course) {
    echo '<section class="page-section"><div class="card"><h1>Курс не знайдено</h1><p class="muted">Перевір посилання.</p></div></section>';
    require_once __DIR__ . '/footer.php';
    exit;
}

$title = (string)($course['title'] ?? '—');
$desc  = (string)($course['desc'] ?? '');
$tags  = is_array($course['tags'] ?? null) ? $course['tags'] : [];
$imgN  = (int)($course['img'] ?? 0);
$author = (string)($course['author'] ?? 'platform');

// Демо-відео (можешь заменить потом на реальные ссылки)
$videos = [
    ['title' => 'Вступ', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
    ['title' => 'Блок 1', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
    ['title' => 'Блок 2', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
];
?>

<section class="page-section">
    <div class="course-hero card">
        <div class="course-cover" style="background-image:url('/img/courses/course<?= $imgN ?>.jpg');"></div>

        <div class="course-info">
            <h1 class="course-title"><?= htmlspecialchars($title) ?></h1>
            <div class="muted">Автор: <?= htmlspecialchars($author) ?></div>

            <?php if ($desc !== ''): ?>
                <p class="course-desc muted"><?= htmlspecialchars($desc) ?></p>
            <?php endif; ?>

            <div class="course-tags">
                <?php foreach ($tags as $t): ?>
                    <span class="tag">#<?= htmlspecialchars(mb_strtolower((string)$t)) ?></span>
                <?php endforeach; ?>
            </div>

            <div class="course-actions">
                <button class="btn-primary" type="button" disabled>Записатися (демо)</button>
            </div>
        </div>
    </div>

    <h2 style="margin-top:16px;">Відео</h2>

    <div class="course-videos">
        <?php foreach ($videos as $v): ?>
            <a class="video-card" href="<?= htmlspecialchars($v['url']) ?>" target="_blank" rel="noreferrer">
                <div class="video-thumb">▶</div>
                <div>
                    <div class="video-title"><?= htmlspecialchars($v['title']) ?></div>
                    <div class="muted" style="font-size:13px;">Відкрити відео (демо)</div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
