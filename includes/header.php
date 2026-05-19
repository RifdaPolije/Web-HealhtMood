<?php
$active = $active ?? '';
$title = $title ?? 'HealthMood';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> - HealthMood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php" aria-label="HealthMood Home">
        <img src="public/WhatsApp-Image-2026-04-29-at-21-03-52-removebg-preview-1@2x.png" alt="Logo HealthMood">
        <span>HEALTHMOOD</span>
    </a>
    <nav class="main-nav" aria-label="Menu utama">
        <a class="<?= $active === 'mood' ? 'active' : '' ?>" href="<?= is_logged_in() ? 'mood.php' : 'login.php' ?>">MOOD</a>
        <a class="<?= $active === 'tidur' ? 'active' : '' ?>" href="<?= is_logged_in() ? 'tidur.php' : 'login.php' ?>">TIDUR</a>
        <a class="<?= $active === 'game' ? 'active' : '' ?>" href="<?= is_logged_in() ? 'game.php' : 'login.php' ?>">GAME</a>
    </nav>
    <div class="header-actions">
        <form class="search-box" role="search">
            <input type="search" placeholder="SEARCH" aria-label="Search">
            <span class="search-icon" aria-hidden="true"></span>
        </form>
        <button class="bell" type="button" title="Notifikasi" aria-label="Notifikasi"><span></span></button>
        <?php if (is_logged_in()): ?>
            <button class="logout-trigger" type="button" title="Logout" aria-label="Logout"><span></span></button>
        <?php else: ?>
            <a class="login-link" href="login.php">LOGIN</a>
        <?php endif; ?>
    </div>
</header>
<main class="page">

<div class="confirm-modal" id="logoutModal" aria-hidden="true">
    <div class="confirm-card">
        <h2>Yakin mau logout?</h2>
        <p>Data yang sudah disimpan tetap aman di akunmu.</p>
        <div class="actions">
            <button class="btn light" type="button" data-close-logout>Batal</button>
            <a class="btn danger" href="logout.php">Logout</a>
        </div>
    </div>
</div>

<div class="toast-modal" id="toastModal" aria-hidden="true">
    <div class="toast-card">
        <h2 id="toastTitle">Berhasil</h2>
        <p id="toastMessage">Data berhasil disimpan.</p>
        <button class="btn" type="button" data-close-toast>OK</button>
    </div>
</div>
