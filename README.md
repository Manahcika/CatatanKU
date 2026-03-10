# CatatanKu 📝

Aplikasi web To-Do List berbasis kalender yang dibangun menggunakan PHP, MySQL, dan Tailwind CSS.

---

## Fitur

- ✅ Register & Login akun
- 📅 Kalender bulanan interaktif
- ➕ Tambah, edit, hapus catatan
- 🔴🟡🟢 Filter catatan berdasarkan prioritas
- ✔️ Tandai catatan sebagai selesai

---

## Teknologi

| Teknologi | Keterangan |
|---|---|
| PHP | Backend & logika aplikasi |
| MySQL | Database penyimpanan data |
| Tailwind CSS (CDN) | Styling tampilan |
| XAMPP | Local server (Apache + MySQL) |

---

## Struktur Folder

```
catatanku/
├── index.php                 ← Redirect otomatis
├── catatanku.sql             ← File database
├── config/
│   └── database.php          ← Koneksi database
├── classes/
│   ├── User.php              ← Class login & register
│   ├── Todo.php              ← Class CRUD catatan
│   └── Kategori.php          ← Class extends Todo
├── includes/
│   ├── header.php            ← Navbar reusable
│   ├── footer.php            ← Footer reusable
│   └── functions.php         ← Fungsi helper
└── pages/
    ├── login.php
    ├── register.php
    ├── dashboard.php          ← Halaman utama + kalender
    ├── logout.php
    └── todo/
        ├── tambah.php
        └── edit.php
```

---

## Cara Instalasi

**1. Clone repository**
```bash
git clone https://github.com/Manahcika/CatatanKU.git
```
Letakkan folder di dalam htdocs XAMPP:
```
C:/xampp/htdocs/catatanku/
```

**2. Buat database**
- Buka phpMyAdmin → `http://localhost/phpmyadmin`
- Buat database baru bernama `catatanku`
- Import file `catatanku.sql`

**3. Sesuaikan konfigurasi database**

Buka `config/database.php`:
```php
$host   = "localhost";
$dbname = "catatanku";
$user   = "root";
$pass   = "";        // kosong untuk XAMPP default
```

**4. Jalankan aplikasi**
```
http://localhost/catatanku
```

---

## Akun Demo

Sudah tersedia akun demo untuk mencoba aplikasi:

| | |
|---|---|
| **Email** | user10@gmail.com |
| **Password** | password |

---

## Penggunaan

1. Buka `http://localhost/catatanku`
2. Login dengan akun demo di atas atau klik **Sign up** untuk buat akun baru
3. Klik tanggal di kalender untuk melihat catatan
4. Klik **+ Tambah Catatan** untuk menambah catatan baru
5. Gunakan tombol ✏️ untuk edit dan 🗑️ untuk hapus
6. Klik ○ untuk menandai catatan sebagai selesai
