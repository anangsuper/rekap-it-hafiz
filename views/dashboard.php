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

    // Tambahan: Aktivitas Terbaru
    $stmtLogs = $conn->query("SELECT al.*, u.nama as user_nama FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 5");
    $recentLogs = $stmtLogs->fetchAll();

    // Tambahan: Distribusi Aset per Cabang
    $stmtBranch = $conn->query("SELECT c.nama_cabang, COUNT(a.id) as total FROM cabang c LEFT JOIN assets a ON c.id = a.id_cabang GROUP BY c.id");
    $branchDistribution = $stmtBranch->fetchAll();

} catch (PDOException $e) {
    $totalAssets = $totalMaintenance = $totalRepairs = $totalCost = 0;
    $recentLogs = [];
    $branchDistribution = [];
}
?>

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --shadow-3d: 0 10px 30px -10px rgba(0,0,0,0.2), 0 5px 15px -5px rgba(0,0,0,0.1);
        --shadow-hover: 0 20px 40px -10px rgba(0,0,0,0.3);
    }
    .lux-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: var(--shadow-3d);
        position: relative;
        overflow: hidden;
        z-index: 1; /* Explicitly low z-index */
    }
    .lux-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: var(--shadow-hover);
    }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .transition-hover { transition: all 0.3s ease; border: 1px solid transparent !important; }
    .transition-hover:hover { background-color: #fff !important; border-color: var(--primary-light) !important; transform: translateY(-5px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05); }
</style>

<div class="row g-4 mb-5 animate-fade-in">
    <!-- Stat Card 1 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
            <div class="card-body p-4 position-relative text-white">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(20%, -20%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-80">TOTAL ASSETS</div>
                <h2 class="fw-800 mb-0"><?= $totalAssets ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-90 text-dark rounded-pill small">
                        <i class="bi bi-arrow-up-right me-1"></i> Active devices
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);">
            <div class="card-body p-4 position-relative text-white">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(20%, -20%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-80">MAINTENANCE</div>
                <h2 class="fw-800 mb-0"><?= $totalMaintenance ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-90 text-dark rounded-pill small">
                        <i class="bi bi-calendar-event me-1"></i> This month
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #f59e0b 0%, #ec4899 100%);">
            <div class="card-body p-4 position-relative text-white">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(20%, -20%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-80">ACTIVE REPAIRS</div>
                <h2 class="fw-800 mb-0"><?= $totalRepairs ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-90 text-dark rounded-pill small">
                        <i class="bi bi-clock-history me-1"></i> In progress
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);">
            <div class="card-body p-4 position-relative text-white">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(20%, -20%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-80">REPAIR COSTS</div>
                <h2 class="fw-800 mb-0 text-nowrap" style="font-size: 1.5rem;">Rp <?= number_format($totalCost, 0, ',', '.') ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-90 text-dark rounded-pill small">
                        <i class="bi bi-graph-up me-1"></i> Current period
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 animate-fade-in" style="animation-delay: 0.1s;">
    <!-- Welcome Card -->
    <div class="col-md-8">
        <div class="card p-4 border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3 text-primary">
                        <i class="bi bi-stars fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-800 m-0 text-dark">Selamat Datang, <?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Pengguna' ?>!</h5>
                        <p class="text-muted small m-0">Kelola infrastruktur IT Anda dengan presisi dan kemudahan.</p>
                    </div>
                </div>
                <a href="index.php?page=logs" class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm">
                    <i class="bi bi-clock-history me-1 text-primary"></i> Log Aktivitas
                </a>
            </div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-white h-100 transition-hover">
                        <i class="bi bi-plus-circle-fill text-primary fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700 text-dark">Input Inventaris</h6>
                        <p class="small text-muted mb-3">Daftarkan aset baru.</p>
                        <a href="index.php?page=inventaris" class="btn btn-primary btn-sm w-100 text-white">Tambah Aset</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-white h-100 transition-hover">
                        <i class="bi bi-tools text-success fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700 text-dark">Maintenance</h6>
                        <p class="small text-muted mb-3">Catat hasil pengecekan.</p>
                        <a href="index.php?page=maintenance" class="btn btn-success btn-sm w-100 text-white" style="border-radius: 12px;">Catat Cek</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-white h-100 transition-hover">
                        <i class="bi bi-wrench-adjustable text-warning fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700 text-dark">Tiket Perbaikan</h6>
                        <p class="small text-muted mb-3">Pantau status perbaikan.</p>
                        <a href="index.php?page=perbaikan" class="btn btn-warning btn-sm w-100 fw-bold" style="border-radius: 12px;">Lihat Tiket</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm mb-4 h-100">
            <h6 class="fw-800 mb-3 text-dark">Aktivitas Terkini</h6>
            <div class="list-group list-group-flush">
                <?php foreach ($recentLogs as $log): ?>
                    <div class="list-group-item px-0 border-0">
                        <div class="small fw-bold text-dark"><?= $log['action'] ?></div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            <?= $log['user_nama'] ?> - <?= date('d M, H:i', strtotime($log['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Distribusi Aset per Cabang -->
<div class="row g-4 mb-5 animate-fade-in">
    <div class="col-md-12">
        <div class="card p-4 border-0 shadow-sm">
            <h6 class="fw-800 mb-4 text-dark">Distribusi Aset per Cabang</h6>
            <?php foreach ($branchDistribution as $branch): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-muted"><?= $branch['nama_cabang'] ?></span>
                        <span class="small fw-bold text-dark"><?= $branch['total'] ?> Aset</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= ($totalAssets > 0) ? ($branch['total'] / $totalAssets * 100) : 0 ?>%" aria-valuenow="<?= $branch['total'] ?>" aria-valuemin="0" aria-valuemax="<?= $totalAssets ?>"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
