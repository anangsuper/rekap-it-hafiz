# 🖥️ Rekap IT - Professional Maintenance & Asset Management System

Aplikasi web manajemen aset dan maintenance IT profesional berbasis **PHP Native (PDO)**. Sistem ini dirancang untuk memantau siklus hidup perangkat IT, mulai dari inventarisasi, mutasi, maintenance berkala, hingga perbaikan kerusakan di berbagai cabang perusahaan secara terpusat.

---

## 🚀 Fitur Utama yang Telah Diimplementasikan

### 📊 Dashboard Monitoring
* Statistik real-time total aset, kondisi perangkat (Baik/Rusak), dan biaya perbaikan.
* Ringkasan aktivitas operasional IT dalam satu layar.

### 🏢 Manajemen Organisasi (Master Data)
* **Manajemen Cabang:** Pengelolaan data lokasi cabang perusahaan.
* **Manajemen Divisi:** Pengelolaan struktur departemen.
* **Manajemen Karyawan:** Database pemegang aset dengan validasi NIP unik (opsional).

### 🖥️ Inventaris Aset Terperinci
* Pencatatan spesifikasi teknis lengkap (SN, Merk, Model, Kategori).
* Relasi otomatis antara aset dengan Cabang, Divisi, dan Pemegang (Karyawan).
* **Smart Filter:** Filter data aset berdasarkan cabang untuk mempermudah pencarian.
* **Dynamic Input:** Form tambah aset cerdas yang menyaring daftar karyawan berdasarkan cabang yang dipilih.

### 🔧 Modul Maintenance (Rutin & Massal)
* **Maintenance Individu:** Pencatatan temuan, tindakan, dan rekomendasi teknisi untuk satu perangkat.
* **Maintenance Massal (Bulk):** Fitur unggulan untuk memproses maintenance banyak komputer sekaligus dalam satu cabang menggunakan sistem *Checklist*. Sangat efisien untuk kegiatan rutin bulanan.

### 🚨 Manajemen Perbaikan (Repair Ticketing)
* Sistem tiket perbaikan untuk memantau perangkat yang sedang rusak.
* **Update Aksi:** Teknisi dapat menginput tindakan solusi, biaya perbaikan, dan mengubah status (Proses/Selesai/Batal).
* Histori perbaikan yang lengkap untuk setiap aset.

### 📄 Modul Laporan & Export
* Laporan operasional IT yang dapat difilter berdasarkan **Rentang Tanggal** dan **Cabang**.
* **Export Excel:** Mengunduh data Aset, Maintenance, dan Perbaikan dalam format tabel Excel.
* **Cetak PDF:** Fitur cetak yang dioptimalkan untuk menghasilkan dokumen PDF yang rapi langsung dari browser.

---

## 🛠️ Teknologi & Library
* **Backend:** PHP 8.2+ (PDO MySQL)
* **Frontend:** Bootstrap 5.3, FontAwesome 6
* **Database:** MySQL / MariaDB
* **UI/UX:** Responsive Dashboard, Dynamic Select (JavaScript), CSS Print Media.

---

## 📂 Struktur Folder
```text
rekap-it/
├── assets/          # CSS, JS, dan Gambar
├── config/          # Konfigurasi Database (PDO)
├── database/        # Skema SQL (rekap_it_full.sql)
├── export/          # Script ekspor data (Excel)
├── models/          # Logika Database (CRUD Aset, Karyawan, dll)
├── views/           # Tampilan Antarmuka (Dashboard, Inventaris, dll)
├── index.php        # Router Utama
└── login.php        # Sistem Autentikasi
```

---

## 📋 Panduan Instalasi Lokal

### 1. Persiapan
* Pastikan Anda menggunakan server lokal seperti **XAMPP** atau **Laragon** dengan PHP versi 8.2 ke atas.

### 2. Database
* Buat database baru dengan nama `rekap_it`.
* Import file database yang tersedia di: `database/rekap_it_full.sql`.

### 3. Konfigurasi
* Sesuaikan pengaturan database Anda di file `config/database.php`:
```php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'rekap_it';
```

### 4. Akses Aplikasi
* Buka browser dan akses: `http://localhost/rekap-it-hafiz` (sesuaikan dengan nama folder Anda).

---

## 🔐 Akun Akses Awal
* **Username:** `admin`
* **Password:** `password`

---

## 🎯 Tujuan Pengembangan
Sistem ini dibuat untuk efisiensi tim IT dalam menjaga aset perusahaan agar tetap dalam kondisi prima (high availability) dan mendokumentasikan setiap biaya operasional secara akurat.

**Dikembangkan oleh Tim IT - Rekap IT System.**
