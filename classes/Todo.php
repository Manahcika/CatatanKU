<?php
/**
 * Todo.php
 * Class untuk CRUD catatan.
 */
class Todo {

    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ambil semua catatan milik user
    public function getAll($userId) {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM todos WHERE user_id = ? ORDER BY tanggal ASC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Ambil catatan berdasarkan bulan & tahun (untuk kalender)
    public function getByBulan($userId, $bulan, $tahun) {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM todos WHERE user_id = ?
             AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
             ORDER BY tanggal ASC'
        );
        $stmt->execute([$userId, $bulan, $tahun]);
        return $stmt->fetchAll();
    }

    // Ambil satu catatan berdasarkan ID
    public function findById($id, $userId) {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM todos WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    // Tambah catatan baru
    public function create($userId, $judul, $deskripsi, $tanggal, $prioritas) {
        $stmt = $this->pdo->prepare(
            'INSERT INTO todos (user_id, judul, deskripsi, tanggal, prioritas)
             VALUES (?, ?, ?, ?, ?)'
        );
        return $stmt->execute([$userId, $judul, $deskripsi, $tanggal, $prioritas]);
    }

    // Update catatan
    public function update($id, $userId, $judul, $deskripsi, $tanggal, $prioritas) {
        $stmt = $this->pdo->prepare(
            'UPDATE todos SET judul=?, deskripsi=?, tanggal=?, prioritas=?
             WHERE id=? AND user_id=?'
        );
        return $stmt->execute([$judul, $deskripsi, $tanggal, $prioritas, $id, $userId]);
    }

    // Hapus catatan
    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare(
            'DELETE FROM todos WHERE id = ? AND user_id = ?'
        );
        return $stmt->execute([$id, $userId]);
    }

    // Update status selesai
    public function updateSelesai($id, $userId, $status) {
        $stmt = $this->pdo->prepare(
            'UPDATE todos SET selesai = ? WHERE id = ? AND user_id = ?'
        );
        return $stmt->execute([$status, $id, $userId]);
    }
}
