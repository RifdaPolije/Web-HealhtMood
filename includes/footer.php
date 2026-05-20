</main>

<link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">

<footer class="site-footer">

    <div class="footer-brand">

        <div class="footer-logo-line">
            <img src="assets/images/game/WhatsApp-Image-2026-04-29-at-21-03-52-removebg-preview-1@2x.png" alt="Logo HealthMood">
            <h2>HealthMood</h2>
        </div>

        <div class="partner-logos">
            <div class="partner-row partner-row-top">
                <img src="assets/images/game/HEADER-NEW-1@2x.png" alt="Politeknik Negeri Jember">
                <img class="trk-logo" src="assets/images/game/logo-trk-1@2x.png" alt="Teknologi Rekayasa Komputer">
            </div>
            <img src="assets/images/game/HEADER-NEW-2@2x.png" alt="Partner logo">
        </div>

    </div>

    <div>
        <h3>About us</h3>

        <p>
            Pahami dirimu, perbaiki tidurmu,
            dan temukan ketenangan setiap hari.
        </p>
    </div>

    <div>
        <h3>Layanan</h3>

        <a href="<?= is_logged_in() ? 'mood.php' : 'login.php' ?>">
            Cek Mood
        </a>

        <a href="<?= is_logged_in() ? 'tidur.php' : 'login.php' ?>">
            Catatan Tidur
        </a>

        <a href="<?= is_logged_in() ? 'game.php' : 'login.php' ?>">
            Refreshing Game
        </a>

        <a href="<?= is_logged_in() ? 'laporan_mood.php' : 'login.php' ?>">
            Laporan Mingguan
        </a>
    </div>

    <div>
        <h3>Bantuan</h3>

        <a href="#">Pusat Edukasi</a>
        <a href="#">Kebijakan Privasi</a>
        <a href="#">Hubungi Kami</a>
    </div>

    <div>
        <h3>Contact us</h3>

        <a href="tel:+123456789">
            Call:<br>
            +12 3456789
        </a>

        <a href="mailto:email@gmail.com">
            Email:<br>
            email@gmail.com
        </a>
    </div>

    <div class="footer-bottom">
        <a href="#">Privacy Policy</a>
        <a href="#">Our History</a>
        <a href="#">What We Do</a>

        <span>
            &copy; 2026 HEALTHMOOD. All rights reserved.
        </span>
    </div>

</footer>

<script src="assets/js/app.js"></script>
</body>
</html>
