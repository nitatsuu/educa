<?php
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/storage_users.php';
require_once __DIR__ . '/php/storage_profiles.php';

$user = auth_current_user();
if (!$user) { header('Location: /auth.php'); exit; }

$me = $user['login'];
$viewLogin = trim($_GET['u'] ?? '');
if ($viewLogin === '') $viewLogin = $me;

// якщо дивимось чужий профіль — він має існувати
if ($viewLogin !== $me && !users_find($viewLogin)) {
    header('Location: /profile.php');
    exit;
}

$isOwn = ($viewLogin === $me);
$profile = profiles_find($viewLogin);

if (!$profile) {
    $profile = [
        'login' => $viewLogin,
        'display_name' => $viewLogin,
        'bio' => '',
        'teaches' => '',
        'learns' => '',
        'tags' => [],
        'avatar' => null,
        'updated_at' => null
    ];
}

$saved = ($_GET['saved'] ?? '') === '1';
$reqOk = ($_GET['req'] ?? '') === '1';

$tags_str = '';
if (is_array($profile['tags'] ?? null)) {
    $tags_str = implode(' ', array_map(fn($t) => '#' . $t, $profile['tags']));
}

$avatar = !empty($profile['avatar']) ? (string)$profile['avatar'] : null;

// передаємо атрибути для body (якщо ти це вже робив для чату — залиш)
$pageBodyAttrs = '';
require_once __DIR__ . '/header.php';
?>

<section class="page-section">
    <div class="profile-head">
        <div class="profile-avatar">
            <?php if ($avatar): ?>
                <img src="<?= htmlspecialchars($avatar) ?>" alt="avatar" class="avatar-img">
            <?php else: ?>
                👤
            <?php endif; ?>
        </div>

        <div class="profile-meta">
            <h1 class="profile-title"><?= htmlspecialchars($profile['display_name']) ?></h1>
            <div class="profile-sub">
                <span class="muted">@<?= htmlspecialchars($viewLogin) ?></span>
                <?php if (!empty($profile['updated_at'])): ?>
                    <span class="muted">• оновлено: <?= htmlspecialchars(date('d.m.Y H:i', strtotime($profile['updated_at']))) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($saved): ?>
                <div class="notice success">Зміни збережено.</div>
            <?php endif; ?>

            <?php if ($reqOk): ?>
                <div class="notice success">Заявку на менторство надіслано (демо).</div>
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

            <?php if (!$isOwn): ?>
                <hr class="sep">
                <form action="/php/request_mentorship.php" method="post">
                    <input type="hidden" name="mentor" value="<?= htmlspecialchars($viewLogin) ?>">
                    <button class="btn-primary" type="submit">Подати заявку на менторство</button>
                </form>
                <p class="muted" style="margin-top:10px;font-size:13px;">
                    Після заявки ментор зможе звʼязатися з вами в чаті (демо).
                </p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2><?= $isOwn ? 'Редагування профілю' : 'Профіль (перегляд)' ?></h2>

            <?php if ($isOwn): ?>
                <form action="/php/save_profile.php" method="post" enctype="multipart/form-data">
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

                    <label class="field">
                        <span>Фото профілю (jpg/png/webp, до 2MB)</span>
                        <input name="avatar" type="file" accept=".jpg,.jpeg,.png,.webp">
                    </label>

                    <button class="btn-primary" type="submit">Зберегти</button>

                    <hr class="sep">
                    
                    <div class="profile-buttons">
                        <?php if (!empty($user['premium'])): ?>
                            <a class="btn-primary" href="/create_course.php" style="display:inline-block;">
                                Створити курс
                            </a>
                        <?php else: ?>
                            <button class="btn-secondary" type="button" disabled title="Доступно з Premium">
                                Створити курс (Premium)
                            </button>
                        <?php endif; ?>
                        
                        <button class="btn-primary" id="startLesson"> Провести урок </button>
                    </div>
                </form>
            <?php else: ?>
                <p class="muted">Редагування недоступне.</p>
                <a class="btn-secondary" href="/chat.php?with=<?= urlencode($viewLogin) ?>">Відкрити чат</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2>Календар (демо)</h2>
        <p class="muted">Тут учні бачать зайнятість / доступні слоти (поки статична таблиця).</p>

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
                    <td class="slot">—</td>
                    <td class="slot busy">12:00</td>
                </tr>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
