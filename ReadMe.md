# 🖥️ Rekap IT - Maintenance & Asset Management System

Aplikasi web profesional berbasis **PHP Native (PDO)** untuk membantu tim IT mengelola inventaris aset, menjadwalkan maintenance berkala, memantau perbaikan perangkat, mengelola sparepart, serta menghasilkan laporan operasional secara terpusat.

Sistem dirancang untuk mendukung operasional perusahaan yang memiliki banyak cabang, divisi, dan aset IT dengan kontrol akses berbasis role.

---

# 🚀 Fitur Utama

### 📊 Dashboard Modern

* Statistik total aset
* Total aset aktif
* Maintenance bulan berjalan
* Perbaikan aktif
* Garansi akan habis
* Grafik maintenance dan kerusakan
* Monitoring aktivitas teknisi

### 🖥️ Manajemen Inventaris Aset

* Data aset lengkap
* Kode aset otomatis
* Nomor seri (Serial Number)
* Kode inventaris
* Spesifikasi perangkat
* Foto aset
* Status aset
* Lokasi penempatan
* Vendor dan garansi

### 🏢 Manajemen Organisasi

* Data cabang
* Data divisi
* Data karyawan
* Pemegang aset
* Jabatan pengguna aset

### 🔄 Mutasi Aset

* Perpindahan aset antar pengguna
* Perpindahan aset antar divisi
* Riwayat mutasi aset
* Tracking pemegang aset

### 📅 Jadwal Maintenance Massal

* Maintenance berkala
* Maintenance bulanan
* Maintenance triwulan
* Maintenance semesteran
* Maintenance tahunan
* Penugasan teknisi

### 🔧 Maintenance Rutin

* Pencatatan hasil maintenance
* Temuan pemeriksaan
* Tindakan maintenance
* Rekomendasi teknisi
* Upload foto sebelum dan sesudah

### 🚨 Perbaikan Individual & Massal

* Tiket perbaikan
* Monitoring status
* Estimasi biaya
* Riwayat perbaikan
* Tracking kerusakan perangkat

### 📦 Manajemen Sparepart

* Data sparepart
* Stok sparepart
* Penggunaan sparepart
* Histori penggunaan

### 🔍 Audit Aset

* Pemeriksaan inventaris
* Verifikasi lokasi aset
* Verifikasi pemegang aset
* Verifikasi kondisi perangkat

### 📄 Laporan

* Laporan aset
* Laporan maintenance
* Laporan perbaikan
* Laporan sparepart
* Laporan audit
* Export PDF
* Export Excel

### 🔐 Role Management

* Super Admin
* Admin IT
* Teknisi
* Pimpinan Cabang
* Viewer / Auditor

### 📜 Activity Log (Audit Trail)

* Login sistem
* Tambah data
* Edit data
* Hapus data
* Aktivitas pengguna

### 📱 QR Code Aset

* Generate QR Code
* Scan QR Code
* Akses detail aset secara cepat

---

# 👥 Role & Hak Akses

Sistem menggunakan Role Based Access Control (RBAC).

## Super Admin

Hak akses penuh:

* Kelola pengguna
* Kelola role
* Kelola aset
* Kelola maintenance
* Kelola perbaikan
* Kelola sparepart
* Kelola laporan
* Kelola audit

## Admin IT

* Kelola aset
* Kelola maintenance
* Kelola perbaikan
* Kelola sparepart
* Melihat seluruh laporan

## Teknisi

* Melihat jadwal maintenance
* Input maintenance
* Input perbaikan
* Upload foto dokumentasi

## Pimpinan Cabang

* Melihat aset cabang
* Melihat laporan cabang
* Monitoring maintenance cabang

## Viewer / Auditor

* Hanya melihat data
* Hanya melihat laporan
* Tidak dapat mengubah data

> Role tidak dipilih saat login. Sistem otomatis menentukan hak akses berdasarkan data pengguna yang tersimpan di database.

---

# 📊 Modul Utama

1. Dashboard
2. Data Cabang
3. Data Divisi
4. Data Karyawan
5. Data Kategori Aset
6. Data Aset
7. Mutasi Aset
8. Jadwal Maintenance
9. Maintenance
10. Perbaikan Individual
11. Perbaikan Massal
12. Sparepart
13. Audit Aset
14. Laporan
15. Pengguna
16. Hak Akses
17. Activity Log

---

# 🗄️ Struktur Database

Database menggunakan MySQL dengan nama:

```sql
rekap_it
```

## Master Data

* pengguna
* cabang
* divisi
* karyawan
* kategori_aset
* sparepart

## Manajemen Aset

* aset
* mutasi_aset
* audit_aset

## Maintenance

* jadwal_maintenance
* detail_jadwal_maintenance
* maintenance
* foto_maintenance

## Perbaikan

* perbaikan_massal
* detail_perbaikan_massal
* penggunaan_sparepart

## Sistem

* activity_logs
* notifications

---

# 🖥️ Data Aset yang Dikelola

Setiap aset memiliki informasi:

* Kode Aset
* Nomor Seri (Serial Number)
* Kode Inventaris
* Nama Aset
* Kategori
* Merk
* Model
* Processor
* RAM
* Storage
* Sistem Operasi
* IP Address
* MAC Address
* Vendor
* Tanggal Pembelian
* Masa Garansi
* Cabang
* Divisi
* Pemegang Aset
* Lokasi Penempatan
* Status Aset
* Foto Aset
* Riwayat Maintenance
* Riwayat Perbaikan

---

# 🛠️ Teknologi yang Digunakan

### Backend

* PHP 8.2+
* PDO MySQL

### Database

* MySQL 8+
* MariaDB 10+

### Frontend

* HTML5
* CSS3
* JavaScript ES6

### Library

* Bootstrap 5.3
* FontAwesome 6
* SweetAlert2
* DataTables
* Chart.js
* UI Avatars

### Export

* DomPDF
* PhpSpreadsheet

### Deployment

* Railway
* VPS Linux
* Shared Hosting

---

# 📂 Struktur Folder

```text
rekap-it/
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── qrcode/
│
├── config/
│   ├── database.php
│   └── auth.php
│
├── controllers/
│
├── models/
│
├── views/
│   ├── dashboard.php
│   ├── aset.php
│   ├── maintenance.php
│   ├── perbaikan.php
│   ├── audit.php
│   ├── laporan.php
│   ├── pengguna.php
│   ├── header.php
│   └── footer.php
│
├── uploads/
│   ├── aset/
│   ├── maintenance/
│   └── perbaikan/
│
├── database/
│   └── rekap_it.sql
│
├── login.php
├── logout.php
├── index.php
├── composer.json
├── railway.toml
└── README.md
```

---

# 📋 Instalasi Lokal

## 1. Clone Repository

```bash
git clone https://github.com/anangsuper/rekap-it-hafiz.git
```

## 2. Buat Database

```sql
CREATE DATABASE rekap_it;
```

## 3. Import Database

Import file:

```text
database/rekap_it.sql
```

ke database MySQL.

## 4. Konfigurasi Database

Buka file:

```php
config/database.php
```

Sesuaikan:

```php
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=rekap_it
DB_USERNAME=root
DB_PASSWORD=
```

## 5. Jalankan Aplikasi

```text
http://localhost/rekap-it
```

---

# ☁️ Deployment Railway

Aplikasi telah mendukung deployment Railway.

## Environment Variables

```env
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

atau

```env
MYSQL_URL=
```

## Build System

Menggunakan:

```text
Nixpacks
```

dan PHP 8.2+

---

# 🔐 Akun Awal

## Super Admin

```text
Username : admin
Password : password
```

> Segera ubah password setelah instalasi pertama.

---

# 🎯 Tujuan Sistem

Sistem ini dibuat untuk membantu tim IT dalam:

* Mengelola inventaris aset secara terpusat
* Mengetahui lokasi dan pemegang aset
* Menjadwalkan maintenance berkala
* Memantau kerusakan perangkat
* Mengontrol penggunaan sparepart
* Mendokumentasikan aktivitas teknisi
* Mempermudah audit aset
* Menyediakan laporan operasional yang akurat

---

# 📄 Lisensi

Aplikasi ini dikembangkan untuk kebutuhan operasional manajemen aset dan maintenance perangkat IT perusahaan.

---

**Dikembangkan oleh Tim IT untuk efisiensi pengelolaan aset, maintenance, dan operasional infrastruktur teknologi informasi.**
