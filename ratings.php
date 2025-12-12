<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/php/ratings_data.php';

$rows = ratings_load_all();

// Сортуємо за годинами (спадання)
usort($rows, fn($a, $b) => (int)($b['hours'] ?? 0) <=> (int)($a['hours'] ?? 0));

function rank_title(int $pos): array {
    // pos = 1..N
    if ($pos === 1) return ['Легенда платформи', 'rank-1'];
    if ($pos === 2) return ['Топ-учень', 'rank-2'];
    if ($pos === 3) return ['Суперактивний', 'rank-3'];
    if ($pos <= 10) return ['Активний учень', 'rank-top'];
    return ['Новачок', 'rank-new'];
}
?>

<section class="page-section">
    <div class="ratings-head">
        <h1>Рейтинг учнів</h1>
        <div class="muted">Топ формується лише за загальною кількістю годин занять на платформі</div>
    </div>

    <div class="card">
        <table class="rating-table">
            <tr>
                <th>#</th>
                <th>Учень</th>
                <th>Години</th>
                <th>Звання</th>
            </tr>

            <?php foreach ($rows as $i => $r): ?>
                <?php
                    $pos = $i + 1;
                    [$title, $cls] = rank_title($pos);
                ?>
                <tr>
                    <td><?= $pos ?></td>
                    <td><b><?= htmlspecialchars((string)($r['name'] ?? '—')) ?></b></td>
                    <td><?= (int)($r['hours'] ?? 0) ?></td>
                    <td>
                        <span class="pill <?= htmlspecialchars($cls) ?>">
                            <?= htmlspecialchars($title) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p class="muted" style="margin-top:12px;">
            Підказка: чим більше годин, тим вище місце.
        </p>
    </div>
</section>

<?php
require_once __DIR__ . '/footer.php';
?>
