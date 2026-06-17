<?php
require_once 'controllers/MaintenanceController.php';
require_once 'controllers/AssetController.php';

$maintCtrl = new MaintenanceController($conn);
$assetCtrl = new AssetController($conn);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    if ($maintCtrl->store($_POST)) {
        $message = '<div class="alert alert-success">Maintenance berhasil dicatat!</div>';
    }
}

$history = $maintCtrl->index();
$assets = $assetCtrl->index();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Maintenance Rutin</h3>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMaintModal">
        <i class="fas fa-plus me-2"></i> Catat Maintenance
    </button>
</div>

<?= $message ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Aset</th>
                        <th>Teknisi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                        <tr><td colspan="4" class="text-center">Belum ada riwayat maintenance.</td></tr>
                    <?php else: ?>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($h['tanggal'])) ?></td>
                                <td><strong><?= $h['kode_aset'] ?></strong> - <?= $h['nama_aset'] ?></td>
                                <td><?= $h['teknisi'] ?></td>
                                <td><?= $h['keterangan'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addMaintModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Catat Maintenance Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Pilih Aset</label>
                        <select name="asset_id" class="form-select" required>
                            <option value="">-- Pilih Aset --</option>
                            <?php foreach ($assets as $a): ?>
                                <option value="<?= $a['id'] ?>"><?= $a['kode_aset'] ?> - <?= $a['nama_aset'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teknisi</label>
                        <input type="text" name="teknisi" class="form-control" value="<?= $_SESSION['nama'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan Aktivitas</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembersihan debu, update windows, cek suhu." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
