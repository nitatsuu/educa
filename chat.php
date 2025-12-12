<?php
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/storage_users.php';

$user = auth_current_user();
if (!$user) {
    header('Location: /auth.php');
    exit;
}

$me = $user['login'];
$with = trim($_GET['with'] ?? '');

$users = users_read_all();
$contacts = array_values(array_filter(array_keys($users), fn($u) => $u !== $me));

if ($with === '' && count($contacts) > 0) {
    $with = $contacts[0];
}

$pageBodyAttrs = 'data-chat-with="' . htmlspecialchars($with) . '"';
require_once __DIR__ . '/header.php';
?>
<script src="/js/chat.js" defer></script>

<section class="page-section">
    <h1>Чат</h1>

    <div class="chat-layout">
        <aside class="chat-list card">
            <h2>Користувачі</h2>
            <?php if (empty($contacts)): ?>
                <p class="muted">Немає інших користувачів для чату.</p>
            <?php else: ?>
                <div class="chat-users">
                    <?php foreach ($contacts as $u): ?>
                        <a class="chat-user <?= $u === $with ? 'chat-user--active' : '' ?>"
                           href="/chat.php?with=<?= urlencode($u) ?>">
                            @<?= htmlspecialchars($u) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </aside>

        <div class="chat-main card">
            <div class="chat-head">
                <div>
                    <div class="muted">Ви: @<?= htmlspecialchars($me) ?></div>
                    <div class="chat-with">Співрозмовник: <b>@<?= htmlspecialchars($with) ?></b></div>
                </div>
            </div>

            <div id="chatBox" class="chat-box"></div>

            <form id="chatForm" class="chat-form">
                <input id="chatInput" type="text" maxlength="500" placeholder="Напишіть повідомлення…" autocomplete="off">
                <button class="btn-primary" type="submit">Надіслати</button>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  if (window.initChat) window.initChat();
});
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
