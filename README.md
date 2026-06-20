# 🖥️ Rekap IT - Sistem Manajemen Aset & Maintenance Profesional

**Rekap IT** adalah solusi manajemen infrastruktur IT berbasis web yang dirancang untuk membantu tim IT dalam mengelola, memantau, dan merawat aset perusahaan secara efisien. Dikembangkan dengan fokus pada kemudahan penggunaan, keamanan data, dan estetika antarmuka.

---

## ✨ Fitur Unggulan

### 🎨 Premium & Aesthetic Interface
*   **Modern Dashboard:** Visualisasi data aset, statistik kondisi perangkat, dan monitoring biaya perbaikan secara real-time.
*   **Responsive Design:** Antarmuka yang adaptif untuk akses lancar melalui desktop, tablet, maupun smartphone.
*   **Interactive UI:** Menggunakan *Plus Jakarta Sans* untuk keterbacaan tinggi dan efek *Glassmorphism* yang elegan.

### ⚙️ Manajemen Data Master (Full CRUD)
*   **Manajemen Terpusat:** Kontrol penuh untuk data **Cabang**, **Divisi**, dan **Karyawan**.
*   **Smart Assignee:** Sistem otomatis memfilter daftar karyawan berdasarkan cabang yang dipilih.

### 📦 Inventarisasi Aset Cerdas
*   **Pelacakan Detail:** Pencatatan Kode Aset, Serial Number, Merk, Model, hingga histori pemegang.
*   **Kondisi Real-time:** Status visual untuk kondisi aset (Baik, Rusak Ringan, Rusak Berat).

### 🛡️ Audit & Mutasi
*   **Audit Fisik:** Modul khusus untuk verifikasi lapangan periodik.
*   **Histori Mutasi:** Pencatatan riwayat perpindahan aset antar lokasi atau antar karyawan.

### 🔧 Operasional & Pemeliharaan IT
*   **Maintenance Massal (Bulk):** Efisiensi tinggi bagi teknisi.
*   **Filter Cerdas (Baru!):** Sistem otomatis menyembunyikan aset dari daftar pilihan maintenance jika aset tersebut sudah dilakukan maintenance pada bulan berjalan. Aset hanya akan muncul kembali jika diperlukan perbaikan (Repair).
*   **Tiket Perbaikan (Repair):** Sistem pelacakan kerusakan aset dari mulai laporan masuk hingga penyelesaian beserta rincian biaya.
*   **Stok Sparepart:** Manajemen inventaris suku cadang.

### 📊 Pelaporan & Keamanan
*   **Export Excel:** Hasilkan laporan operasional siap cetak.
*   **Log Aktivitas:** Rekam jejak setiap aksi penting.
*   **Keamanan Database:** Proteksi penuh terhadap serangan SQL Injection menggunakan *PDO Prepared Statements*.

---

## 🛠️ Spesifikasi Teknologi
*   **Backend:** PHP 8.2+ (OOP & PDO)
*   **Database:** MySQL / MariaDB
*   **Frontend:** Bootstrap 5.3, Vanilla JS, CSS3 Custom Properties
*   **Icons:** FontAwesome 6 & Bootstrap Icons

---

## 📂 Struktur Direktori
```text
rekap-it/
├── config/          # Konfigurasi Database & Koneksi
├── controllers/     # Business Logic & Request Handlers
├── database/        # Skema Database SQL
├── models/          # Data Models (Asset, Karyawan, Maintenance, dsb)
├── views/           # UI Components & Page Templates
├── index.php        # Router Utama
└── login.php        # Autentikasi Keamanan
```

---

**Rekap IT - Transformasi Digital untuk Manajemen Aset IT yang Lebih Terukur dan Profesional.**
