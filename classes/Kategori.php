<?php
/**
 * Kategori.php
 * Inheritance - extends Todo
 */
require_once __DIR__ . '/Todo.php';

class Kategori extends Todo {

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    // Ambil semua kategori milik user
    // Override method dari parent (Polymorphism)
    public function getAll($userId) {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM kategori WHERE user_id = ? ORDER BY nama ASC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Tambah kategori baru
    // Override method dari parent
    public function create($userId, $nama, $deskripsi = '', $tanggal = '', $warna = '#3b82f6') {
        // Parameter $deskripsi & $tanggal dibiarkan agar cocok dengan parent
        // Warna diambil dari $warna
        $stmt = $this->pdo->prepare(
            'INSERT INTO kategori (user_id, nama, warna) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$userId, $nama, $warna]);
    }
}