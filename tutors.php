<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/tutors_data.php';
require_once __DIR__ . '/php/courses_data.php';


$courses = courses_load_all();
$tutors = tutors_load_all();
?>

<section class="page-section">
    <h1>Репетитори</h1>

    <div class="tutors-toolbar card">
        <div class="toolbar-row">
            <label class="field" style="margin:0;">
                <span>Пошук за тегами або текстом</span>
                <input id="tutorSearch" type="text" placeholder="#python #математика або імʼя/опис">
            </label>
            <button class="btn-secondary" type="button" id="clearSearch">Очистити</button>
        </div>
        <p class="muted" style="margin:10px 0 0;">
            Порада: введи <b>#тег</b>, наприклад <b>#python</b> або <b>#англійська</b>.
        </p>
    </div>

    <div class="tutors-grid" id="tutorsGrid">
        <?php foreach ($tutors as $t): ?>
            <?php
                $tags = $t['tags'] ?? [];
                if (!is_array($tags)) $tags = [];
                $tagsLower = array_map(fn($x) => mb_strtolower((string)$x), $tags);

                $searchBlob = mb_strtolower(
                    ($t['name'] ?? '') . ' ' .
                    ($t['title'] ?? '') . ' ' .
                    ($t['desc'] ?? '') . ' ' .
                    implode(' ', $tagsLower)
                );

                $dataTags = implode(',', $tagsLower);
            ?>
            <article class="tutor-card" data-tags="<?= htmlspecialchars($dataTags) ?>" data-blob="<?= htmlspecialchars($searchBlob) ?>">
                <div class="tutor-top">
                    <?php $imgN = (int)($t['img'] ?? 0); ?>
                    <div class="tutor-avatar" style="background-image:url('/img/tutors/tutor<?= $imgN ?>.jpg');">
                    </div>

                    <div class="tutor-price" title="Вартість у жовтих зірках">
                        ★ <?= (int)($t['price_yellow'] ?? 0) ?>
                    </div>
                </div>

                <h2 class="tutor-name"><?= htmlspecialchars($t['name'] ?? '—') ?></h2>
                <div class="tutor-title muted"><?= htmlspecialchars($t['title'] ?? '') ?></div>

                <div class="tutor-meta">
                    <span class="badge">Рейтинг: <?= htmlspecialchars((string)($t['rating'] ?? '—')) ?></span>
                </div>

                <p class="tutor-desc muted"><?= htmlspecialchars($t['desc'] ?? '') ?></p>

                <div class="tutor-tags">
                    <?php foreach ($tagsLower as $tag): ?>
                        <span class="tag">#<?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:auto;">
                    <a class="btn-primary" href="/tutor.php?id=<?= urlencode((string)($t['id'] ?? '')) ?>">Профіль</a>
                    <a class="btn-secondary" href="/chat.php?with=<?= urlencode((string)($t['login'] ?? '')) ?>">Чат</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>


    
    <div class="section-divider">
        <div class="divider-title">КУРСИ</div>
    </div>

    <div class="courses-grid" id="coursesGrid">
        <?php foreach ($courses as $c): ?>
            <?php
                $tags = is_array($c['tags'] ?? null) ? $c['tags'] : [];
                $tagsLower = array_map(fn($x) => mb_strtolower((string)$x), $tags);
                $blob = mb_strtolower(($c['title'] ?? '') . ' ' . ($c['desc'] ?? '') . ' ' . implode(' ', $tagsLower));
                $dataTags = implode(',', $tagsLower);
                $imgN = (int)($c['img'] ?? 0);
            ?>
            <a class="course-card" href="/course.php?id=<?= urlencode((string)$c['id']) ?>"
            data-tags="<?= htmlspecialchars($dataTags) ?>"
            data-blob="<?= htmlspecialchars($blob) ?>">
                <div class="course-card-cover" style="background-image:url('/img/courses/course<?= $imgN ?>.jpg');"></div>
                <div class="course-card-body">
                    <div class="course-card-title"><?= htmlspecialchars((string)($c['title'] ?? '—')) ?></div>
                    <div class="muted course-card-desc"><?= htmlspecialchars((string)($c['desc'] ?? '')) ?></div>
                    <div class="course-card-tags">
                        <?php foreach ($tagsLower as $t): ?>
                            <span class="tag">#<?= htmlspecialchars($t) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="course-card-open">Відкрити →</div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.initTutorSearch) window.initTutorSearch();
});
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
