<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/tutors_data.php';

$tutors = tutors_load_all();
$recommended = array_slice($tutors, 0, 6);
$recRow1 = array_slice($recommended, 0, 3);
$recRow2 = array_slice($recommended, 3, 3);
?>

<section class="page-section">
    <h1>Головна</h1>

    <div class="card">
        <h2>Важливе оголошення</h2>
        <p class="muted">Демо-версія: платежі та підтвердження занять поки імітуються.</p>
    </div>

    <div class="home-recommended">
        <div class="home-head">
            <h2>Рекомендовані репетитори</h2>
            <a class="btn-link" href="/tutors.php">Дивитися всіх →</a>
        </div>

        <!-- Десктоп: сітка -->
        <div class="tutors-grid home-grid">
            <?php foreach ($recommended as $t): ?>
                <?php $tags = is_array($t['tags'] ?? null) ? $t['tags'] : []; ?>
                <article class="tutor-card tutor-card--compact">
                    <div class="tutor-top">
                        <div class="tutor-avatar">🎓</div>
                        <div class="tutor-price" title="Вартість у жовтих зірках">
                            ★ <?= (int)($t['price_yellow'] ?? 0) ?>
                        </div>
                    </div>
                    <h3 class="tutor-name"><?= htmlspecialchars($t['name'] ?? '—') ?></h3>
                    <div class="tutor-title muted"><?= htmlspecialchars($t['title'] ?? '') ?></div>
                    <div class="tutor-tags">
                        <?php foreach ($tags as $tag): ?>
                            <span class="tag">#<?= htmlspecialchars(mb_strtolower((string)$tag)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Мобілка: 2 ряди, горизонтальна прокрутка -->
        <div class="home-carousel">
            <div class="carousel-row">
                <?php foreach ($recRow1 as $t): ?>
                    <?php $tags = is_array($t['tags'] ?? null) ? $t['tags'] : []; ?>
                    <article class="tutor-card tutor-card--compact">
                        <div class="tutor-top">
                            <div class="tutor-avatar">🎓</div>
                            <div class="tutor-price">★ <?= (int)($t['price_yellow'] ?? 0) ?></div>
                        </div>
                        <h3 class="tutor-name"><?= htmlspecialchars($t['name'] ?? '—') ?></h3>
                        <div class="tutor-title muted"><?= htmlspecialchars($t['title'] ?? '') ?></div>
                        <div class="tutor-tags">
                            <?php foreach ($tags as $tag): ?>
                                <span class="tag">#<?= htmlspecialchars(mb_strtolower((string)$tag)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="carousel-row">
                <?php foreach ($recRow2 as $t): ?>
                    <?php $tags = is_array($t['tags'] ?? null) ? $t['tags'] : []; ?>
                    <article class="tutor-card tutor-card--compact">
                        <div class="tutor-top">
                            <div class="tutor-avatar">🎓</div>
                            <div class="tutor-price">★ <?= (int)($t['price_yellow'] ?? 0) ?></div>
                        </div>
                        <h3 class="tutor-name"><?= htmlspecialchars($t['name'] ?? '—') ?></h3>
                        <div class="tutor-title muted"><?= htmlspecialchars($t['title'] ?? '') ?></div>
                        <div class="tutor-tags">
                            <?php foreach ($tags as $tag): ?>
                                <span class="tag">#<?= htmlspecialchars(mb_strtolower((string)$tag)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/footer.php';
?>
