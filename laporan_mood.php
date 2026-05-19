<?php
require_once 'config.php';
require_login();

if (!$pdo) {
    $title = 'Laporan Mood';
    $active = 'mood';
    require 'includes/header.php';
    echo '<section class="section"><p class="message error">Database belum tersambung. Import database.sql dulu di phpMyAdmin.</p></section>';
    require 'includes/footer.php';
    exit;
}

$userId = $_SESSION['user_id'];
$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$countStmt = $pdo->prepare('SELECT COUNT(*) FROM moods WHERE user_id = ?');
$countStmt->execute([$userId]);
$totalRows = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalRows / $perPage));

$stmt = $pdo->prepare('SELECT * FROM moods WHERE user_id = ? ORDER BY mood_date DESC, id DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $userId, PDO::PARAM_INT);
$stmt->bindValue(2, $perPage, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

$title = 'Laporan Mood';
$active = 'mood';
require 'includes/header.php';
?>
<section class="section">
    <div class="toolbar">
        <div>
            <h1>Berikan Ruang untuk Dirimu Bercerita.</h1>
            <p>Langkah pertama menuju kesehatan mental yang kuat adalah dengan memberikan validasi pada setiap perasaan yang hadir.</p>
        </div>
        <a class="btn green" href="mood.php?add=1">Tambah Data Mood</a>
    </div>
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Perasaan Saat Ini</th>
                    <th>Tanggal</th>
                    <th>Aktivitas Hari Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <?php
                    $firstLine = strtok($row['note'] ?? '', "\n");
                    $activity = trim(str_replace('Aktivitas:', '', $firstLine)) ?: '-';
                    ?>
                    <tr>
                        <td><?= e($row['mood_label']) ?></td>
                        <td><?= e(date('d/m/Y', strtotime($row['mood_date']))) ?></td>
                        <td><?= e($activity) ?></td>
                        <td class="actions">
                            <a class="btn icon icon-view" href="mood.php?view=<?= e($row['id']) ?>" title="Lihat"><span></span></a>
                            <a class="btn icon icon-edit yellow" href="mood.php?edit=<?= e($row['id']) ?>" title="Edit"><span></span></a>
                            <a class="btn icon icon-trash danger" href="mood.php?delete=<?= e($row['id']) ?>" onclick="return confirm('Hapus data mood ini?')" title="Hapus"><span></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?><tr><td colspan="4">Belum ada data mood.</td></tr><?php endif; ?>
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
