<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/tutors_data.php';
require_once __DIR__ . '/php/storage_profiles.php';
require_once __DIR__ . '/php/tutor_accounts.php';

$id = trim($_GET['id'] ?? '');
$tutors = tutors_load_all();

$tutor = null;
foreach ($tutors as $t) {
    if (($t['id'] ?? '') === $id) { $tutor = $t; break; }
}

if (!$tutor) {
    echo '<section class="page-section"><div class="card"><h1>Репетитора не знайдено</h1><p class="muted">Перевір посилання.</p></div></section>';
    require_once __DIR__ . '/footer.php';
    exit;
}

$login = (string)($tutor['login'] ?? ('tutor_' . ($tutor['id'] ?? 'x')));
ensure_tutor_user($login);

$profile = profiles_find($login);
if (!$profile) {
    // Якщо профілю нема — беремо дані з tutors.json як “профіль”
    $profile = [
        'login' => $login,
        'display_name' => (string)($tutor['name'] ?? $login),
        'bio' => (string)($tutor['desc'] ?? ''),
        'teaches' => (string)($tutor['title'] ?? ''),
        'learns' => '',
        'tags' => is_array($tutor['tags'] ?? null) ? $tutor['tags'] : [],
        'avatar' => null,
        'updated_at' => null,
    ];
}

$imgN = (int)($tutor['img'] ?? 0);
?>

<section class="page-section">
    <div class="tutor-hero card">
        <div class="tutor-cover" style="background-image:url('/img/tutors/tutor<?= $imgN ?>.jpg');"></div>

        <div>
            <h1 style="margin:0;"><?= htmlspecialchars($profile['display_name']) ?></h1>
            <div class="muted">@<?= htmlspecialchars($login) ?></div>

            <div class="tutor-badges" style="margin-top:10px;">
                <span class="badge">★ 1 жовта / заняття</span>
                <?php if (!empty($tutor['rating'])): ?>
                    <span class="badge">Рейтинг: <?= htmlspecialchars((string)$tutor['rating']) ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($profile['bio'])): ?>
                <p class="muted" style="margin-top:10px;"><?= htmlspecialchars($profile['bio']) ?></p>
            <?php endif; ?>

            <div class="tutor-tags" style="margin-top:10px;">
                <?php foreach (($profile['tags'] ?? []) as $tag): ?>
                    <span class="tag">#<?= htmlspecialchars(mb_strtolower((string)$tag)) ?></span>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn-primary" href="/chat.php?with=<?= urlencode($login) ?>">Призначити зустріч</a>
                <a class="btn-secondary" href="/profile.php?u=<?= urlencode($login) ?>">Відкрити як профіль</a>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2>Календар (демо)</h2>
        <p class="muted">Учні бачать зайнятість репетитора (поки статично).</p>

        <div class="calendar-wrap">
            <table class="calendar-table">
                <tr>
                    <th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Нд</th>
                </tr>
                <tr>
                    <td class="slot">—</td>
                    <td class="slot busy">18:00</td>
                    <td class="slot">—</td>
                    <td class="slot busy">19:30</td>
                    <td class="slot">—</td>
                    <td class="slot busy">11:00</td>
                    <td class="slot">—</td>
                </tr>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
