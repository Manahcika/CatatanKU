<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatatanKu 📝</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen">

<nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
    <a href="/catatanku/pages/dashboard.php" class="text-xl font-bold text-blue-600">
        CatatanKu 📝
    </a>
    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">
            Halo, <strong><?= bersihkan($_SESSION['nama'] ?? '') ?></strong>!
        </span>
        <a href="/catatanku/pages/logout.php"
           class="text-sm bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition">
            Logout
        </a>
    </div>
</nav>
