<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/auth.php';

$user = auth_current_user();
if (!$user) {
    header('Location: /auth.php');
    exit;
}

$ok  = ($_GET['ok'] ?? '') === '1';
$err = ($_GET['err'] ?? '');
?>

<section class="page-section">
    <h1>Зірки</h1>

    <?php if ($ok): ?>
        <div class="notice success">Обмін успішний. Баланс оновлено.</div>
    <?php endif; ?>

    <?php if ($err === 'not_enough'): ?>
        <div class="notice">Недостатньо синіх зірок для обміну.</div>
    <?php endif; ?>

    <div class="stars-grid">
        <!-- Купівля жовтих -->
        <div class="card">
            <h2>Купити жовті зірки</h2>
            <p class="muted">
                Жовті зірки використовуються для запису на заняття.
                (Демо — реальні платежі не підключені)
            </p>

            <table class="tariff-table">
                <tr>
                    <th>Пакет</th>
                    <th>Зірки</th>
                    <th>Ціна</th>
                </tr>
                <tr>
                    <td>Starter</td>
                    <td>1 ★</td>
                    <td>80 грн</td>
                </tr>
                <tr>
                    <td>Pro</td>
                    <td>5 ★</td>
                    <td>360 грн</td>
                </tr>
                <tr>
                    <td>Max</td>
                    <td>10 ★</td>
                    <td>700 грн</td>
                </tr>
            </table>

            <button class="btn-secondary" type="button" disabled>
                Оплата (демо)
            </button>
        </div>

        <!-- Обмін синіх -->
        <div class="card">
            <h2>Обміняти сині зірки</h2>

            <p class="muted">
                Сині зірки нараховуються за навчання інших користувачів.
            </p>

            <ul class="exchange-info">
                <li>1 синя ★ → 1 жовта ★</li>
                <li>Ваш баланс: <?= (int)$user['blue'] ?> синіх ★</li>
            </ul>

            <form action="/php/exchange_blue.php" method="post">
                <button class="btn-primary" type="submit"
                    <?= ($user['blue'] ?? 0) < 10 ? 'disabled' : '' ?>>
                    Обміняти 1 → 1
                </button>
            </form>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/footer.php';
?>
