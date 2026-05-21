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
        <label style="margin-bottom: 12px; display: block;">
            <input type="email" name="email" placeholder="Email" required>
        </label>
        <label class="password-field">
            <input id="loginPassword" type="password" name="password" placeholder="Password" required>
            <button class="eye-toggle" type="button" data-toggle-password="#loginPassword" aria-label="Lihat password" style="margin-right: 8px;"></button>
        </label>
        <p><a href="0">Forgot Password?</a></p>
        <button class="btn" type="submit">LOGIN</button>
        <p>Don't have account? <a href="register.php">Create here</a></p>
        <div class="social-row">

    <!-- Google -->
    <span class="social-icon google">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.17 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.2C12.43 13.19 17.74 9.5 24 9.5z"/>
        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
        <path fill="#FBBC05" d="M10.54 28.43c-1.22-2.96-1.22-6.23 0-9.19l-7.98-6.2C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.96l7.98-6.53z"/>
        <path fill="#34A853" d="M24 48c6.48 0 12-2.13 16-5.81l-7.73-6c-2.15 1.45-4.92 2.31-8.27 2.31-6.26 0-11.57-3.69-13.46-9.07l-7.98 6.53C6.51 42.62 14.62 48 24 48z"/>
    </svg>
</span>

    <!-- Facebook -->
    <span class="social-icon facebook">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path fill="#1877F2" d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
        </svg>
    </span>

    <!-- Apple -->
    <span class="social-icon apple">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path fill="#000000" d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516s1.52.087 2.475-1.258.762-2.391.728-2.43"/>
            <path fill="#000000" d="M12.5 6.5c-.9-1.2-2.3-1.4-3.1-1.4-.9 0-1.6.3-2.2.3-.6 0-1.3-.3-2.1-.3-1.6 0-3.2 1.3-3.2 3.8 0 2.6 1.9 6.1 3.5 6.1.7 0 1.2-.3 1.8-.3.6 0 1.1.3 1.8.3 1.6 0 3.3-3.3 3.3-5.9 0-1.3-.4-2.2-1.1-2.6z"/>
        </svg>
    </span>

</div>
        <script src="assets/js/app.js"></script>
    </form>
</body>
</html>
