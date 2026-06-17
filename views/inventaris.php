<?php
require_once 'controllers/AssetController.php';
$assetCtrl = new AssetController($conn);

$message = '';

// Proses Form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $result = $assetCtrl->store($_POST, $_FILES);
            if ($result === true) {
                $message = '<div class="alert alert-success">Aset berhasil ditambahkan!</div>';
            } elseif ($result === "duplicate") {
                $message = '<div class="alert alert-danger">Gagal! Kode Aset sudah digunakan. Gunakan kode lain.</div>';
            }
        } elseif ($_POST['action'] == 'delete') {
            if ($assetCtrl->destroy($_POST['id'])) {
                $message = '<div class="alert alert-warning">Aset berhasil dihapus!</div>';
            }
        }
    }
}

$assets = $assetCtrl->index();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Inventaris Aset IT</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal">
        <i class="fas fa-plus me-2"></i> Tambah Aset
    </button>
</div>

<?= $message ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama Aset</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assets)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data aset.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assets as $asset): ?>
                            <tr>
                                <td>
                                    <?php if ($asset['foto']): ?>
                                        <img src="<?= $asset['foto'] ?>" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 5px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= $asset['kode_aset'] ?></strong></td>
                                <td><?= $asset['nama_aset'] ?></td>
                                <td><?= $asset['lokasi'] ?></td>
                                <td>
                                    <?php
                                    $badge = 'bg-success';
                                    if ($asset['kondisi'] == 'Rusak Ringan') $badge = 'bg-warning text-dark';
                                    if ($asset['kondisi'] == 'Rusak Berat') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= $asset['kondisi'] ?></span>
                                </td>
                                <td>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $asset['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Aset -->
<div class="modal fade" id="addAssetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Aset Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Kode Aset</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: PC-001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Aset</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Laptop HP Pavilion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spesifikasi</label>
                        <textarea name="spek" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Cabang Jakarta">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Aset</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Aset</button>
                </div>
            </form>
        </div>
    </div>
</div>
