<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEES5 PICKMOK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <h1>PEES5 PICKMOK</h1>
    <nav>
        <a href="index.php?page=games"  class="<?= $page === 'games'  ? 'active' : '' ?>">Games</a>
        <a href="index.php?page=genres" class="<?= $page === 'genres' ? 'active' : '' ?>">Genres</a>
        <a href="index.php?page=picks"  class="<?= $page === 'picks'  ? 'active' : '' ?>">Pilihanku</a>
    </nav>
</header>
<main class="container">
<?php if ($flash = getFlash()): ?>
    <div class="alert <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
<?php endif; ?>
