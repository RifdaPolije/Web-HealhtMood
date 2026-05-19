<?php
require_once 'config.php';
require_login();

if (!$pdo) {
    $title = 'Tidur';
    $active = 'tidur';
    require 'includes/header.php';
    echo '<section class="section"><p class="message error">Database belum tersambung. Import database.sql dulu di phpMyAdmin.</p></section>';
    require 'includes/footer.php';
    exit;
}

$userId = $_SESSION['user_id'];
$record = null;
$isView = false;

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM sleeps WHERE id = ? AND user_id = ?');
    $stmt->execute([(int) $_GET['delete'], $userId]);
    redirect_to('laporan_tidur.php?success=sleep_deleted');
}

$modalId = (int) ($_GET['edit'] ?? $_GET['view'] ?? 0);
if ($modalId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM sleeps WHERE id = ? AND user_id = ?');
    $stmt->execute([$modalId, $userId]);
    $record = $stmt->fetch();
    $isView = isset($_GET['view']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $date = $_POST['sleep_date'] ?? date('Y-m-d');
    $start = $_POST['sleep_start'] ?? '21:25';
    $end = $_POST['sleep_end'] ?? '06:30';
    $quality = max(1, min(5, (int) ($_POST['quality'] ?? 3)));
    $dream = trim($_POST['dream'] ?? '');
    $condition = trim($_POST['condition'] ?? '');
    $hours = 8;
    $note = trim("Mimpi: {$dream}\nKondisi: {$condition}");

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE sleeps SET sleep_date = ?, sleep_start = ?, sleep_end = ?, hours = ?, quality = ?, note = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$date, $start, $end, $hours, $quality, $note, $id, $userId]);
        redirect_to('laporan_tidur.php?success=sleep_updated');
    }

    $stmt = $pdo->prepare('INSERT INTO sleeps (user_id, sleep_date, sleep_start, sleep_end, hours, quality, note) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$userId, $date, $start, $end, $hours, $quality, $note]);
    redirect_to('laporan_tidur.php?success=sleep_created');
}

$showModal = $record || isset($_GET['add']);
$title = 'Tidur';
$active = 'tidur';
require 'includes/header.php';
?>
<section class="feature-page">
    <div class="feature-copy">
        <h1 class="section-title">Lelah Menjalani Hari?<br>Mari Mulai dari Tidurmu.</h1>
        <p>Jangan biarkan hari-harimu berjalan tanpa arah. Dengan memantau kualitas istirahat dan fluktuasi suasana hati secara konsisten, kamu bisa menemukan cara terbaik yang dipersonalisasi khusus untuk menjaga kesehatan mentalmu.</p>
        <div class="actions">
            <button class="btn" type="button" data-open-modal="#sleepModal">Tambah Data Tidur</button>
            <a class="btn" href="laporan_tidur.php">Laporan Data Tidur</a>
        </div>
    </div>
    <div class="feature-art">
        <div class="fake-bars">
            <p>Pola Tidur Minggu Ini</p>
            <small>Durasi tidur harian (jam)</small>
            <?php foreach ([65, 80, 26, 40, 50, 0, 0] as $i => $w): ?>
                <div class="bar-line"><span><?= ['Sen','Sel','Rab','Kam','Jum','Sab','Min'][$i] ?></span><span style="--w:<?= $w ?>%"></span><span><?= $w ? round($w / 10, 1) : '' ?></span></div>
            <?php endforeach; ?>
        </div>
        <img src="public/Rectangle-39@2x.png" alt="Ilustrasi tidur">
    </div>
</section>

<div class="modal <?= $showModal ? 'show' : '' ?>" id="sleepModal">
    <form class="modal-card" method="post">
        <button class="modal-close" type="button" data-close-modal>&times;</button>
        <h2><?= $isView ? 'Detail Data Tidur' : ($record ? 'Edit Data Tidur' : 'Isi data tidur malammu') ?></h2>
        <p>Data tidur membantumu memahami hubungan antara istirahat dan suasana hati</p>
        <input type="hidden" name="id" value="<?= e($isView ? '' : ($record['id'] ?? '')) ?>">
        <div class="form-grid modal-fields">
            <label>Tanggal tidur
                <input type="date" name="sleep_date" value="<?= e($record['sleep_date'] ?? date('Y-m-d')) ?>" required <?= $isView ? 'disabled' : '' ?>>
            </label>
            <label>Jam mulai tidur
                <input type="time" name="sleep_start" value="<?= e(substr($record['sleep_start'] ?? '21:25', 0, 5)) ?>" required <?= $isView ? 'disabled' : '' ?>>
            </label>
            <label>Jam mulai bangun
                <input type="time" name="sleep_end" value="<?= e(substr($record['sleep_end'] ?? '06:30', 0, 5)) ?>" required <?= $isView ? 'disabled' : '' ?>>
            </label>
            <label>Kualitas tidur
                <select name="quality" <?= $isView ? 'disabled' : '' ?>>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= (int)($record['quality'] ?? 3) === $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label>
            <label>Mimpi?
                <input type="text" name="dream" placeholder="Tidak ingat" <?= $isView ? 'disabled' : '' ?>>
            </label>
            <label>Kondisi sebelum tidur
                <input type="text" name="condition" placeholder="Normal" <?= $isView ? 'disabled' : '' ?>>
            </label>
        </div>
        <?php if (!$isView): ?>
            <button class="btn modal-submit" type="submit"><?= $record ? 'Edit Data Tidur' : 'Simpan Data Tidur' ?></button>
        <?php endif; ?>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
