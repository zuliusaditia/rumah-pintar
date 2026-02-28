<?php
// =======================
// SESSION HARDENING
// =======================

// Force session to use cookies only
ini_set('session.use_only_cookies', 1);

// Prevent session fixation
ini_set('session.use_strict_mode', 1);

// Make session cookie inaccessible to JavaScript
ini_set('session.cookie_httponly', 1);

// Kalau nanti sudah HTTPS production, aktifkan ini:
// ini_set('session.cookie_secure', 1);

// Disable caching (biar back button aman)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Start session
session_start();

// Regenerate ID setiap 15 menit
if (!isset($_SESSION['LAST_REGEN'])) {
    $_SESSION['LAST_REGEN'] = time();
} elseif (time() - $_SESSION['LAST_REGEN'] > 900) {
    session_regenerate_id(true);
    $_SESSION['LAST_REGEN'] = time();
}

// =======================
// LOAD DATABASE
// =======================
require_once __DIR__ . "/../koneksi.php";

// Generate CSRF token jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// =======================
// END OF SESSION CONFIG
// =======================
ini_set('session.cookie_samesite', 'Strict');