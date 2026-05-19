<?php
require_once 'config.php';
require_login();

if (!$pdo) {
    $title = 'Mood';
    $active = 'mood';
    require 'includes/header.php';
    echo '<section class="section"><p class="message error">Database belum tersambung. Import database.sql dulu di phpMyAdmin.</p></section>';
    require 'includes/footer.php';
    exit;
}

$userId = $_SESSION['user_id'];
$record = null;
$isView = false;

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM moods WHERE id = ? AND user_id = ?');
    $stmt->execute([(int) $_GET['delete'], $userId]);
    redirect_to('laporan_mood.php?success=mood_deleted');
}

$modalId = (int) ($_GET['edit'] ?? $_GET['view'] ?? 0);
if ($modalId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM moods WHERE id = ? AND user_id = ?');
    $stmt->execute([$modalId, $userId]);
    $record = $stmt->fetch();
    $isView = isset($_GET['view']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $date = $_POST['mood_date'] ?? date('Y-m-d');
    $label = $_POST['mood_label'] ?? 'Biasa aja';
    $activity = trim($_POST['activity'] ?? '');
    $scoreMap = ['Senang' => 5, 'Cemas' => 2, 'Biasa aja' => 3, 'Sedih' => 1, 'Marah' => 1];
    $score = $scoreMap[$label] ?? 3;
    $note = trim($_POST['note'] ?? '');

    if ($activity !== '') {
        $note = trim("Aktivitas: {$activity}\n{$note}");
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE moods SET mood_date = ?, mood_label = ?, mood_score = ?, note = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$date, $label, $score, $note, $id, $userId]);
        redirect_to('laporan_mood.php?success=mood_updated');
    }

    $stmt = $pdo->prepare('INSERT INTO moods (user_id, mood_date, mood_label, mood_score, note) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$userId, $date, $label, $score, $note]);
    redirect_to('laporan_mood.php?success=mood_created');
}

$showModal = $record || isset($_GET['add']);
$activityValue = '';
if ($record && !empty($record['note'])) {
    $firstLine = strtok($record['note'], "\n");
    $activityValue = trim(str_replace('Aktivitas:', '', $firstLine));
}

$title = 'Mood';
$active = 'mood';
require 'includes/header.php';
?>
<section class="feature-page reverse">
    <div class="feature-art">
        <div class="fake-bars">
            <p>Grafik Mood Minggu Ini</p>
            <small>Skor harian (1-5)</small>
            <?php foreach ([70, 42, 56, 38, 64, 52, 86] as $i => $w): ?>
                <div class="bar-line"><span><?= ['Sen','Sel','Rab','Kam','Jum','Sab','Min'][$i] ?></span><span style="--w:<?= $w ?>%"></span><span><?= round($w / 20, 1) ?></span></div>
            <?php endforeach; ?>
        </div>
        <img src="public/Rectangle-38@2x.png" alt="Ilustrasi mood">
    </div>
    <div class="feature-copy">
        <h1 class="section-title">Kenali Dirimu Lebih Dalam<br>Satu Hari di Satu Waktu</h1>
        <p>Tidak apa-apa jika merasa tidak baik-baik saja hari ini. Langkah pertama untuk merasa lebih baik adalah dengan mengakui perasaanmu dan memahami penyebabnya.</p>
        <div class="actions" style="justify-content:flex-end">
            <button class="btn" type="button" data-open-modal="#moodModal">Tambah Data Mood</button>
            <a class="btn" href="laporan_mood.php">Laporan Data Mood</a>
        </div>
    </div>
</section>

<div class="modal <?= $showModal ? 'show' : '' ?>" id="moodModal">
    <form class="modal-card" method="post">
        <button class="modal-close" type="button" data-close-modal>&times;</button>
        <h2><?= $isView ? 'Detail Data Mood' : ($record ? 'Edit Data Mood' : 'Bagaimana perasaanmu sekarang?') ?></h2>
        <p>Pilih perasaan yang paling menggambarkan kondisimu saat ini</p>
        <input type="hidden" name="id" value="<?= e($isView ? '' : ($record['id'] ?? '')) ?>">
        <div class="mood-options">
            <?php foreach (['Senang' => ':)', 'Cemas' => ':|', 'Biasa aja' => ':D', 'Sedih' => ':(', 'Marah' => '>:('] as $label => $icon): ?>
                <label>
                    <input type="radio" name="mood_label" value="<?= e($label) ?>" <?= (($record['mood_label'] ?? 'Biasa aja') === $label) ? 'checked' : '' ?> <?= $isView ? 'disabled' : '' ?>>
                    <span><?= e($icon) ?></span>
                    <?= e($label) ?>
                </label>
            <?php endforeach; ?>
        </div>
        <div class="form-grid modal-fields">
            <label>Tanggal
                <input type="date" name="mood_date" value="<?= e($record['mood_date'] ?? date('Y-m-d')) ?>" required <?= $isView ? 'disabled' : '' ?>>
            </label>
            <label>Aktivitas hari ini
                <input type="text" name="activity" value="<?= e($activityValue) ?>" placeholder="Bekerja" <?= $isView ? 'disabled' : '' ?>>
            </label>
            <label class="full">Catatan (opsional)
                <textarea name="note" placeholder="Ceritakan sedikit tentang harimu hari ini..." <?= $isView ? 'disabled' : '' ?>><?= e($record['note'] ?? '') ?></textarea>
            </label>
        </div>
        <?php if (!$isView): ?>
            <button class="btn modal-submit" type="submit"><?= $record ? 'Edit Data Mood' : 'Simpan Data Mood' ?></button>
        <?php endif; ?>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
