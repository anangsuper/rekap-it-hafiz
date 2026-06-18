# 🖥️ Rekap IT - Sistem Manajemen Aset & Maintenance Profesional

Aplikasi **Rekap IT** adalah solusi manajemen infrastruktur IT berbasis web yang dirancang untuk membantu tim IT dalam mengelola, memantau, dan merawat aset perusahaan secara efisien dan terorganisir. Aplikasi ini telah ditingkatkan dengan tampilan **Premium UI/UX** yang estetik dan fitur fungsional yang lengkap.

---

## ✨ Fitur Unggulan

### 🎨 Premium & Aesthetic Interface
*   **Modern Design:** Menggunakan desain berbasis *glassmorphism* dan kartu bergradien yang memberikan kesan bersih, mewah, dan profesional.
*   **Responsive Layout:** Tampilan yang optimal baik di layar desktop maupun perangkat mobile.
*   **Plus Jakarta Sans Typography:** Tipografi modern yang nyaman di mata untuk penggunaan jangka panjang.

### 🏢 Manajemen Organisasi (Master Data)
*   **Struktur Multi-Cabang:** Kelola data aset di berbagai lokasi cabang dan divisi secara terpusat.
*   **Database Karyawan Pintar:** Pencatatan pemegang aset dengan sistem validasi NIP unik dan filter otomatis berdasarkan cabang.

### 🖥️ Inventaris Aset Terpadu
*   **Spesifikasi Teknis Lengkap:** Pantau Serial Number, Merk, Model, hingga kondisi fisik perangkat.
*   **Smart Filtering:** Filter cepat berdasarkan cabang untuk memantau distribusi aset di seluruh perusahaan.
*   **Dynamic Assignee:** Sistem otomatis menyaring nama karyawan sesuai cabang yang dipilih saat input data aset baru.

### 🔧 Operasional IT (Maintenance & Repair)
*   **Maintenance Massal (Bulk):** Fitur efisiensi tinggi yang memungkinkan teknisi melakukan maintenance puluhan komputer sekaligus dalam satu klik menggunakan sistem *Checklist*.
*   **Sistem Tiket Perbaikan:** Pantau setiap kerusakan perangkat mulai dari pengajuan, proses perbaikan, hingga estimasi biaya yang dikeluarkan.
*   **Log Riwayat:** Setiap tindakan maintenance dan perbaikan tercatat rapi untuk kebutuhan audit di masa depan.

### 📊 Laporan & Analitik
*   **Dashboard Statistik:** Visualisasi jumlah aset, status kondisi perangkat, dan akumulasi biaya operasional bulan berjalan.
*   **Export Data:** Unduh laporan lengkap dalam format **Excel (.xls)** untuk kebutuhan administratif.
*   **Smart Print (PDF):** Fitur cetak laporan yang dioptimalkan untuk menghasilkan dokumen fisik yang rapi tanpa elemen navigasi website.

---

## 🛠️ Spesifikasi Teknologi
*   **Core Engine:** PHP 8.2+ dengan **PDO MySQL** (Aman dari SQL Injection).
*   **Database:** MariaDB / MySQL.
*   **Frontend Framework:** Bootstrap 5.3 & Vanilla JavaScript.
*   **Icons:** FontAwesome 6 & Bootstrap Icons.
*   **Typography:** Google Fonts (Plus Jakarta Sans).

---

## 📂 Struktur Aplikasi
```text
rekap-it/
├── assets/          # Resource desain, CSS, JS, dan Gambar
├── config/          # Pengaturan database dan konfigurasi sistem
├── database/        # Skema SQL database (rekap_it_full.sql)
├── export/          # Modul pemrosesan ekspor data Excel
├── models/          # Logika bisnis dan interaksi database (CRUD)
├── views/           # Antarmuka pengguna (Dashboard, Inventory, dsb)
├── index.php        # Router dan titik masuk aplikasi
└── login.php        # Sistem gerbang keamanan pengguna
```

---

## 📋 Panduan Penggunaan
1.  **Dashboard:** Pantau ringkasan aset dan biaya bulan ini.
2.  **Manajemen:** Tambahkan Cabang, Divisi, dan Karyawan terlebih dahulu.
3.  **Aset:** Daftarkan perangkat IT dan tentukan pemegangnya.
4.  **Maintenance:** Lakukan pemeriksaan rutin (individu atau massal).
5.  **Perbaikan:** Buat tiket jika ada perangkat yang rusak dan update statusnya hingga selesai.
6.  **Laporan:** Gunakan filter tanggal/cabang untuk melihat kinerja IT Anda.

---

## 🔐 Keamanan Sistem
*   **RBAC (Role-Based Access Control):** Mendukung berbagai level akses (Super Admin, Teknisi, dll).
*   **Password Hashing:** Keamanan akun menggunakan enkripsi standar industri.
*   **Database Security:** Menggunakan PDO Prepared Statements untuk mencegah serangan SQL Injection.

---

**Rekap IT System - Solusi Modern untuk Efisiensi Infrastruktur IT Perusahaan.**
