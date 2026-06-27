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
    .lux-card {
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.05), 0 10px 20px -10px rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .lux-card:hover {
        transform: translateY(-8px) scale(1.015);
        box-shadow: 0 25px 45px -10px rgba(99, 102, 241, 0.15), 0 15px 25px -15px rgba(0, 0, 0, 0.05);
    }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .transition-hover { 
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); 
        border: 1px solid rgba(226, 232, 240, 0.8) !important; 
        background: rgba(255, 255, 255, 0.45) !important;
    }
    .transition-hover:hover { 
        background-color: #ffffff !important; 
        border-color: rgba(99, 102, 241, 0.3) !important; 
        transform: translateY(-4px); 
        box-shadow: 0 12px 25px -5px rgba(99, 102, 241, 0.08); 
    }
    
    /* Progress Bars & Custom badges */
    .progress {
        background-color: #e2e8f0;
        height: 8px;
        border-radius: 99px;
        overflow: hidden;
    }
    .progress-bar {
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
        border-radius: 99px;
    }
    
    .list-group-item {
        background: transparent !important;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5) !important;
    }
    .list-group-item:last-child {
        border-bottom: none !important;
    }
</style>

<div class="row g-4 mb-5 animate-fade-in">
    <!-- Stat Card 1 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
            <!-- Glossy Glass Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none; z-index: 0;"></div>
            <div class="card-body p-4 position-relative text-white" style="z-index: 1;">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(15%, -15%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-70" style="letter-spacing: 0.05em;">TOTAL ASSETS</div>
                <h2 class="fw-800 mb-0"><?= $totalAssets ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30 rounded-pill small">
                        <i class="bi bi-arrow-up-right me-1"></i> Active devices
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #059669 0%, #0d9488 100%);">
            <!-- Glossy Glass Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none; z-index: 0;"></div>
            <div class="card-body p-4 position-relative text-white" style="z-index: 1;">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(15%, -15%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-70" style="letter-spacing: 0.05em;">MAINTENANCE</div>
                <h2 class="fw-800 mb-0"><?= $totalMaintenance ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30 rounded-pill small">
                        <i class="bi bi-calendar-event me-1"></i> This month
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #d97706 0%, #db2777 100%);">
            <!-- Glossy Glass Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none; z-index: 0;"></div>
            <div class="card-body p-4 position-relative text-white" style="z-index: 1;">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(15%, -15%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-70" style="letter-spacing: 0.05em;">ACTIVE REPAIRS</div>
                <h2 class="fw-800 mb-0"><?= $totalRepairs ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30 rounded-pill small">
                        <i class="bi bi-clock-history me-1"></i> In progress
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3">
        <div class="lux-card" style="background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);">
            <!-- Glossy Glass Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none; z-index: 0;"></div>
            <div class="card-body p-4 position-relative text-white" style="z-index: 1;">
                <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 5rem; transform: translate(15%, -15%); pointer-events: none; z-index: 0;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="small fw-bold mb-1 opacity-70" style="letter-spacing: 0.05em;">REPAIR COSTS</div>
                <h2 class="fw-800 mb-0 text-nowrap" style="font-size: 1.45rem;">Rp <?= number_format($totalCost, 0, ',', '.') ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-30 rounded-pill small">
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
        <div class="card p-4 border-0 mb-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3 text-primary">
                        <i class="bi bi-stars fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-800 m-0 text-dark">Selamat Datang, <?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Pengguna' ?>!</h5>
                        <p class="text-muted small m-0">Kelola infrastruktur IT Anda dengan presisi dan kemudahan.</p>
                    </div>
                </div>
                <a href="index.php?page=logs" class="btn btn-secondary btn-sm px-3 shadow-sm" style="border-radius: 20px;">
                    <i class="bi bi-clock-history me-1 text-primary"></i> Log Aktivitas
                </a>
            </div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-4 rounded-4 transition-hover">
                        <i class="bi bi-plus-circle-fill text-primary fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700 text-dark">Input Inventaris</h6>
                        <p class="small text-muted mb-3">Daftarkan aset baru.</p>
                        <a href="index.php?page=inventaris" class="btn btn-primary btn-sm w-100 text-white">Tambah Aset</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 transition-hover">
                        <i class="bi bi-tools text-success fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700 text-dark">Maintenance</h6>
                        <p class="small text-muted mb-3">Catat hasil pengecekan.</p>
                        <a href="index.php?page=maintenance" class="btn btn-sm w-100 text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 12px;">Catat Cek</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 transition-hover">
                        <i class="bi bi-wrench-adjustable text-warning fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700 text-dark">Tiket Perbaikan</h6>
                        <p class="small text-muted mb-3">Pantau status perbaikan.</p>
                        <a href="index.php?page=perbaikan" class="btn btn-sm w-100 fw-bold" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: #fff; border-radius: 12px;">Lihat Tiket</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="col-md-4">
        <div class="card p-4 border-0 mb-4 h-100">
            <h6 class="fw-800 mb-4 text-dark d-flex align-items-center">
                <i class="bi bi-clock-history me-2 text-primary"></i> Aktivitas Terkini
            </h6>
            <div class="list-group list-group-flush mb-3">
                <?php foreach ($recentLogs as $log): ?>
                    <div class="list-group-item px-0 border-0 mb-2 d-flex align-items-start">
                        <div class="bg-light p-2 rounded-circle me-3 mt-1">
                            <i class="bi bi-person-fill text-secondary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="small fw-bold text-dark text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($log['description']) ?>">
                                <?= htmlspecialchars($log['action']) ?>
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                <?= htmlspecialchars($log['user_nama'] ?: 'Sistem') ?> • 
                                <span title="<?= date('d M Y, H:i:s', strtotime($log['created_at'])) ?>">
                                    <?= date('d M, H:i', strtotime($log['created_at'])) ?>
                                </span>
                            </div>
                            <div class="text-muted small mt-1" style="font-size: 0.7rem; line-height: 1.3;">
                                <?= htmlspecialchars($log['description']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="index.php?page=logs" class="btn btn-secondary btn-sm w-100 mt-auto" style="border-radius: 20px;">
                Lihat Semua Log
            </a>
        </div>
    </div>
</div>

<!-- Distribusi Aset per Cabang -->
<div class="row g-4 mb-5 animate-fade-in">
    <div class="col-md-12">
        <div class="card p-4 border-0">
            <h6 class="fw-800 mb-4 text-dark d-flex align-items-center">
                <i class="bi bi-pie-chart me-2 text-primary"></i> Distribusi Aset per Cabang
            </h6>
            <div class="row g-4">
                <?php foreach ($branchDistribution as $branch): ?>
                    <div class="col-md-6 mb-2">
                        <div class="d-flex justify-content-between mb-1 align-items-center">
                            <span class="small fw-bold text-muted"><?= $branch['nama_cabang'] ?></span>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.75rem;"><?= $branch['total'] ?> Aset</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?= ($totalAssets > 0) ? ($branch['total'] / $totalAssets * 100) : 0 ?>%" aria-valuenow="<?= $branch['total'] ?>" aria-valuemin="0" aria-valuemax="<?= $totalAssets ?>"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

