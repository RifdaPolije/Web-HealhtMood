<?php
require_once 'config.php';
require_login();
$title = 'Game';
$active = 'game';
require 'includes/header.php';

$games = [
    [
        'title' => 'Subway Surfers',
        'desc' => 'game endless runner seru! Hindari kejaran petugas dengan melompati kereta dan mengumpulkan koin.',
        'rating' => '4.7',
        'image' => 'assets/images/game/image-13@2x.png',
        'url' => 'https://poki.com/en/g/subway-surfers',
    ],
    [
        'title' => 'Anycolor',
        'desc' => 'game mewarnai kasual. Warnai gambar dengan memilih warna dari spidol di bagian bawah layar.',
        'rating' => '4.8',
        'image' => 'assets/images/game/image-14@2x.png',
        'url' => 'https://poki.com/en/g/anycolor',
    ],
    [
        'title' => 'Poxel.io',
        'desc' => 'FPS online serba cepat yang dirancang untuk keahlian murni. Ikuti pertandingan singkat dan intens melawan pemain sungguhan',
        'rating' => '4.5',
        'image' => 'assets/images/game/poxel-io.png',
        'url' => 'https://www.crazygames.com/',
    ],
    [
        'title' => 'Block Blaster',
        'desc' => 'Susun blok, bersihkan baris, dan nikmati permainan puzzle ringan untuk istirahat sebentar.',
        'rating' => '4.6',
        'image' => 'assets/images/game/Screenshot-2026-04-30-111717-11@2x.png',
        'url' => 'https://poki.com/',
    ],
    [
        'title' => 'Merge Fruits',
        'desc' => 'Uji kecerdasan & kreativitas dengan teka teki lucu yg mengasah logika dan berfikir kritis',
        'rating' => '4.7',
        'image' => 'assets/images/game/Screenshot-2026-04-30-112015-1@2x.png',
        'url' => 'https://poki.com/en/g/jelly-fruit-merge',
    ],
    [
        'title' => 'Sudoku',
        'desc' => 'Uji kecerdasan & kreativitas dengan teka teki lucu yg mengasah logika dan berfikir kritis',
        'rating' => '4.8',
        'image' => 'assets/images/game/Screenshot-2026-04-30-111717-21@2x.png',
        'url' => 'https://poki.com/en/sudoku',
    ],
    [
        'title' => 'Brain Test: Tricky Puzzles',
        'desc' => 'Uji kecerdasan & kreativitas dengan teka teki lucu yg mengasah logika dan berfikir kritis',
        'rating' => '4.9',
        'image' => 'assets/images/game/Screenshot-2026-04-30-111717-12@2x.png',
        'url' => 'https://poki.com/en/g/brain-test-tricky-puzzles',
    ],
    [
        'title' => 'Nails DIY: Manicure Master',
        'desc' => 'Uji kecerdasan & kreativitas dengan teka teki lucu yg mengasah logika dan berfikir kritis',
        'rating' => '4.8',
        'image' => 'assets/images/game/Screenshot-2026-04-30-111717-1@2x.png',
        'url' => 'https://poki.com/en/g/nails-diy-manicure-master',
    ],
    [
        'title' => 'Fish Eat Getting Big',
        'desc' => 'Uji kecerdasan & kreativitas dengan teka teki lucu yg mengasah logika dan berfikir kritis',
        'rating' => '4.8',
        'image' => 'assets/images/game/Screenshot-2026-04-30-111717-13@2x.png',
        'url' => 'https://www.game-poki.com/game/fish-eat-getting-big',
    ],
];
?>
<section class="section game-section">
    <div class="game-heading">
        <h1>Pilih game untuk refreshing otak mu</h1>
        <img src="assets/images/game/mingcute-game-2-fill.svg" alt="" aria-hidden="true">
    </div>
    <div class="game-grid">
        <?php foreach ($games as $game): ?>
            <article class="game-card">
                <img src="<?= e($game['image']) ?>" alt="<?= e($game['title']) ?>">
                <div class="game-body">
                    <h2><?= e($game['title']) ?></h2>
                    <p><?= e($game['desc']) ?></p>
                    <div class="rating">
                        <span><span class="star">&#9733;</span> <?= e($game['rating']) ?></span>
                        <a class="btn" href="<?= e($game['url']) ?>" target="_blank" rel="noopener">Play</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
