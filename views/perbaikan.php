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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $data = [
        'tindakan' => $_POST['tindakan'],
        'biaya' => $_POST['biaya'],
        'status' => $_POST['status'],
        'tanggal_selesai' => ($_POST['status'] == 'Selesai') ? date('Y-m-d') : null
    ];
    if ($repairModel->update($id, $data)) {
        header("Location: index.php?page=perbaikan&status=updated");
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
                        <button class="btn btn-sm btn-light text-primary btn-edit" 
                                data-id="<?= $r['id'] ?>" 
                                data-aset="<?= $r['nama_aset'] ?>" 
                                data-keluhan="<?= $r['keluhan'] ?>"
                                data-tindakan="<?= $r['tindakan'] ?>"
                                data-biaya="<?= $r['biaya'] ?>"
                                data-status="<?= $r['status'] ?>"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalUpdate">
                            <i class="fas fa-edit"></i> Update
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Update -->
<div class="modal fade" id="modalUpdate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="id" id="update_id">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Aset</label>
                        <input type="text" id="update_aset" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keluhan</label>
                        <textarea id="update_keluhan" class="form-control" readonly rows="2"></textarea>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Tindakan / Solusi</label>
                        <textarea name="tindakan" id="update_tindakan" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya Perbaikan (Rp)</label>
                        <input type="number" name="biaya" id="update_biaya" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="update_status" class="form-select">
                            <option value="Proses">Proses (Sedang Dikerjakan)</option>
                            <option value="Selesai">Selesai (Perangkat Kembali Baik)</option>
                            <option value="Batal">Batal (Tidak Bisa Diperbaiki)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('update_id').value = this.getAttribute('data-id');
        document.getElementById('update_aset').value = this.getAttribute('data-aset');
        document.getElementById('update_keluhan').value = this.getAttribute('data-keluhan');
        document.getElementById('update_tindakan').value = this.getAttribute('data-tindakan') || '';
        document.getElementById('update_biaya').value = this.getAttribute('data-biaya') || 0;
        document.getElementById('update_status').value = this.getAttribute('data-status');
    });
});
</script>

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
