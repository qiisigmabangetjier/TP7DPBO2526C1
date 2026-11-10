<?php
/**
 * Titik awal aplikasi (front controller).
 * 1. Load config + class
 * 2. Tentukan halaman dari ?page=
 * 3. Tampilkan header -> view -> footer
 */
session_start();
ob_start(); // supaya redirect() di dalam view tetap bisa jalan

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/class/Genre.php';
require_once __DIR__ . '/class/Game.php';
require_once __DIR__ . '/class/Pick.php';

// Buat koneksi PDO sekali, dipakai semua view
$database = new Database();
$db       = $database->getConnection();

// Whitelist halaman yang boleh dibuka
$allowedPages = ['games', 'genres', 'picks'];
$page = $_GET['page'] ?? 'games'; // halaman default: daftar game
if (!in_array($page, $allowedPages, true)) {
    $page = 'games';
}

require __DIR__ . '/view/header.php';
require __DIR__ . '/view/' . $page . '.php';
require __DIR__ . '/view/footer.php';

ob_end_flush();
