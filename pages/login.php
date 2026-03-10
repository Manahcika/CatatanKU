<?php
session_start();
require_once '../config/database.php';
require_once '../classes/User.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi!';
    } else {
        $user   = new User($pdo);
        $result = $user->login($email, $password);

        if ($result) {
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['nama']    = $result['nama'];
            $_SESSION['email']   = $result['email'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CatatanKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100">

    <div class="text-center mt-10 text-3xl font-bold text-blue-900">
        <h1>CatatanKU 📝</h1>
    </div>

    <div class="mt-16 sm:mx-auto sm:w-full sm:max-w-sm bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-center font-bold text-blue-900 mb-6 text-2xl">LOGIN</h1>

        <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg mb-4">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4"> 

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

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full rounded-md bg-blue-600 hover:bg-blue-500 px-3 py-2
                           text-sm font-semibold text-white shadow-sm transition duration-300">
                LOG IN
            </button>

            <p class="text-sm text-gray-500 text-center">
                Don't have an account?
                <a href="register.php" class="font-semibold text-blue-600 hover:text-blue-500">
                    Sign up
                </a>
            </p>

        </form>
    </div>

</body>
</html>
