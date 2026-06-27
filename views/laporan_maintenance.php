<?php
require_once 'models/Asset.php';
require_once 'models/Cabang.php';
require_once 'models/Maintenance.php';

$cabangModel = new Cabang($conn);
$maintenanceModel = new Maintenance($conn);

$cabangs = $cabangModel->getAll();

$selected_cabang = $_GET['id_cabang'] ?? '';
$selected_bulan = $_GET['bulan'] ?? date('m');
$selected_tahun = $_GET['tahun'] ?? date('Y');

$stats = [];
$summaryDivisi = [];
$topFindings = [];
$yearlyStats = [];
$branchName = "";

if ($selected_cabang) {
    $stats = $maintenanceModel->getReportStats($selected_cabang, $selected_bulan, $selected_tahun);
    $summaryDivisi = $maintenanceModel->getSummaryPerDivisi($selected_cabang, $selected_bulan, $selected_tahun);
    $topFindings = $maintenanceModel->getTopFindings($selected_cabang, $selected_bulan, $selected_tahun);
    $yearlyStats = $maintenanceModel->getYearlyStats($selected_cabang, $selected_tahun);
    
    foreach ($cabangs as $c) {
        if ($c['id'] == $selected_cabang) {
            $branchName = $c['nama_cabang'];
            break;
        }
    }
}

function generateConclusion($stats, $bulan, $tahun, $branchName) {
    $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));
    $conclusion = "Pada bulan $namaBulan $tahun telah dilakukan maintenance terhadap {$stats['total_maintenance']} perangkat dari total {$stats['total_asset']} perangkat yang terdaftar di $branchName. ";
    $conclusion .= "Tingkat penyelesaian maintenance mencapai {$stats['persentase']}%. ";
    
    if ($stats['persentase'] >= 90) {
        $conclusion .= "Sebagian besar perangkat dalam kondisi baik dan operasional. ";
    } elseif ($stats['persentase'] >= 70) {
        $conclusion .= "Sebagian besar perangkat telah diperiksa, namun masih terdapat beberapa perangkat yang tertunda. ";
    } else {
        $conclusion .= "Tingkat penyelesaian maintenance masih rendah dan memerlukan perhatian segera. ";
    }
    
    if ($stats['total_temuan'] > 0) {
        $conclusion .= "Terdapat {$stats['total_temuan']} temuan selama proses maintenance yang memerlukan tindak lanjut.";
    } else {
        $conclusion .= "Tidak ditemukan kendala berarti pada perangkat yang telah diperiksa.";
    }
    
    return $conclusion;
}

function generateRecommendations($stats, $topFindings) {
    $recoms = [];
    if ($stats['persentase'] < 100) {
        $recoms[] = "Perlu dilakukan penjadwalan ulang maintenance untuk perangkat yang belum diperiksa.";
    }
    
    $findingText = "";
    foreach($topFindings as $tf) $findingText .= strtolower($tf['temuan']) . " ";
    
    if (str_contains($findingText, 'antivirus') || str_contains($findingText, 'update')) {
        $recoms[] = "Disarankan melakukan aktivasi antivirus dan pembaruan sistem pada perangkat yang belum terlindungi.";
    }
    
    if (str_contains($findingText, 'penuh') || str_contains($findingText, 'storage') || str_contains($findingText, 'lemot')) {
        $recoms[] = "Disarankan melakukan pembersihan file sementara dan optimasi penyimpanan pada perangkat yang melambat.";
    }
    
    if ($stats['total_perbaikan'] > 5) {
        $recoms[] = "Meningkatnya angka perbaikan menunjukkan perlunya penggantian beberapa komponen hardware yang sudah mencapai masa pakai optimal.";
    }
    
    if (empty($recoms)) {
        $recoms[] = "Tetap lakukan pemeliharaan rutin sesuai jadwal untuk menjaga stabilitas sistem.";
    }
    
    return $recoms;
}

$conclusion = $selected_cabang ? generateConclusion($stats, $selected_bulan, $selected_tahun, $branchName) : "";
$recommendations = $selected_cabang ? generateRecommendations($stats, $topFindings) : [];

$months = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
?>
<div class="container-fluid py-4 animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
                <i class="bi bi-file-earmark-bar-graph fs-4"></i>
            </div>
            <div>
                <h4 class="fw-800 m-0">Laporan Maintenance Massal</h4>
                <p class="text-muted small m-0">Analisis dan rekapitulasi data pemeriksaan berkala</p>
            </div>
        </div>
        <?php if ($selected_cabang): ?>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary shadow-sm px-3 py-2"><i class="bi bi-printer me-2"></i>Print</button>
            <a href="export/maintenance_excel.php?id_cabang=<?= $selected_cabang ?>&bulan=<?= $selected_bulan ?>&tahun=<?= $selected_tahun ?>" class="btn btn-outline-success shadow-sm px-3 py-2"><i class="bi bi-file-earmark-excel me-2"></i>Excel</a>
            <a href="export/maintenance_pdf.php?id_cabang=<?= $selected_cabang ?>&bulan=<?= $selected_bulan ?>&tahun=<?= $selected_tahun ?>" class="btn btn-danger shadow-sm px-3 py-2"><i class="bi bi-file-earmark-pdf me-2"></i>Generate PDF</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form method="GET" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="laporan_maintenance">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Pilih Cabang</label>
                    <select name="id_cabang" class="form-select bg-light border-0" required>
                        <option value="">-- Pilih Cabang --</option>
                        <?php foreach ($cabangs as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($selected_cabang == $c['id']) ? 'selected' : '' ?>><?= $c['nama_cabang'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Bulan</label>
                    <select name="bulan" class="form-select bg-light border-0">
                        <?php foreach ($months as $m => $nama): ?>
                            <option value="<?= $m ?>" <?= ($selected_bulan == $m) ? 'selected' : '' ?>><?= $nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tahun</label>
                    <select name="tahun" class="form-select bg-light border-0">
                        <?php for ($i = date('Y'); $i >= 2020; $i--): ?>
                            <option value="<?= $i ?>" <?= ($selected_tahun == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 shadow-sm"><i class="bi bi-arrow-repeat me-2"></i>Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($selected_cabang): ?>
    <!-- Branch Info -->
    <div class="card shadow-sm border-0 mb-4 bg-primary bg-opacity-10 rounded-4">
        <div class="card-body p-4 d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle p-2.5 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                <i class="bi bi-geo-alt-fill fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-primary mb-1"><?= $branchName ?></h5>
                <?php
                $branchDetails = null;
                foreach ($cabangs as $c) {
                    if ($c['id'] == $selected_cabang) {
                        $branchDetails = $c;
                        break;
                    }
                }
                ?>
                <p class="text-muted small mb-0"><?= $branchDetails['alamat'] ?? 'Alamat tidak tersedia' ?></p>
            </div>
        </div>
    </div>

    <!-- Stats Row (Lux Cards) -->
    <div class="row g-4 mb-4">
        <!-- Stat 1 -->
        <div class="col-md-3">
            <div class="lux-card" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none;"></div>
                <div class="card-body p-4 position-relative text-white">
                    <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 4rem; transform: translate(10%, -10%); pointer-events: none;">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div class="small fw-bold mb-1 opacity-70">TOTAL ASSETS</div>
                    <h3 class="fw-800 mb-0"><?= $stats['total_asset'] ?></h3>
                </div>
            </div>
        </div>
        <!-- Stat 2 -->
        <div class="col-md-3">
            <div class="lux-card" style="background: linear-gradient(135deg, #0d9488 0%, #059669 100%);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none;"></div>
                <div class="card-body p-4 position-relative text-white">
                    <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 4rem; transform: translate(10%, -10%); pointer-events: none;">
                        <i class="bi bi-pc-display"></i>
                    </div>
                    <div class="small fw-bold mb-1 opacity-70">KOMPUTER & LAPTOP</div>
                    <h3 class="fw-800 mb-0"><?= $stats['total_komputer'] ?></h3>
                </div>
            </div>
        </div>
        <!-- Stat 3 -->
        <div class="col-md-3">
            <div class="lux-card" style="background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none;"></div>
                <div class="card-body p-4 position-relative text-white">
                    <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 4rem; transform: translate(10%, -10%); pointer-events: none;">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="small fw-bold mb-1 opacity-70">TOTAL MAINTENANCE</div>
                    <h3 class="fw-800 mb-0"><?= $stats['total_maintenance'] ?></h3>
                </div>
            </div>
        </div>
        <!-- Stat 4 -->
        <div class="col-md-3">
            <div class="lux-card" style="background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%); pointer-events: none;"></div>
                <div class="card-body p-4 position-relative text-white">
                    <div class="position-absolute top-0 end-0 p-3 opacity-20" style="font-size: 4rem; transform: translate(10%, -10%); pointer-events: none;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="small fw-bold mb-1 opacity-70">TINGKAT PENYELESAIAN</div>
                    <h3 class="fw-800 mb-0"><?= $stats['persentase'] ?>%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Summary Per Division -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-grid-fill text-primary me-2"></i> Ringkasan Per Divisi
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Divisi</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">Belum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summaryDivisi as $sd): ?>
                                <tr>
                                    <td class="fw-semibold text-dark"><?= $sd['nama_divisi'] ?? 'Tanpa Divisi' ?></td>
                                    <td class="text-center"><?= $sd['total_perangkat'] ?></td>
                                    <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5"><?= $sd['selesai'] ?></span></td>
                                    <td class="text-center"><span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5"><?= $sd['belum'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-graph-up-arrow text-primary me-2"></i> Tren Bulanan Tahun Berjalan
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 220px; position: relative;">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI & Recommendations -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-start border-primary border-4 rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-cpu-fill me-2"></i>Kesimpulan Otomatis</h6>
                    <p class="mb-0 text-dark small" style="line-height: 1.6;">
                        <?= $conclusion ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 mb-5">
            <div class="card border-0 shadow-sm border-start border-success border-4 rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-success mb-3"><i class="bi bi-lightbulb-fill me-2"></i>Rekomendasi Strategis</h6>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($recommendations as $rec): ?>
                            <div class="d-flex align-items-center text-dark small">
                                <i class="bi bi-check-circle-fill text-success me-2.5"></i>
                                <span><?= $rec ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('yearlyChart').getContext('2d');
            const labels = <?= json_encode(array_values($months)) ?>;
            const dataValues = new Array(12).fill(0);
            
            <?php foreach ($yearlyStats as $ys): ?>
                dataValues[<?= $ys['bulan'] - 1 ?>] = <?= $ys['jumlah'] ?>;
            <?php endforeach; ?>

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Maintenance',
                        data: dataValues,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#6366f1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { stepSize: 1, color: '#94a3b8' },
                            grid: { color: 'rgba(148, 163, 184, 0.08)' }
                        },
                        x: {
                            ticks: { color: '#94a3b8' },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
    <?php else: ?>
    <div class="card border-0 shadow-sm rounded-4 py-5 my-4">
        <div class="card-body text-center py-5">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-4 mb-4">
                <i class="bi bi-file-earmark-spreadsheet fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark">Data Laporan Kosong</h5>
            <p class="text-muted small mx-auto" style="max-width: 350px;">Silakan pilih Cabang, Bulan, dan Tahun pada formulir di atas kemudian klik <strong>Generate Laporan</strong> untuk menampilkan rekapitulasi.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .lux-card {
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }
    .lux-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.12);
    }
    .fw-800 { font-weight: 800; }
    
    @media print {
        .btn-group, form, .card-header, .sidebar, header, .navbar, .btn { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .container-fluid { width: 100% !important; padding: 0 !important; }
        body { background: white !important; padding-top: 0 !important; }
    }
</style>
