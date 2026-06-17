<?php
// Query untuk mengambil statistik
try {
    // Total Aset
    $stmtAssets = $conn->query("SELECT COUNT(*) as total FROM assets");
    $totalAssets = $stmtAssets->fetch()['total'];

    // Maintenance (Bulan ini)
    $stmtMaintenance = $conn->query("SELECT COUNT(*) as total FROM maintenance WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())");
    $totalMaintenance = $stmtMaintenance->fetch()['total'];

    // Perbaikan Aktif
    $stmtRepairs = $conn->query("SELECT COUNT(*) as total FROM repairs WHERE status = 'Proses'");
    $totalRepairs = $stmtRepairs->fetch()['total'];

    // Total Biaya Perbaikan (Bulan ini)
    $stmtCost = $conn->query("SELECT SUM(biaya) as total FROM repairs WHERE status = 'Selesai' AND MONTH(created_at) = MONTH(CURRENT_DATE())");
    $totalCost = $stmtCost->fetch()['total'] ?? 0;
} catch (PDOException $e) {
    // Jika tabel belum ada, set nilai default 0
    $totalAssets = 0;
    $totalMaintenance = 0;
    $totalRepairs = 0;
    $totalCost = 0;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Dashboard Ringkasan</h3>
    <span class="text-muted"><?php echo date('d F Y'); ?></span>
</div>

<div class="row">

    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Total Aset</h5>
                    <h3><?= $totalAssets ?></h3>
                </div>
                <i class="fas fa-boxes fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Maintenance</h5>
                    <h3><?= $totalMaintenance ?></h3>
                </div>
                <i class="fas fa-tools fa-2x opacity-50"></i>
            </div>
            <small>Bulan ini</small>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Perbaikan</h5>
                    <h3><?= $totalRepairs ?></h3>
                </div>
                <i class="fas fa-wrench fa-2x opacity-50"></i>
            </div>
            <small>Status: Proses</small>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Biaya</h5>
                    <h3>Rp <?= number_format($totalCost, 0, ',', '.') ?></h3>
                </div>
                <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
            </div>
            <small>Selesai (Bulan ini)</small>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <h5>Selamat Datang, <?= $_SESSION['nama'] ?>!</h5>
            <p class="text-muted">Gunakan menu di sebelah kiri untuk mengelola aset IT, mencatat maintenance rutin, atau memantau perbaikan perangkat.</p>
        </div>
    </div>
</div>
