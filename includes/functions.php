<?php
/**
 * functions.php
 * Kumpulan fungsi helper untuk seluruh aplikasi.
 * Kriteria (e): Fungsi/Prosedur
 */

// Cek login - kalau belum login redirect ke login
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../pages/login.php');
        exit;
    }
}

// Bersihkan input dari karakter berbahaya
function bersihkan($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Format tanggal ke bahasa Indonesia
// Contoh: 2024-03-15 → 15 Maret 2024
function formatTanggal($tanggal) {
    $namaBulan = [
        1=>'Januari', 2=>'Februari', 3=>'Maret',
        4=>'April',   5=>'Mei',      6=>'Juni',
        7=>'Juli',    8=>'Agustus',  9=>'September',
        10=>'Oktober',11=>'November',12=>'Desember'
    ];
    $ts = strtotime($tanggal);
    return date('d', $ts) . ' ' . $namaBulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

// Kembalikan warna & ikon sesuai prioritas
function warnaPrioritas($prioritas) {
    if ($prioritas === 'tinggi') {
        return ['warna' => 'bg-red-100 text-red-700', 'ikon' => '🔴'];
    } elseif ($prioritas === 'sedang') {
        return ['warna' => 'bg-yellow-100 text-yellow-700', 'ikon' => '🟡'];
    } else {
        return ['warna' => 'bg-green-100 text-green-700', 'ikon' => '🟢'];
    }
}
