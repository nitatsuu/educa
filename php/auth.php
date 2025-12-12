<?php
require_once __DIR__ . '/storage_users.php';

function auth_current_user(): ?array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $login = $_SESSION['login'] ?? null;
    if (!$login) return null;

    $u = users_find($login);
    return $u ?: null;
}

function auth_require_login(): void
{
    $u = auth_current_user();
    if (!$u) {
        header('Location: /auth.php');
        exit;
    }
}

function auth_login(string $login): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['login'] = $login;
}

function auth_logout(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
