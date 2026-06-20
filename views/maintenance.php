<?php
require_once 'models/Maintenance.php';
require_once 'models/Asset.php';

$maintenanceModel = new Maintenance($conn);
$assetModel = new Asset($conn);

$maintenances = $maintenanceModel->getAll();
$assets = $assetModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'asset_id' => $_POST['asset_id'],
        'tanggal' => $_POST['tanggal'],
        'teknisi' => $_POST['teknisi'],
        'temuan' => $_POST['temuan'],
        'tindakan' => $_POST['tindakan'],
        'rekomendasi' => $_POST['rekomendasi'],
        'id_detail_jadwal' => null
    ];
    if ($maintenanceModel->create($data)) {
        header("Location: index.php?page=maintenance&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
    <div class="d-flex align-items-center">
        <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3 text-success">
            <i class="bi bi-tools fs-4"></i>
        </div>
        <div>
            <h4 class="fw-800 m-0">Maintenance History</h4>
            <p class="text-muted small m-0">Tracking routine system checks</p>
        </div>
    </div>
    <div>
        <a href="index.php?page=maintenance_massal" class="btn btn-outline-primary shadow-sm me-2 border-0 bg-white" style="border-radius: 12px;">
            <i class="bi bi-layers-half me-2"></i> Bulk Maintenance
        </a>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i> Log Check
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm animate-fade-in" style="animation-delay: 0.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Asset Information</th>
                        <th>Technician</th>
                        <th>Maintenance Details</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($maintenances)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No maintenance records found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($maintenances as $m): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold"><?= date('d M Y', strtotime($m['tanggal'])) ?></div>
                            <div class="small text-muted" style="font-size: 0.65rem;">Logged: <?= date('H:i', strtotime($m['created_at'])) ?></div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary"><?= $m['kode_aset'] ?></div>
                            <div class="small text-muted"><?= $m['nama_aset'] ?></div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-1 me-2"><i class="bi bi-person text-secondary"></i></div>
                                <span class="small fw-500"><?= $m['teknisi'] ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-bold">Findings:</div>
                            <div class="small text-muted text-truncate" style="max-width: 250px;"><?= $m['temuan'] ?: 'No issues noted.' ?></div>
                        </td>
                        <td class="text-end pe-4">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2" style="font-size: 0.65rem;">
                                COMPLETED
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <form method="POST">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 m-0"><i class="bi bi-check2-square text-success me-2"></i> Log New Maintenance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select Asset</label>
                        <select name="asset_id" class="form-select shadow-sm" required>
                            <?php foreach ($assets as $a): ?>
                                <option value="<?= $a['id'] ?>"><?= $a['kode_aset'] ?> - <?= $a['nama_aset'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Check Date</label>
                            <input type="date" name="tanggal" class="form-control shadow-sm" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Technician Name</label>
                            <input type="text" name="teknisi" class="form-control shadow-sm" placeholder="Full name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Findings</label>
                        <textarea name="temuan" class="form-control shadow-sm" rows="2" placeholder="Describe current condition..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Actions Taken</label>
                        <textarea name="tindakan" class="form-control shadow-sm" rows="2" placeholder="What was done?"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Recommendation</label>
                        <textarea name="rekomendasi" class="form-control shadow-sm" rows="2" placeholder="Future advice..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Cancel</button>
                    <button type="submit" name="tambah" class="btn btn-success px-4 text-white" style="border-radius: 12px; box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-500 { font-weight: 500; }
</style>
