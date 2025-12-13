<?php
require_once __DIR__ . '/php/init.php';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Educa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/pages/auth.css">
    <link rel="stylesheet" href="/css/pages/profile.css">
    <link rel="stylesheet" href="/css/pages/tutors.css">
    <link rel="stylesheet" href="/css/pages/exchange.css">
    <link rel="stylesheet" href="/css/pages/chat.css">
    <link rel="stylesheet" href="/css/pages/ratings.css">
    <link rel="stylesheet" href="/css/pages/course.css">
    <link rel="stylesheet" href="/css/pages/tutor.css">
    <link rel="stylesheet" href="/css/pages/merch.css">


    <script src="/js/main.js" defer></script>
</head>
<body <?= isset($pageBodyAttrs) ? $pageBodyAttrs : '' ?>>
<header class="site-header">
    <div class="container header-inner">
        <div class="logo">
            <a href="/index.php">Educa</a>
        </div>

        <nav class="main-nav">
            <a href="/tutors.php">Репетитори</a>
            <a href="/ratings.php">Рейтинги</a>
            <a href="/exchange.php">Зірки</a>
            <a href="/chat.php">Чат</a>
            <a href="/profile.php">Профіль</a>
        </nav>

        <div class="user-panel">
            <div class="stars" title="Баланс зірок">
                <span class="stars-yellow" title="Жовті зірки">★ <?= htmlspecialchars($currentUser['yellow_stars']) ?></span>
                <span class="stars-blue" title="Сині зірки">★ <?= htmlspecialchars($currentUser['blue_stars']) ?></span>
            </div>

            <div class="user-auth">
                <span class="user-name <?= !empty($currentUser['premium']) ? 'user-name--premium' : '' ?>" title="Користувач">
                    <?= htmlspecialchars($currentUser['login']) ?>
                </span>


                <!-- Пізніше підв’яжемо до реальної авторизації -->
                <a href="/auth.php" class="btn-link">Увійти</a>
                <a href="/php/logout.php" class="btn-link btn-link--secondary">Вийти</a>
            </div>
        </div>
    </div>
</header>

<main class="site-main container">
