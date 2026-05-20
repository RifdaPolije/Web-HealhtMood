<?php
require_once 'config.php';

$title = 'Home';
$active = 'home';

require 'includes/header.php';
?>

<section class="home-hero">

    <div>
        <h1>
            Hei, Bagaimana
            <em>Perasaanmu?</em>
        </h1>

        <p>
            Catat mood dan pola tidurmu setiap hari.
            Karena memahami dirimu sendiri adalah
            langkah pertama menuju hidup yang
            lebih tenang.
        </p>

        <a
            class="btn"
            href="<?= is_logged_in() ? '#checkinCard' : 'login.php' ?>"
            <?= is_logged_in() ? 'data-scroll-checkin' : '' ?>
        >
            Check In
        </a>
    </div>

    <div class="hero-collage" aria-hidden="true">
        <img src="public/Rectangle-38@2x.png" alt="">
        <img src="public/Rectangle-40@2x.png" alt="">
    </div>

</section>

<!-- =========================================
     DASHBOARD
========================================= -->

<section class="section center">

    <h2 class="dashboard-title">
        Pantau Perkembanganmu
    </h2>

    <p class="dashboard-subtitle">
        Temukan pola tersembunyi di balik
        tidur dan perasaanmu
    </p>

    <div class="dashboard-grid">

        <!-- SLEEP -->
        <div class="mini-chart">

            <h3>
                Pola Tidur Minggu Ini
            </h3>

            <small>
                Durasi tidur harian (jam)
            </small>

            <canvas
                id="sleepChart"
                width="520"
                height="220"
            ></canvas>

        </div>

        <!-- MOOD -->
        <div class="mini-chart">

            <h3>
                Grafik Mood Minggu Ini
            </h3>

            <small>
                Skor harian (1-5)
            </small>

            <canvas
                id="moodChart"
                width="520"
                height="220"
            ></canvas>

        </div>

    </div>

    <p id="chartUpdated" class="chart-updated"></p>

</section>

<!-- =========================================
     CHECK IN
========================================= -->

<section class="section center">

    <h2>
        Check in Harian
    </h2>

    <div class="checkin-card" id="checkinCard">

        <div class="toolbar">

            <div>
                <strong>
                    Streak Kamu
                </strong>

                <br>

                <small>
                    7 hari berturut-turut
                    check-in!
                </small>
            </div>

            <?php if (is_logged_in()): ?>

                <button
                    class="btn light"
                    type="button"
                    data-checkin-action
                >
                    Check In
                </button>

            <?php else: ?>

                <a
                    class="btn light"
                    href="login.php"
                >
                    Check In
                </a>

            <?php endif; ?>

        </div>

        <div class="streak-row">

            <?php foreach (['S','S','R','K','J','S','M'] as $day): ?>

                <div class="day-box">
                    <?= e($day) ?>
                </div>

            <?php endforeach; ?>

        </div>

        <div class="toolbar">
            <span data-level-text>
                Level 8
            </span>

            <span data-xp-text>
                680 / 1000 XP
            </span>
        </div>

        <div class="progress">
            <span data-xp-bar></span>
        </div>

    </div>

</section>

<!-- =========================================
     TIPS
========================================= -->

<section class="section">

    <div class="tips-card">

        <h2>
            Tips Kesehatan Mental
        </h2>

        <ul>

            <li>
                <strong>
                    Tidur di jam yang sama
                </strong>

                <br>

                Konsistensi waktu tidur membantu
                ritme sirkadian tubuhmu.
            </li>

            <li>
                <strong>
                    Hindari layar 1 jam sebelum tidur
                </strong>

                <br>

                Cahaya biru dari ponsel membuat
                otak tetap aktif saat seharusnya
                rileks.
            </li>

            <li>
                <strong>
                    Luangkan 5 menit untuk bernapas
                </strong>

                <br>

                Tarik napas 4 detik,
                tahan 7 detik,
                lalu buang 8 detik.
            </li>

            <li>
                <strong>
                    Tulis 3 hal yang kamu syukuri
                </strong>

                <br>

                Praktik rasa syukur membantu
                menggeser fokus dari negatif
                ke positif.
            </li>

        </ul>

    </div>

</section>

<script src="assets/js/charts.js"></script>

<?php require 'includes/footer.php'; ?>