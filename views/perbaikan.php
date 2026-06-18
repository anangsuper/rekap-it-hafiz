<?php
require_once 'models/Repair.php';
require_once 'models/Asset.php';

$repairModel = new Repair($conn);
$assetModel = new Asset($conn);

$repairs = $repairModel->getAll();
$assets = $assetModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'asset_id' => $_POST['asset_id'],
        'keluhan' => $_POST['keluhan']
    ];
    if ($repairModel->create($data)) {
        header("Location: index.php?page=perbaikan&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Data Perbaikan</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tiket Perbaikan
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Asset</th>
                    <th>Keluhan</th>
                    <th>Status</th>
                    <th>Biaya</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($repairs as $r): ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?= $r['nama_aset'] ?></div>
                        <div class="small text-muted"><?= $r['kode_aset'] ?></div>
                    </td>
                    <td><div class="small text-truncate" style="max-width: 200px;"><?= $r['keluhan'] ?></div></td>
                    <td>
                        <?php 
                        $badge = 'warning';
                        if ($r['status'] == 'Selesai') $badge = 'success';
                        if ($r['status'] == 'Batal') $badge = 'danger';
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= $r['status'] ?></span>
                    </td>
                    <td>Rp <?= number_format($r['biaya'], 0, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-light text-primary"><i class="fas fa-edit"></i></button>
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
                    <h5 class="modal-title">Buat Tiket Perbaikan</h5>
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
                        <label class="form-label">Keluhan / Kerusakan</label>
                        <textarea name="keluhan" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Buat Tiket</button>
                </div>
            </form>
        </div>
    </div>
</div>
