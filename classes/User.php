<?php
/**
 * User.php
 * Class untuk login dan register pengguna.
 * Kriteria (h): Class, properties, method, hak akses
 */
class User {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Login - return data user kalau berhasil, false kalau gagal
    public function login($email, $password) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Register - return false kalau email sudah dipakai
    public function register($nama, $email, $password) {
        $cek = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
        $cek->execute([$email]);
        if ($cek->fetch()) {
            return false;
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (nama, email, password) VALUES (?, ?, ?)'
        );
        $stmt->execute([$nama, $email, $hash]);
        return true;
    }
}
