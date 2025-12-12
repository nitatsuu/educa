<?php
require_once __DIR__ . '/header.php';
?>

<section class="page-section auth-page">
    <h1>Вхід / Реєстрація</h1>

    <div class="auth-grid">
        <div class="auth-card">
            <h2>Увійти</h2>
            <form action="/php/login.php" method="post">
                <label class="field">
                    <span>Логін</span>
                    <input name="login" type="text" required minlength="3" maxlength="24">
                </label>

                <label class="field">
                    <span>Пароль</span>
                    <input name="password" type="password" required minlength="4" maxlength="64">
                </label>

                <button class="btn-primary" type="submit">Увійти</button>
            </form>
        </div>

        <div class="auth-card">
            <h2>Зареєструватися</h2>
            <form action="/php/register.php" method="post">
                <label class="field">
                    <span>Логін</span>
                    <input name="login" type="text" required minlength="3" maxlength="24">
                </label>

                <label class="field">
                    <span>Пароль</span>
                    <input name="password" type="password" required minlength="4" maxlength="64">
                </label>

                <button class="btn-secondary" type="submit">Створити акаунт</button>
            </form>
        </div>
    </div>

    <p class="hint">
        Після входу баланс зірок у шапці оновиться автоматично.
    </p>
</section>

<?php
require_once __DIR__ . '/footer.php';
?>
