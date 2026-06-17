# 🖥️ Rekap IT - Maintenance & Asset Management System

Aplikasi web profesional berbasis **PHP Native (PDO)** untuk membantu tim IT mengelola inventaris aset, mencatat aktivitas maintenance rutin, serta memantau perbaikan perangkat secara efisien.

## 🚀 Fitur Utama

*   **Dashboard Modern**: Statistik real-time total aset, maintenance bulanan, perbaikan aktif, dan biaya operasional.
*   **Manajemen Inventaris (CRUD)**: Kelola aset IT (PC, Laptop, Server) lengkap dengan spesifikasi, lokasi, dan unggah foto.
*   **Maintenance Rutin**: Pencatatan aktivitas perawatan berkala oleh teknisi.
*   **Monitoring Perbaikan**: Laporan kerusakan, estimasi biaya, dan pelacakan status perbaikan (Proses, Selesai, Batal).
*   **Activity Logs (Audit Trail)**: Mencatat setiap aktivitas sistem (tambah data, update, login) untuk keamanan data.
*   **UI/UX Premium**: Menggunakan **Bootstrap 5** dengan font **Plus Jakarta Sans** dan palet warna modern.

## 🛠️ Teknologi yang Digunakan

*   **Backend**: PHP 8.2+ (PDO MySQL)
*   **Database**: MySQL / MariaDB
*   **Frontend**: HTML5, CSS3, JavaScript (ES6)
*   **Library**: Bootstrap 5.3, FontAwesome 6, UI Avatars
*   **Deployment**: Railway (via Nixpacks)

## 📂 Struktur Folder

```text
Website hafiz/
├── assets/             # CSS, JS, dan file gambar statis
├── config/             # Konfigurasi koneksi database
├── controllers/        # Logika aplikasi (Business Logic)
├── database/           # File SQL skema database
├── models/             # Query database (Data Access)
├── uploads/            # Penyimpanan foto aset yang diunggah
├── views/              # File tampilan (UI)
│   ├── dashboard.php
│   ├── inventaris.php
│   ├── maintenance.php
│   ├── perbaikan.php
│   ├── header.php
│   └── footer.php
├── index.php           # Entry point aplikasi
├── login.php           # Halaman otentikasi
├── logout.php          # Proses keluar sistem
├── composer.json       # Konfigurasi dependency PHP
└── railway.toml        # Konfigurasi deployment Railway
```

## 📋 Persiapan Lokal (XAMPP/Laragon)

1.  **Clone Repository**:
    ```bash
    git clone https://github.com/anangsuper/rekap-it-hafiz.git
    ```
2.  **Impor Database**:
    *   Buat database baru bernama `rekap_it`.
    *   Impor file `database/rekap_it.sql` ke database tersebut.
3.  **Konfigurasi**:
    *   Buka `config/database.php`.
    *   Sesuaikan username dan password MySQL Anda (default: `root` & `""`).
4.  **Akses**:
    Buka di browser: `http://localhost/rekap-it-hafiz`

## ☁️ Deployment Railway

Aplikasi ini sudah dikonfigurasi sepenuhnya untuk Railway:
1.  Gunakan `railway.toml` yang sudah tersedia untuk otomatisasi server.
2.  Pastikan menggunakan **Environment Variables** berikut di Railway:
    *   `MYSQL_URL` atau mapping variabel `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
3.  Tingkat keamanan: Menggunakan **Nixpacks** untuk build PHP 8.2 yang stabil.

## 🔐 Akun Akses Default

*   **Username**: `admin`
*   **Password**: `password`

---

**Dikembangkan oleh Tim IT** untuk efisiensi operasional manajemen infrastruktur perusahaan.
