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

    <!-- FONT LOGO -->
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
</head>

<body class="<?= $active ? 'page-' . e($active) : 'page-default' ?>">

<header class="site-header">

    <!-- LOGO -->
    <a class="brand" href="index.php" aria-label="HealthMood Home">

        <img src="assets/images/game/WhatsApp-Image-2026-04-29-at-21-03-52-removebg-preview-1@2x.png"
             alt="Logo HealthMood">

        <span>HealthMood</span>

    </a>

    <!-- MENU -->
    <nav class="main-nav" aria-label="Menu utama">

        <a class="<?= $active === 'mood' ? 'active' : '' ?>"
           href="<?= is_logged_in() ? 'mood.php' : 'login.php' ?>">
            MOOD
        </a>

        <a class="<?= $active === 'tidur' ? 'active' : '' ?>"
           href="<?= is_logged_in() ? 'tidur.php' : 'login.php' ?>">
            TIDUR
        </a>

        <a class="<?= $active === 'game' ? 'active' : '' ?>"
           href="<?= is_logged_in() ? 'game.php' : 'login.php' ?>">
            GAME
        </a>

    </nav>

    <!-- ACTION -->
    <div class="header-actions">

        <!-- SEARCH -->
        <form class="search-box" role="search">

            <input type="search"
                   placeholder="SEARCH"
                   aria-label="Search">

            <img class="search-icon-img"
                 src="assets/images/game/ic-baseline-search.svg"
                 alt=""
                 aria-hidden="true">

        </form>

        <!-- NOTIFIKASI -->
        <button class="bell"
                type="button"
                title="Notifikasi"
                aria-label="Notifikasi">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="26"
                 height="26"
                 fill="white"
                 viewBox="0 0 16 16">

                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>

            </svg>

        </button>

        <?php if (is_logged_in()): ?>

            <!-- LOGOUT -->
            <button class="logout-trigger"
                    type="button"
                    title="Logout"
                    aria-label="Logout">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="26"
                     height="26"
                     fill="white"
                     viewBox="0 0 16 16">

                    <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>

                </svg>

            </button>

        <?php else: ?>

            <a class="login-link" href="login.php">
                LOGIN
            </a>

        <?php endif; ?>

    </div>

</header>

<main class="page">

<div class="confirm-modal" id="logoutModal" aria-hidden="true">

    <div class="confirm-card">

        <h2>Yakin mau logout?</h2>

        <p>
            Data yang sudah disimpan tetap aman di akunmu.
        </p>

        <div class="actions">

            <button class="btn light"
                    type="button"
                    data-close-logout>
                Batal
            </button>

            <a class="btn danger"
               href="logout.php">
                Logout
            </a>

        </div>

    </div>

</div>

<div class="toast-modal"
     id="toastModal"
     aria-hidden="true">

    <div class="toast-card">

        <h2 id="toastTitle">Berhasil</h2>

        <p id="toastMessage">
            Data berhasil disimpan.
        </p>

        <button class="btn"
                type="button"
                data-close-toast>
            OK
        </button>

    </div>

</div>