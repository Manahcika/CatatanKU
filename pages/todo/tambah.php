<?php
// Memulai session untuk menyimpan data login user
session_start();

// Menghubungkan file konfigurasi database
require_once '../../config/database.php';

// Menghubungkan class Todo (untuk operasi todo seperti create, update, delete)
require_once '../../classes/Todo.php';

// Menghubungkan file functions (biasanya berisi fungsi bantu seperti login check)
require_once '../../includes/functions.php';

// Mengecek apakah user sudah login
// Jika belum login biasanya akan diarahkan ke halaman login
requireLogin();

// Variabel untuk menyimpan pesan error
$error = '';

// Variabel untuk menyimpan pesan sukses
$success = '';

// Mengecek apakah form dikirim menggunakan method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil input judul dari form lalu menghapus spasi di awal dan akhir
    $judul = trim($_POST['judul']);

    // Mengambil input deskripsi
    $deskripsi = trim($_POST['deskripsi']);

    // Mengambil tanggal dari input form
    $tanggal = $_POST['tanggal'];

    // Mengambil prioritas dari dropdown
    $prioritas = $_POST['prioritas'];

    // Validasi: jika judul atau tanggal kosong
    if (empty($judul) || empty($tanggal)) {

        // Menampilkan pesan error
        $error = 'Judul dan tanggal wajib diisi!';

    } else {

        // Membuat object Todo dengan koneksi database ($pdo)
        $todo = new Todo($pdo);

        // Menyimpan data todo baru ke database
        // Parameter:
        // user_id dari session
        // judul
        // deskripsi
        // tanggal
        // prioritas
        $todo->create($_SESSION['user_id'], $judul, $deskripsi, $tanggal, $prioritas);

        // Menampilkan pesan berhasil
        $success = 'Catatan berhasil ditambahkan!';
    }
}
?>

<?php
// Memanggil header website (biasanya berisi navbar, meta tag, dll)
require_once '../../includes/header.php';
?>

<!-- Container utama halaman -->
<div class="max-w-xl mx-auto mt-10 px-4 pb-10">

    <!-- Tombol kembali ke dashboard -->
    <a href="../dashboard.php" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        ← Kembali ke Dashboard
    </a>

    <!-- Card form -->
    <div class="bg-white rounded-xl shadow p-6">

        <!-- Judul halaman -->
        <h2 class="text-xl font-bold text-blue-900 mb-6">Tambah Catatan Baru</h2>

        <!-- Jika ada error maka tampilkan pesan error -->
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg mb-4">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Jika berhasil maka tampilkan pesan sukses -->
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 text-sm px-4 py-2 rounded-lg mb-4">
                ✅ <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Form tambah todo -->
        <form action="" method="POST" class="space-y-4">

            <!-- Input Judul -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label>

                <!-- 
                Input judul
                required = wajib diisi
                value digunakan agar data tetap ada jika form gagal submit
                -->
                <input type="text" name="judul" required value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>"
                    placeholder="Contoh: Belajar PHP" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Input Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>

                <!-- Textarea untuk catatan tambahan -->
                <textarea name="deskripsi" rows="3" placeholder="Catatan tambahan (opsional)..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
            </div>

            <!-- Input tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>

                <!-- 
                Input tipe date
                Jika belum ada input dari user maka otomatis memakai tanggal hari ini
                -->
                <input type="date" name="tanggal" required value="<?= $_POST['tanggal'] ?? date('Y-m-d') ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Dropdown Prioritas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>

                <!-- Select untuk memilih prioritas tugas -->
                <select name="prioritas" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-400">

                    <!-- Opsi prioritas rendah -->
                    <option value="rendah">🟢 Rendah</option>

                    <!-- Opsi prioritas sedang (default selected) -->
                    <option value="sedang" selected>🟡 Sedang</option>

                    <!-- Opsi prioritas tinggi -->
                    <option value="tinggi">🔴 Tinggi</option>

                </select>
            </div>

            <!-- Tombol aksi -->
            <div class="flex gap-3 pt-2">

                <!-- Tombol submit untuk menyimpan catatan -->
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white
                               font-semibold py-2 rounded-lg transition text-sm">
                    + Simpan Catatan
                </button>

                <!-- Tombol batal kembali ke dashboard -->
                <a href="../dashboard.php" class="flex-1 text-center bg-gray-200 hover:bg-gray-300
                          text-gray-700 font-semibold py-2 rounded-lg transition text-sm">
                    Batal
                </a>

            </div>

        </form>
    </div>
</div>

<?php
// Memanggil footer website
require_once '../../includes/footer.php';
?>