<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/merch_data.php';

$user = auth_current_user();
if (!$user) { header('Location: /auth.php'); exit; }

$items = merch_load_all();

$ok = ($_GET['ok'] ?? '') === '1';
$err = ($_GET['err'] ?? '');

function group_by_type(array $items): array {
    $g = ['merch'=>[], 'gift'=>[], 'discount'=>[]];
    foreach ($items as $it) {
        $type = (string)($it['type'] ?? 'merch');
        if (!isset($g[$type])) $g[$type] = [];
        $g[$type][] = $it;
    }
    return $g;
}

$groups = group_by_type($items);

function render_grid(array $list, array $user) {
    foreach ($list as $m) {
        $img = trim((string)($m['img'] ?? ''));
        $title = (string)($m['title'] ?? '—');
        $price = (int)($m['price_blue'] ?? 0);
        $id = (string)($m['id'] ?? '');

        echo '<div class="merch-card card">';

        if ($img !== '') {
            echo '<div class="merch-img" style="background-image:url(\'/img/merch/' . htmlspecialchars($img) . '\');"></div>';
        } else {
            echo '<div class="merch-img merch-img--empty">Без фото</div>';
        }

        echo '<div class="merch-title">' . htmlspecialchars($title) . '</div>';
        echo '<div class="merch-price pill">Ціна: ★ ' . $price . ' (сині)</div>';

        echo '<form action="/php/buy_merch.php" method="post" class="merch-form">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($id) . '">';
        $disabled = ((int)($user['blue'] ?? 0) < $price) ? 'disabled' : '';
        echo '<button class="btn-primary" type="submit" ' . $disabled . '>Отримати (демо)</button>';
        echo '</form>';

        echo '</div>';
    }
}
?>

<section class="page-section">
  <div class="home-head">
    <h1>Мерч та знижки</h1>
    <a class="btn-link" href="/exchange.php">← Назад</a>
  </div>

  <?php if ($ok): ?>
    <div class="notice success">Готово (демо). Сині зірки списано.</div>
  <?php endif; ?>

  <?php if ($err === 'not_enough'): ?>
    <div class="notice">Недостатньо синіх зірок.</div>
  <?php elseif ($err === 'bad'): ?>
    <div class="notice">Помилка.</div>
  <?php endif; ?>

  <h2 style="margin-top:14px;">Мерч</h2>
  <div class="merch-grid">
    <?php render_grid($groups['merch'], $user); ?>
  </div>

  <h2 style="margin-top:18px;">Таємний подарунок</h2>
  <div class="merch-grid">
    <?php render_grid($groups['gift'], $user); ?>
  </div>

  <h2 style="margin-top:18px;">Знижки</h2>
  <div class="merch-grid">
    <?php render_grid($groups['discount'], $user); ?>
  </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
