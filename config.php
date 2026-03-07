<?php
session_start();

// Load translations
require_once 'translations.php';
$lang = (isset($_SESSION['lang']) && isset($translations[$_SESSION['lang']])) ? $_SESSION['lang'] : 'en';
$t = $translations[$lang];

// GCS configuration
$gcs_bucket = getenv('GCS_BUCKET') ?: 'awesome-project-489421-images';

// Database configuration
$db_socket = getenv('DB_SOCKET') ?: '';
$db_host   = getenv('DB_HOST') ?: '35.192.15.69';
$db_name   = getenv('DB_NAME') ?: 'image_catalog';
$db_user   = getenv('DB_USER') ?: 'appmod-phpapp-user';
$db_pass   = getenv('DB_PASS') ?: '';

try {
    if ($db_socket) {
        $dsn = "mysql:unix_socket=$db_socket;dbname=$db_name";
    } else {
        $dsn = "mysql:host=$db_host;dbname=$db_name";
    }
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($t['connection_error'] . ": " . $e->getMessage());
}
?>
