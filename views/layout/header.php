<?php

declare(strict_types=1);

$page_title   = $page_title   ?? 'Club des Felouques de Kerkennah';
$current_page = $current_page ?? '';

//send_security_headers();
?>
<!DOCTYPE html>
<html lang="fr" data-page="<?= h($current_page) ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Club des Felouques de Kerkennah — La voile traditionnelle." />
  <title><?= h($page_title) ?> — Club des Felouques de Kerkennah</title>
  <link rel="stylesheet" href="public/style.css" />
  <link rel="icon" href="media/icon.png" type="image/png" />
</head>
<body>

  <header>
    <div class="header-inner">
      <div class="logo-area">
        <span class="logo-icon animate-wave">⛵</span>
        <div class="logo-text">
          <span class="logo-title">Club des Felouques</span>
          <span class="logo-subtitle">Kerkennah • Tunisie</span>
        </div>
      </div>
      <nav aria-label="Navigation principale">
        <ul>
          <li><a href="index.php" <?= $current_page === 'index' ? 'class="active"' : '' ?>>Accueil</a></li>
          <li><a href="histoire.php" <?= $current_page === 'histoire' ? 'class="active"' : '' ?>>Histoire</a></li>
          <li><a href="galerie.php" <?= $current_page === 'galerie' ? 'class="active"' : '' ?>>Galerie</a></li>
          <li><a href="calendrier.php" <?= $current_page === 'calendrier' ? 'class="active"' : '' ?>>Calendrier</a></li>
          <li><a href="contact.php" <?= $current_page === 'contact' ? 'class="active"' : '' ?>>Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <?php
  $flashes = $flashes ?? get_flash();
  foreach ($flashes as $flash_item):
  ?>
    <div class="flash-msg flash-<?= h($flash_item['type']) ?>" role="alert" aria-live="polite">
      <?= h($flash_item['msg']) ?>
    </div>
  <?php endforeach; ?>