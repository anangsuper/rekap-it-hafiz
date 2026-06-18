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

<div class="row g-4 mb-5 animate-fade-in">
    <!-- Stat Card 1 -->
    <div class="col-md-3">
        <div class="card border-0 overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #4361ee 100%);">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10" style="font-size: 5rem; transform: translate(20%, -20%);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="text-white-50 small fw-bold mb-1">TOTAL ASSETS</div>
                <h2 class="text-white fw-800 mb-0"><?= $totalAssets ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill small">
                        <i class="bi bi-arrow-up-right me-1"></i> Active devices
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3">
        <div class="card border-0 overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10" style="font-size: 5rem; transform: translate(20%, -20%);">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="text-white-50 small fw-bold mb-1">MAINTENANCE</div>
                <h2 class="text-white fw-800 mb-0"><?= $totalMaintenance ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill small">
                        <i class="bi bi-calendar-event me-1"></i> This month
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3">
        <div class="card border-0 overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10" style="font-size: 5rem; transform: translate(20%, -20%);">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="text-white-50 small fw-bold mb-1">ACTIVE REPAIRS</div>
                <h2 class="text-white fw-800 mb-0"><?= $totalRepairs ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill small">
                        <i class="bi bi-clock-history me-1"></i> In progress
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3">
        <div class="card border-0 overflow-hidden" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10" style="font-size: 5rem; transform: translate(20%, -20%);">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="text-white-50 small fw-bold mb-1">REPAIR COSTS</div>
                <h2 class="text-white fw-800 mb-0 text-nowrap" style="font-size: 1.5rem;">Rp <?= number_format($totalCost, 0, ',', '.') ?></h2>
                <div class="mt-3">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill small">
                        <i class="bi bi-graph-up me-1"></i> Current period
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 animate-fade-in" style="animation-delay: 0.1s;">
    <div class="col-md-8">
        <div class="card h-100 p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3 text-primary">
                    <i class="bi bi-stars fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-800 m-0">Welcome back, <?= $_SESSION['nama'] ?>!</h5>
                    <p class="text-muted small m-0">Manage your IT infrastructure with precision and ease.</p>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-white h-100 transition-hover">
                        <i class="bi bi-plus-circle-fill text-primary fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700">Quick Inventory</h6>
                        <p class="small text-muted mb-3">Register new hardware assets to the system.</p>
                        <a href="index.php?page=inventaris" class="btn btn-primary btn-sm w-100">Add Asset</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-white h-100 transition-hover">
                        <i class="bi bi-tools text-success fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700">Maintenance</h6>
                        <p class="small text-muted mb-3">Log routine checks and system health.</p>
                        <a href="index.php?page=maintenance" class="btn btn-success btn-sm w-100 text-white" style="border-radius: 12px;">Log Check</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-white h-100 transition-hover">
                        <i class="bi bi-wrench-adjustable text-warning fs-3 mb-3 d-block"></i>
                        <h6 class="fw-700">Repair Tickets</h6>
                        <p class="small text-muted mb-3">Track and update active repair cases.</p>
                        <a href="index.php?page=perbaikan" class="btn btn-warning btn-sm w-100 text-white" style="border-radius: 12px;">View Tickets</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 p-4 border-0 shadow-sm bg-dark text-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-800 m-0"><i class="bi bi-clock-history me-2 text-primary"></i> Recent Activity</h5>
                <span class="badge bg-primary bg-opacity-20 text-primary small">Live</span>
            </div>
            <div class="activity-feed">
                <?php
                require_once 'models/ActivityLog.php';
                $logModel = new ActivityLog($conn);
                $recentLogs = $logModel->getRecent(5);
                foreach ($recentLogs as $log):
                    $icon = 'bi-record-circle';
                    $color = 'text-primary';
                    if(strpos(strtolower($log['action']), 'tambah') !== false) { $icon = 'bi-plus-circle'; $color = 'text-success'; }
                    if(strpos(strtolower($log['action']), 'hapus') !== false) { $icon = 'bi-trash'; $color = 'text-danger'; }
                    if(strpos(strtolower($log['action']), 'login') !== false) { $icon = 'bi-person-check'; $color = 'text-info'; }
                ?>
                    <div class="d-flex mb-4 position-relative">
                        <div class="me-3 z-index-1">
                            <div class="bg-secondary bg-opacity-20 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="bi <?= $icon ?> <?= $color ?> small"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small fw-bold text-white"><?= $log['action'] ?></div>
                            <div class="small text-white-50 mt-1"><?= $log['description'] ?></div>
                            <div class="text-muted mt-1" style="font-size: 0.65rem;">
                                <i class="bi bi-calendar3 me-1"></i> <?= date('d M, H:i', strtotime($log['created_at'])) ?> • <span class="text-primary"><?= $log['nama'] ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-auto pt-3">
                <button class="btn btn-outline-light btn-sm w-100 py-2 border-opacity-25" style="border-radius: 12px;">View All Logs</button>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .transition-hover { transition: all 0.3s ease; border: 1px solid transparent !important; }
    .transition-hover:hover { background-color: #fff !important; border-color: var(--primary-light) !important; transform: translateY(-5px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05); }
    .activity-feed::before {
        content: '';
        position: absolute;
        left: 17px;
        top: 40px;
        bottom: 80px;
        width: 1px;
        background: rgba(255, 255, 255, 0.1);
    }
</style>
