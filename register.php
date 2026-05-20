<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$pdo) {
        $error = 'Database belum tersambung. Import database.sql dulu di phpMyAdmin.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($name === '' || $email === '' || strlen($password) < 6) {
            $error = 'Nama, email, dan password minimal 6 karakter wajib diisi.';
        } elseif ($password !== $confirm) {
            $error = 'Konfirmasi password tidak sama.';
        } else {
            try {
                $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
                $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                redirect_to('login.php');
            } catch (PDOException $e) {
                $error = 'Email sudah digunakan.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - HealthMood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <form class="auth-card" method="post">
        <img src="public/WhatsApp-Image-2026-04-29-at-21-03-52-removebg-preview-1@2x.png" alt="HealthMood">
        <h1>Register Account</h1>
        <?php if ($error): ?><p class="message error"><?= e($error) ?></p><?php endif; ?>
        <label><input type="email" name="email" placeholder="Email" style="margin-bottom: 12px;" required></label>
        <label><input type="text" name="name" placeholder="Username" style="margin-bottom: 12px;" required></label>
        <label class="password-field" style="margin-bottom: 12px;">
            <input id="registerPassword" type="password" name="password" placeholder="Password" minlength="6" required>
            <button class="eye-toggle" type="button" data-toggle-password="#registerPassword" aria-label="Lihat password"></button>
        </label>
        <label class="password-field" style="margin-bottom: 12px;">
            <input id="confirmPassword" type="password" name="confirm_password" placeholder="Confirm Password" minlength="6" required>
            <button class="eye-toggle" type="button" data-toggle-password="#confirmPassword" aria-label="Lihat password"></button>
        </label>   
        <button class="btn" type="submit">CREATE ACCOUNT</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <script src="assets/js/app.js"></script>
    </form>
</body>
</html>