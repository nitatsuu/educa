<?php
require_once __DIR__ . '/header.php';

require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/storage_profiles.php';

$user = auth_current_user();
if (!$user) {
    header('Location: /auth.php');
    exit;
}

$login = $user['login'];
$profile = profiles_find($login);

if (!$profile) {
    // Профіль ще не заповнений — створюємо дефолтне відображення
    $profile = [
        'login' => $login,
        'display_name' => $login,
        'bio' => '',
        'teaches' => '',
        'learns' => '',
        'tags' => [],
        'updated_at' => null
    ];
}

$saved = ($_GET['saved'] ?? '') === '1';
$tags_str = '';
if (is_array($profile['tags'] ?? null)) {
    $tags_str = implode(' ', array_map(fn($t) => '#' . $t, $profile['tags']));
}
?>

<section class="page-section">
    <div class="profile-head">
        <div class="profile-avatar">👤</div>
        <div class="profile-meta">
            <h1 class="profile-title"><?= htmlspecialchars($profile['display_name']) ?></h1>
            <div class="profile-sub">
                <span class="muted">@<?= htmlspecialchars($login) ?></span>
                <?php if (!empty($profile['updated_at'])): ?>
                    <span class="muted">• оновлено: <?= htmlspecialchars(date('d.m.Y H:i', strtotime($profile['updated_at']))) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($saved): ?>
                <div class="notice success">Зміни збережено.</div>
            <?php endif; ?>

            <div class="profile-tags">
                <?php if (!empty($profile['tags'])): ?>
                    <?php foreach ($profile['tags'] as $t): ?>
                        <span class="tag">#<?= htmlspecialchars($t) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="muted">Теги ще не додано.</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="profile-grid">
        <div class="card">
            <h2>Про мене</h2>
            <p class="muted"><?= $profile['bio'] !== '' ? htmlspecialchars($profile['bio']) : 'Короткий опис відсутній.' ?></p>

            <div class="two-cols">
                <div>
                    <h3 class="h3">Навчаю</h3>
                    <p class="muted"><?= $profile['teaches'] !== '' ? htmlspecialchars($profile['teaches']) : '—' ?></p>
                </div>
                <div>
                    <h3 class="h3">Вчуся</h3>
                    <p class="muted"><?= $profile['learns'] !== '' ? htmlspecialchars($profile['learns']) : '—' ?></p>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Редагування профілю</h2>

            <form action="/php/save_profile.php" method="post">
                <label class="field">
                    <span>Імʼя (публічно)</span>
                    <input name="display_name" type="text" maxlength="40"
                           value="<?= htmlspecialchars($profile['display_name']) ?>">
                </label>

                <label class="field">
                    <span>Опис</span>
                    <input name="bio" type="text" maxlength="240"
                           value="<?= htmlspecialchars($profile['bio']) ?>"
                           placeholder="Коротко про себе (до 240 символів)">
                </label>

                <label class="field">
                    <span>Що навчаю</span>
                    <input name="teaches" type="text" maxlength="120"
                           value="<?= htmlspecialchars($profile['teaches']) ?>"
                           placeholder="Напр.: математика, Python, англійська">
                </label>

                <label class="field">
                    <span>Що вчу</span>
                    <input name="learns" type="text" maxlength="120"
                           value="<?= htmlspecialchars($profile['learns']) ?>"
                           placeholder="Напр.: фізика, дизайн, історія">
                </label>

                <label class="field">
                    <span>Теги</span>
                    <input name="tags" type="text"
                           value="<?= htmlspecialchars($tags_str) ?>"
                           placeholder="#математика #python #англійська">
                </label>

                <button class="btn-primary" type="submit">Зберегти</button>
            </form>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/footer.php';
?>
