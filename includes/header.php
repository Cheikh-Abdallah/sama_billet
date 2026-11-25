<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#6c5ce7">
  <meta name="description" content="Découvrez les événements et réservez vos billets avec Sama Billet.">
  <title><?= esc($pageTitle ?? APP_NAME) ?> — Sama Billet</title>
  <link rel="manifest" href="/manifest.webmanifest">
  <link rel="icon" href="/assets/icons/favicon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="/assets/icons/icon-192.svg">
  <link rel="stylesheet" href="/assets/style.css">
  <link rel="stylesheet" href="/assets/pwa.css">
</head>
<body>
<nav class="navbar">
  <a class="brand" href="/index.php"><span class="brand-mark">SB</span> Sama Billet</a>
  <button class="nav-toggle" aria-label="Menu">☰</button>
  <div class="nav-links">
    <a href="/index.php">Accueil</a>
    <a href="/events.php">Événements</a>
    <?php if (isLoggedIn()): ?>
      <a href="/dashboard.php">Mes billets</a>
      <?php if (isAdmin()): ?><a href="/admin.php">Administration</a><?php endif; ?>
      <a class="nav-user" href="/logout.php">Déconnexion · <?= esc($_SESSION['user']['name']) ?></a>
    <?php else: ?>
      <a href="/login.php">Connexion</a>
      <a class="btn btn-small" href="/register.php">Créer un compte</a>
    <?php endif; ?>
  </div>
</nav>
<main>
<?php if ($message = flash('success')): ?><div class="alert success"><?= esc($message) ?></div><?php endif; ?>
<?php if ($message = flash('error')): ?><div class="alert error"><?= esc($message) ?></div><?php endif; ?>
