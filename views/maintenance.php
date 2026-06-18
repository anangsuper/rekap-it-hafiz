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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Riwayat Maintenance</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Input Maintenance
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Asset</th>
                    <th>Teknisi</th>
                    <th>Temuan</th>
                    <th>Tindakan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($maintenances as $m): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($m['tanggal'])) ?></td>
                    <td>
                        <div class="fw-bold"><?= $m['nama_aset'] ?></div>
                        <div class="small text-muted"><?= $m['kode_aset'] ?></div>
                    </td>
                    <td><?= $m['teknisi'] ?></td>
                    <td><div class="small text-truncate" style="max-width: 200px;"><?= $m['temuan'] ?></div></td>
                    <td><div class="small text-truncate" style="max-width: 200px;"><?= $m['tindakan'] ?></div></td>
                    <td>
                        <button class="btn btn-sm btn-light text-primary"><i class="fas fa-eye"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Input Maintenance Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Aset</label>
                        <select name="asset_id" class="form-select" required>
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
                        <input type="text" name="teknisi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Temuan Pemeriksaan</label>
                        <textarea name="temuan" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindakan</label>
                        <textarea name="tindakan" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rekomendasi</label>
                        <textarea name="rekomendasi" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
