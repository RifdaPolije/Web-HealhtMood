<?php
require_once 'config.php';

if (is_logged_in()) {
    redirect_to('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$pdo) {
        $error = 'Database belum tersambung. Import database.sql dulu di phpMyAdmin.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            redirect_to('index.php');
        }

        $error = 'Email atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - HealthMood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <form class="auth-card" method="post">
        <img src="public/WhatsApp-Image-2026-04-29-at-21-03-52-removebg-preview-1@2x.png" alt="HealthMood">
        <h1>HealthMood</h1>
        <p>Catat mood dan pola tidurmu setiap hari, temukan pola tersembunyi di balik tidur dan perasaanmu.</p>
        <?php if ($error): ?><p class="message error"><?= e($error) ?></p><?php endif; ?>
        <label>
            <input type="email" name="email" value="admin@healthmood.test" placeholder="Email" required>
        </label>
<<<<<<< HEAD
        
        <label>
            <input type="password" name="password" placeholder="🔒  Password" required>
=======
        <label class="password-field">
            <input id="loginPassword" type="password" name="password" placeholder="Password" required>
            <button class="eye-toggle" type="button" data-toggle-password="#loginPassword" aria-label="Lihat password"></button>
>>>>>>> d495e45ef926c60f1e30d117ab147b08ebd0977f
        </label>
        <p><a href="#">Forgot Password?</a></p>
        <button class="btn" type="submit">LOGIN</button>
        <p>Don't have account? <a href="register.php">Create here</a></p>
        <div class="social-row">
            <span>G</span>
            <span>f</span>
            <span>A</span>
        </div>
        <script src="assets/js/app.js"></script>
    </form>
</body>
</html>
