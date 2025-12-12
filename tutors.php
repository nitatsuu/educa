<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/tutors_data.php';

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
                    <div class="tutor-avatar">🎓</div>
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

                <button class="btn-primary tutor-action" type="button">
                    Записатися (демо)
                </button>
            </article>
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
