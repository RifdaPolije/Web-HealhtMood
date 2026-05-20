<?php
session_start();

$dbHost = 'localhost';
$dbName = 'healthmood';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    $pdo = null;
}

if ($pdo) {
    try {
        $columns = $pdo->query("SHOW COLUMNS FROM sleeps LIKE 'sleep_start'")->fetchAll();
        if (!$columns) {
            $pdo->exec("ALTER TABLE sleeps ADD sleep_start TIME NOT NULL DEFAULT '21:25:00' AFTER sleep_date");
        }

        $columns = $pdo->query("SHOW COLUMNS FROM sleeps LIKE 'sleep_end'")->fetchAll();
        if (!$columns) {
            $pdo->exec("ALTER TABLE sleeps ADD sleep_end TIME NOT NULL DEFAULT '06:30:00' AFTER sleep_start");
        }
    } catch (PDOException $e) {
        // Database may not be imported yet; pages will show a setup message.
    }
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function current_user_name()
{
    return $_SESSION['user_name'] ?? 'Pengguna';
}

function redirect_to($path)
{
    header("Location: {$path}");
    exit;
}
