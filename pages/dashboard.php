<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Todo.php';
require_once '../includes/functions.php';

requireLogin();

$todo = new Todo($pdo);

// Proses centang selesai
if (isset($_GET['selesai'])) {
    $todo->updateSelesai((int)$_GET['selesai'], $_SESSION['user_id'], (int)$_GET['status']);
    header('Location: dashboard.php');
    exit;
}

// Proses hapus
if (isset($_GET['hapus'])) {
    $todo->delete((int)$_GET['hapus'], $_SESSION['user_id']);
    header('Location: dashboard.php');
    exit;
}

// Bulan & tahun kalender
$bulan = (int)($_GET['bulan'] ?? date('n'));
$tahun = (int)($_GET['tahun'] ?? date('Y'));

$namaBulan = [
    1=>'Januari', 2=>'Februari', 3=>'Maret',    4=>'April',
    5=>'Mei',     6=>'Juni',     7=>'Juli',      8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November', 12=>'Desember'
];

// Navigasi bulan
$prev = mktime(0, 0, 0, $bulan - 1, 1, $tahun);
$next = mktime(0, 0, 0, $bulan + 1, 1, $tahun);

// Data kalender
$hariPertama = date('N', mktime(0, 0, 0, $bulan, 1, $tahun));
$jumlahHari  = date('t', mktime(0, 0, 0, $bulan, 1, $tahun));

// Ambil semua catatan bulan ini
$semuaTodo = $todo->getByBulan($_SESSION['user_id'], $bulan, $tahun);

// Kelompokkan per tanggal - Array
$todoPerTanggal = [];
foreach ($semuaTodo as $t) {
    $todoPerTanggal[$t['tanggal']][] = $t;
}

// Tanggal yang dipilih
$terpilih     = $_GET['tgl'] ?? date('Y-m-d');
$todoTerpilih = $todoPerTanggal[$terpilih] ?? [];
?>
<?php require_once '../includes/header.php'; ?>

<div class="max-w-5xl mx-auto mt-8 px-4 pb-10">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">Dashboard</h2>
        <a href="todo/tambah.php"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">
            + Tambah Catatan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- KALENDER -->
        <div class="bg-white rounded-xl shadow p-5">

            <!-- Header bulan -->
            <div class="flex justify-between items-center mb-4">
                <a href="?bulan=<?= date('n', $prev) ?>&tahun=<?= date('Y', $prev) ?>"
                   class="text-gray-400 hover:text-blue-600 text-2xl font-bold">‹</a>
                <h3 class="font-bold text-blue-900">
                    <?= $namaBulan[$bulan] ?> <?= $tahun ?>
                </h3>
                <a href="?bulan=<?= date('n', $next) ?>&tahun=<?= date('Y', $next) ?>"
                   class="text-gray-400 hover:text-blue-600 text-2xl font-bold">›</a>
            </div>

            <!-- Nama hari -->
            <div class="grid grid-cols-7 mb-1">
                <?php foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $h): ?>
                <div class="text-center text-xs font-semibold text-gray-400 py-1"><?= $h ?></div>
                <?php endforeach; ?>
            </div>

            <!-- Grid tanggal -->
            <div class="grid grid-cols-7 gap-1">

                <!-- Kolom kosong sebelum hari pertama -->
                <?php for ($i = 1; $i < $hariPertama; $i++): ?>
                <div></div>
                <?php endfor; ?>

                <!-- for loop untuk tanggal -->
                <?php for ($hari = 1; $hari <= $jumlahHari; $hari++): ?>
                <?php
                $tglStr     = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
                $adaTodo    = isset($todoPerTanggal[$tglStr]);
                $isHariIni  = $tglStr === date('Y-m-d');
                $isTerpilih = $tglStr === $terpilih;
                ?>
                <a href="?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&tgl=<?= $tglStr ?>"
                   class="flex flex-col items-center justify-center rounded-lg py-1.5 text-sm transition
                          <?php
                          if ($isTerpilih)   echo 'bg-blue-600 text-white font-bold';
                          elseif ($isHariIni) echo 'bg-blue-100 text-blue-700 font-bold';
                          else                echo 'hover:bg-gray-100 text-gray-700';
                          ?>">
                    <?= $hari ?>
                    <?php if ($adaTodo): ?>
                    <span class="w-1 h-1 rounded-full mt-0.5 <?= $isTerpilih ? 'bg-white' : 'bg-blue-500' ?>"></span>
                    <?php endif; ?>
                </a>
                <?php endfor; ?>

            </div>
        </div>

        <!-- CATATAN TANGGAL TERPILIH -->
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-blue-900 mb-4">
                📅 <?= formatTanggal($terpilih) ?>
            </h3>

            <?php if (empty($todoTerpilih)): ?>
            <div class="text-center text-gray-400 py-8">
                <p class="text-3xl mb-2">📭</p>
                <p class="text-sm">Tidak ada catatan di tanggal ini</p>
                <a href="todo/tambah.php"
                   class="mt-3 inline-block text-sm text-blue-600 hover:underline">
                    + Tambah catatan
                </a>
            </div>

            <?php else: ?>
            <div class="space-y-3">
                <!-- foreach loop -->
                <?php foreach ($todoTerpilih as $t):
                    $p       = warnaPrioritas($t['prioritas']);
                    $selesai = $t['selesai'] == 1;
                ?>
                <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-100
                            <?= $selesai ? 'opacity-60' : '' ?>">

                    <!-- Centang selesai -->
                    <a href="?selesai=<?= $t['id'] ?>&status=<?= $selesai ? 0 : 1 ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&tgl=<?= $terpilih ?>"
                       class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 text-xs
                              <?= $selesai ? 'bg-blue-500 border-blue-500 text-white' : 'border-gray-300 hover:border-blue-400' ?>">
                        <?= $selesai ? '✓' : '' ?>
                    </a>

                    <!-- Konten -->
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800
                                  <?= $selesai ? 'line-through text-gray-400' : '' ?>">
                            <?= bersihkan($t['judul']) ?>
                        </p>
                        <?php if ($t['deskripsi']): ?>
                        <p class="text-xs text-gray-400 mt-0.5"><?= bersihkan($t['deskripsi']) ?></p>
                        <?php endif; ?>
                        <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block <?= $p['warna'] ?>">
                            <?= $p['ikon'] ?> <?= ucfirst($t['prioritas']) ?>
                        </span>
                    </div>

                    <!-- Edit & Hapus -->
                    <div class="flex gap-2">
                        <a href="todo/edit.php?id=<?= $t['id'] ?>"
                           class="text-yellow-500 hover:text-yellow-700 transition">✏️</a>
                        <a href="?hapus=<?= $t['id'] ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&tgl=<?= $terpilih ?>"
                           onclick="return confirm('Yakin hapus catatan ini?')"
                           class="text-red-400 hover:text-red-600 transition">🗑️</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
