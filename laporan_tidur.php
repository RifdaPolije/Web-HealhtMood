<?php
require_once 'config.php';
require_login();

if (!$pdo) {
    $title = 'Laporan Tidur';
    $active = 'tidur';
    require 'includes/header.php';
    echo '<section class="section"><p class="message error">Database belum tersambung. Import database.sql dulu di phpMyAdmin.</p></section>';
    require 'includes/footer.php';
    exit;
}

$userId = $_SESSION['user_id'];
$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$countStmt = $pdo->prepare('SELECT COUNT(*) FROM sleeps WHERE user_id = ?');
$countStmt->execute([$userId]);
$totalRows = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalRows / $perPage));

$stmt = $pdo->prepare('SELECT * FROM sleeps WHERE user_id = ? ORDER BY sleep_date DESC, id DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $userId, PDO::PARAM_INT);
$stmt->bindValue(2, $perPage, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

$title = 'Laporan Tidur';
$active = 'tidur';
require 'includes/header.php';
?>
<section class="section">
    <div class="toolbar">
        <div>
            <h1>Ringkasan Data Kesehatanmu Tidur mu.</h1>
            <p>Pantau kembali catatan tidur dan riwayat mood harianmu untuk melihat perkembangan kesehatan mental secara menyeluruh.</p>
        </div>
        <a class="btn green" href="tidur.php?add=1">Tambah Data Tidur</a>
    </div>
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Tanggal Tidur</th>
                    <th>Jam Mulai Tidur</th>
                    <th>Jam Mulai Bangun</th>
                    <th>Kualitas Tidur</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e(date('n/j/Y', strtotime($row['sleep_date']))) ?></td>
                        <td><?= e(date('h.i A', strtotime($row['sleep_start'] ?? '21:25'))) ?></td>
                        <td><?= e(date('h.i A', strtotime($row['sleep_end'] ?? '06:30'))) ?></td>
                        <td><?= ((int) $row['quality'] >= 4) ? 'Baik' : 'Cukup' ?></td>
                        <td class="actions">
                            <a class="btn icon icon-view" href="tidur.php?view=<?= e($row['id']) ?>" title="Lihat"><span></span></a>
                            <a class="btn icon icon-edit yellow" href="tidur.php?edit=<?= e($row['id']) ?>" title="Edit"><span></span></a>
                            <a class="btn icon icon-trash danger" href="tidur.php?delete=<?= e($row['id']) ?>" onclick="return confirm('Hapus data tidur ini?')" title="Hapus"><span></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?><tr><td colspan="5">Belum ada data tidur.</td></tr><?php endif; ?>
            </tbody>
        </table>
        <div class="actions pagination">
            <?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>">&lt;</a><?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="<?= $i === $page ? 'active' : '' ?>" href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?><a href="?page=<?= $page + 1 ?>">&gt;</a><?php endif; ?>
        </div>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
