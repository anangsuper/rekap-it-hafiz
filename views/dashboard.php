<?php
// Query untuk mengambil statistik
try {
    $stmtAssets = $conn->query("SELECT COUNT(*) as total FROM assets");
    $totalAssets = $stmtAssets->fetch()['total'];

    $stmtMaintenance = $conn->query("SELECT COUNT(*) as total FROM maintenance WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())");
    $totalMaintenance = $stmtMaintenance->fetch()['total'];

    $stmtRepairs = $conn->query("SELECT COUNT(*) as total FROM repairs WHERE status = 'Proses'");
    $totalRepairs = $stmtRepairs->fetch()['total'];

    $stmtCost = $conn->query("SELECT SUM(biaya) as total FROM repairs WHERE status = 'Selesai' AND MONTH(created_at) = MONTH(CURRENT_DATE())");
    $totalCost = $stmtCost->fetch()['total'] ?? 0;
} catch (PDOException $e) {
    $totalAssets = $totalMaintenance = $totalRepairs = $totalCost = 0;
}
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card p-3 border-start border-primary border-5">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary">
                    <i class="fas fa-boxes fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">TOTAL ASET</div>
                    <h3 class="m-0 fw-bold"><?= $totalAssets ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-success border-5">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3 text-success">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">MAINTENANCE</div>
                    <h3 class="m-0 fw-bold"><?= $totalMaintenance ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-warning border-5">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3 text-warning">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">DALAM PERBAIKAN</div>
                    <h3 class="m-0 fw-bold"><?= $totalRepairs ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-danger border-5">
            <div class="d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3 text-danger">
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">BIAYA BULAN INI</div>
                    <h4 class="m-0 fw-bold text-nowrap">Rp <?= number_format($totalCost, 0, ',', '.') ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card h-100 p-4">
            <h5 class="fw-bold mb-3">Selamat Datang di Portal Maintenance</h5>
            <p class="text-muted">Halo <strong><?= $_SESSION['nama'] ?></strong>, sistem siap membantu Anda mengelola infrastruktur IT hari ini. Berikut panduan singkat:</p>
            <div class="list-group list-group-flush mt-2">
                <div class="list-group-item bg-transparent d-flex align-items-center px-0">
                    <div class="bg-light p-2 rounded me-3"><i class="fas fa-plus text-primary"></i></div>
                    <div>Gunakan menu <strong>Inventaris</strong> untuk mencatat perangkat baru.</div>
                </div>
                <div class="list-group-item bg-transparent d-flex align-items-center px-0">
                    <div class="bg-light p-2 rounded me-3"><i class="fas fa-sync text-success"></i></div>
                    <div>Catat pemeriksaan rutin di menu <strong>Maintenance</strong>.</div>
                </div>
                <div class="list-group-item bg-transparent d-flex align-items-center px-0">
                    <div class="bg-light p-2 rounded me-3"><i class="fas fa-wrench text-warning"></i></div>
                    <div>Pantau status kerusakan di menu <strong>Perbaikan</strong>.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 p-4 bg-dark text-white">
            <h5 class="fw-bold mb-4">Informasi Sistem</h5>
            <div class="mb-4">
                <div class="small opacity-50 mb-1">DIBUAT PADA</div>
                <div>Juni 2026</div>
            </div>
            <div class="mb-4">
                <div class="small opacity-50 mb-1">VERSI APLIKASI</div>
                <div>v1.0.0 Stable</div>
            </div>
            <div class="mt-auto">
                <button class="btn btn-primary w-100">Cek Log Aktivitas</button>
            </div>
        </div>
    </div>
</div>
