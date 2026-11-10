<?php
/**
 * Fungsi bantu kecil yang dipakai di seluruh view.
 */

// Escape output supaya aman dari XSS
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Redirect lalu hentikan script
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

// Simpan pesan sekali tampil (flash message) di session
function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// Ambil & hapus flash message
function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}
