<?php
session_start();
$valid_langs = ['en', 'it', 'es', 'fr', 'de', 'pt', 'zh', 'ja', 'ar', 'hi'];
if (isset($_GET['lang']) && in_array($_GET['lang'], $valid_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $referer);
exit;
