<?php
require_once 'controllers/RepairController.php';
require_once 'controllers/AssetController.php';

$repairCtrl = new RepairController($conn);
$assetCtrl = new AssetController($conn);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        if ($repairCtrl->store($_POST)) {
            $message = '<div class="alert alert-success">Laporan perbaikan berhasil dibuat!</div>';
        }
    } elseif ($_POST['action'] == 'update') {
        if ($repairCtrl->update($_POST['id'], $_POST)) {
            $message = '<div class="alert alert-info">Status perbaikan berhasil diperbarui!</div>';
        }
    }
}

$repairs = $repairCtrl->index();
$assets = $assetCtrl->index();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Monitoring Perbaikan</h3>
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addRepairModal">
        <i class="fas fa-plus me-2"></i> Lapor Perbaikan
    </button>
</div>

<?= $message ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Aset</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($repairs)): ?>
                        <tr><td colspan="5" class="text-center">Belum ada data perbaikan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($repairs as $r): ?>
                            <tr>
                                <td><strong><?= $r['kode_aset'] ?></strong><br><small><?= $r['nama_aset'] ?></small></td>
                                <td><?= $r['keluhan'] ?></td>
                                <td>
                                    <?php
                                    $badge = 'bg-warning text-dark';
                                    if ($r['status'] == 'Selesai') $badge = 'bg-success';
                                    if ($r['status'] == 'Batal') $badge = 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= $r['status'] ?></span>
                                </td>
                                <td>Rp <?= number_format($r['biaya'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($r['status'] == 'Proses'): ?>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateRepairModal<?= $r['id'] ?>">
                                            Update
                                        </button>

                                        <!-- Modal Update Status -->
                                        <div class="modal fade" id="updateRepairModal<?= $r['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content text-start">
                                                    <form method="POST">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update Perbaikan: <?= $r['kode_aset'] ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="action" value="update">
                                                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                                            <div class="mb-3">
                                                                <label class="form-label">Tindakan / Solusi</label>
                                                                <textarea name="tindakan" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Biaya (Rp)</label>
                                                                <input type="number" name="biaya" class="form-control" value="0">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Status Akhir</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="Selesai">Selesai</option>
                                                                    <option value="Batal">Batal</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted"><?= $r['tindakan'] ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Lapor Perbaikan -->
<div class="modal fade" id="addRepairModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Lapor Perbaikan Baru</h5>
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
                        <label class="form-label">Keluhan / Kerusakan</label>
                        <textarea name="keluhan" class="form-control" rows="3" placeholder="Contoh: Monitor bergaris, Laptop mati total" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
