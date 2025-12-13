<?php
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/header.php';

$user = auth_current_user();
if (!$user) { header('Location: /auth.php'); exit; }
if (empty($user['premium'])) { header('Location: /profile.php'); exit; }
?>

<section class="page-section">
  <h1>Створення курсу (демо)</h1>

  <div class="card">
    <form action="/php/save_course.php" method="post">
      <label class="field">
        <span>Назва</span>
        <input name="title" type="text" required maxlength="80" placeholder="Напр.: Python з нуля">
      </label>

      <label class="field">
        <span>Опис</span>
        <input name="desc" type="text" maxlength="240" placeholder="Короткий опис курсу">
      </label>

      <label class="field">
        <span>Теги</span>
        <input name="tags" type="text" placeholder="#python #програмування">
      </label>

      <label class="field">
        <span>Обкладинка (номер картинки)</span>
        <input name="img" type="number" min="0" max="99" value="0">
      </label>

      <button class="btn-primary" type="submit">Зберегти (демо)</button>
    </form>

  </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
