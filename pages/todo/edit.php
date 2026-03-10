<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Todo.php';
require_once '../../includes/functions.php';

requireLogin();

$todo = new Todo($pdo);
$id   = (int)($_GET['id'] ?? 0);
$data = $todo->findById($id, $_SESSION['user_id']);

if (!$data) {
    header('Location: ../dashboard.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal   = $_POST['tanggal'];
    $prioritas = $_POST['prioritas'];

    if (empty($judul) || empty($tanggal)) {
        $error = 'Judul dan tanggal wajib diisi!';
    } else {
        $todo->update($id, $_SESSION['user_id'], $judul, $deskripsi, $tanggal, $prioritas);
        $data['judul']     = $judul;
        $data['deskripsi'] = $deskripsi;
        $data['tanggal']   = $tanggal;
        $data['prioritas'] = $prioritas;
        $success = 'Catatan berhasil diperbarui!';
    }
}
?>
<?php require_once '../../includes/header.php'; ?>

<div class="max-w-xl mx-auto mt-10 px-4 pb-10">

    <a href="../dashboard.php" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        ← Kembali ke Dashboard
    </a>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-blue-900 mb-6">Edit Catatan</h2>

        <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg mb-4">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 text-sm px-4 py-2 rounded-lg mb-4">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label>
                <input type="text" name="judul" required
                       value="<?= htmlspecialchars($data['judul']) ?>"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-400"
                          ><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                <input type="date" name="tanggal" required
                       value="<?= $data['tanggal'] ?>"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                <select name="prioritas"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="rendah" <?= $data['prioritas'] === 'rendah' ? 'selected' : '' ?>>🟢 Rendah</option>
                    <option value="sedang" <?= $data['prioritas'] === 'sedang' ? 'selected' : '' ?>>🟡 Sedang</option>
                    <option value="tinggi" <?= $data['prioritas'] === 'tinggi' ? 'selected' : '' ?>>🔴 Tinggi</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white
                               font-semibold py-2 rounded-lg transition text-sm">
                    💾 Simpan Perubahan
                </button>
                <a href="../dashboard.php"
                   class="flex-1 text-center bg-gray-200 hover:bg-gray-300
                          text-gray-700 font-semibold py-2 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
