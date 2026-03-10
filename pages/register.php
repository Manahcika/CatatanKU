<?php
session_start();
require_once '../config/database.php';
require_once '../classes/User.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error   = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $konfirm  = $_POST['confirm_password'];

    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif ($password !== $konfirm) {
        $error = 'Konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $user   = new User($pdo);
        $result = $user->register($nama, $email, $password);

        if ($result) {
            $success = 'Akun berhasil dibuat! Silakan login.';
        } else {
            $error = 'Email sudah terdaftar, gunakan email lain!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CatatanKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100">

    <div class="text-center mt-10 text-3xl font-bold text-blue-900">
        <h1>CatatanKU 📝</h1>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-center font-bold text-blue-900 mb-6 text-2xl">REGISTER</h1>

        <!-- Pesan Error -->
        <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg mb-4">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <!-- Pesan Success -->
        <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 text-sm px-4 py-2 rounded-lg mb-4">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">

            <!-- Name Input -->
            <div>
                <label class="block text-sm font-medium text-gray-900">Name</label>
                <div class="mt-2">
                    <input type="text" name="nama" required
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900
                                  shadow-sm ring-1 ring-inset ring-gray-300
                                  focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">
                </div>
            </div>
       
            <!-- Email Input -->
            <div>
                <label class="block text-sm font-medium text-gray-900">Email address</label>
                <div class="mt-2">
                    <input type="email" name="email" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900
                                  shadow-sm ring-1 ring-inset ring-gray-300
                                  focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label class="block text-sm font-medium text-gray-900">Password</label>
                <div class="mt-2">
                    <input type="password" name="password" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900
                                  shadow-sm ring-1 ring-inset ring-gray-300
                                  focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">
                </div>
            </div>

            <!-- Confirm Password Input -->
            <div>
                <label class="block text-sm font-medium text-gray-900">Confirm Password</label>
                <div class="mt-2">
                    <input type="password" name="confirm_password" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900
                                  shadow-sm ring-1 ring-inset ring-gray-300
                                  focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full rounded-md bg-indigo-500 hover:bg-fuchsia-500 px-3 py-2
                           text-sm font-semibold text-white shadow-sm transition duration-300">
                REGISTER
            </button>

            <p class="text-sm text-gray-500 text-center">
                Already have an account?
                <a href="login.php" class="font-semibold text-blue-600 hover:text-blue-500">
                    Sign in
                </a>
            </p>

        </form>
    </div>

</body>
</html>
