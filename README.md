# Rekap IT

Rekap IT adalah aplikasi web untuk membantu tim IT mengelola aset perusahaan secara terpusat. Aplikasi ini mencatat data inventaris, lokasi aset, pengguna aset, riwayat maintenance, tiket perbaikan, mutasi, audit fisik, sparepart, laporan, dan aktivitas pengguna.

Tujuan utama aplikasi ini adalah membuat pengelolaan perangkat IT lebih rapi, mudah dilacak, dan siap diaudit.

## Ringkasan Fitur

### Dashboard

Dashboard menampilkan ringkasan kondisi operasional IT, seperti total aset, maintenance bulan berjalan, tiket perbaikan yang masih proses, biaya perbaikan, aset bermasalah, aktivitas terbaru, dan distribusi aset per cabang.

### Manajemen Inventaris

Modul inventaris digunakan untuk mencatat dan memperbarui data aset IT, termasuk kode aset, nama aset, serial number, kategori, merk, model, garansi, cabang, divisi, karyawan pemegang aset, spesifikasi, kondisi, dan foto aset.

Kondisi aset dapat dipantau dengan status seperti:

- Baik
- Rusak Ringan
- Rusak Berat

### Master Data

Aplikasi menyediakan pengelolaan data pendukung agar inventaris lebih terstruktur:

- Cabang
- Divisi
- Kategori aset
- Karyawan
- Pengguna sistem

Data karyawan juga mendukung NIP, jabatan, cabang, dan divisi sehingga kepemilikan aset dapat ditelusuri dengan jelas.

### Maintenance

Modul maintenance digunakan untuk mencatat pemeriksaan rutin aset. Setiap catatan maintenance dapat memuat tanggal, teknisi, temuan, tindakan, rekomendasi, dan status hasil pemeriksaan.

Aplikasi juga memiliki fitur maintenance massal untuk mempercepat pencatatan pengecekan banyak aset sekaligus. Aset yang sudah diperiksa pada bulan berjalan dapat difilter agar tidak tercatat ganda.

### Perbaikan

Modul perbaikan digunakan untuk membuat dan memantau tiket kerusakan aset. Setiap tiket dapat mencatat keluhan, tindakan perbaikan, biaya, status, tanggal mulai, dan tanggal selesai.

Status tiket perbaikan meliputi:

- Proses
- Selesai
- Batal

### Mutasi Aset

Modul mutasi mencatat perpindahan aset dari cabang, divisi, atau karyawan lama ke lokasi atau pemegang baru. Riwayat mutasi membantu tim IT mengetahui perjalanan aset dari waktu ke waktu.

### Audit Fisik

Modul audit membantu mencocokkan data di sistem dengan kondisi fisik di lapangan. Audit mencatat kondisi yang dilaporkan, kondisi fisik, lokasi fisik, catatan auditor, dan status verifikasi.

### Sparepart

Modul sparepart digunakan untuk mengelola stok suku cadang, termasuk kode sparepart, nama sparepart, stok, dan satuan.

### Laporan dan Ekspor

Aplikasi menyediakan halaman laporan dan fitur ekspor data, termasuk ekspor ke Excel dan PDF untuk kebutuhan dokumentasi, pelaporan, dan arsip.

### Log Aktivitas

Aktivitas pengguna dicatat di log sistem untuk membantu transparansi, pelacakan perubahan, dan audit penggunaan aplikasi.

## Hak Akses

Aplikasi mendukung autentikasi pengguna dengan role:

- Admin
- Teknisi

Beberapa halaman dibatasi untuk admin, seperti audit, cabang, divisi, inventaris, karyawan, kategori, laporan, log aktivitas, mutasi, dan pengguna.

## Alur Penggunaan Singkat

1. Login ke aplikasi.
2. Lengkapi master data seperti cabang, divisi, kategori aset, dan karyawan.
3. Input data aset pada menu inventaris.
4. Catat maintenance rutin pada menu maintenance.
5. Buat tiket perbaikan jika aset mengalami kerusakan.
6. Catat mutasi jika aset berpindah lokasi atau pemegang.
7. Lakukan audit fisik untuk memastikan data sistem sesuai dengan kondisi lapangan.
8. Gunakan laporan dan ekspor data untuk kebutuhan rekap dan dokumentasi.

## Teknologi

| Komponen | Teknologi |
| --- | --- |
| Bahasa | PHP 8.2+ |
| Database | MySQL / MariaDB |
| Koneksi Database | PDO |
| Frontend | Bootstrap 5, CSS, JavaScript |
| Ekspor PDF | Dompdf |
| Deployment | Mendukung konfigurasi lokal dan Railway |

## Keamanan

- Menggunakan autentikasi session.
- Mendukung role admin dan teknisi.
- Menggunakan PDO prepared statements untuk mengurangi risiko SQL injection.
- Konfigurasi database dapat menggunakan environment variable untuk deployment.

## Instalasi

### Prasyarat

- PHP 8.2 atau lebih baru
- MySQL atau MariaDB
- Composer
- Web server seperti Apache, Nginx, atau server lokal PHP

### Langkah Setup

1. Clone repository:

   ```bash
   git clone https://github.com/anangsuper/rekap-it-hafiz.git
   ```

2. Masuk ke folder proyek:

   ```bash
   cd rekap-it-hafiz
   ```

3. Install dependency PHP:

   ```bash
   composer install
   ```

4. Buat database MySQL, lalu impor skema:

   ```bash
   mysql -u root -p rekap_it < database/rekap_it.sql
   ```

5. Sesuaikan konfigurasi database di `config/database.php` atau gunakan environment variable berikut:

   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_USERNAME=root
   DB_PASSWORD=
   DB_DATABASE=rekap_it
   ```

6. Jalankan aplikasi melalui web server dengan document root mengarah ke folder proyek.

## Akun Default

Database menyediakan akun admin awal:

| Username | Password | Role |
| --- | --- | --- |
| admin | password | admin |

Segera ubah password default setelah login pertama.

## Struktur Folder

```text
config/       Konfigurasi database
controllers/  Logika pemrosesan fitur
models/       Akses data dan query database
views/        Halaman antarmuka aplikasi
helpers/      Fungsi pendukung autentikasi, pagination, dan UI
database/     Skema dan optimasi database
export/       File ekspor Excel dan PDF
api/          Endpoint data dashboard
uploads/      Penyimpanan file upload
```

## Catatan Deployment

Konfigurasi database mendukung environment lokal dan Railway. Jika dijalankan di Railway, aplikasi dapat membaca variabel seperti `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`, dan `MYSQLDATABASE`.
