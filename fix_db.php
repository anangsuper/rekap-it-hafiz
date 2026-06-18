<?php
require_once 'config/database.php';

echo "<h2>Database Fix Tool</h2>";

$sql = "CREATE TABLE IF NOT EXISTS asset_mutations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_id INT NOT NULL,
    user_id INT NOT NULL,
    id_cabang_lama INT,
    id_divisi_lama INT,
    id_karyawan_lama INT,
    id_cabang_baru INT,
    id_divisi_baru INT,
    id_karyawan_baru INT,
    tanggal_mutasi DATE NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "<div style='color: green; font-weight: bold;'>[SUCCESS] Tabel 'asset_mutations' berhasil dibuat atau sudah ada.</div>";
    echo "<p>Silakan kembali ke <a href='index.php?page=mutasi'>Halaman Mutasi</a>.</p>";
    echo "<p><strong>PENTING:</strong> Hapus file <code>fix_db.php</code> dari server setelah ini untuk keamanan.</p>";
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>[ERROR] Gagal membuat tabel: " . $e->getMessage() . "</div>";
}
?>
