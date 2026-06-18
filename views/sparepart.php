<?php
require_once 'models/Sparepart.php';
$sparepartModel = new Sparepart($conn);
$spareparts = $sparepartModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'nama_sparepart' => $_POST['nama_sparepart'],
        'kode_sparepart' => $_POST['kode_sparepart'],
        'stok' => $_POST['stok'],
        'satuan' => $_POST['satuan']
    ];
    if ($sparepartModel->create($data)) {
        header("Location: index.php?page=sparepart&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Manajemen Sparepart</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tambah Sparepart
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Sparepart</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($spareparts as $s): ?>
                <tr>
                    <td><span class="badge bg-light text-dark"><?= $s['kode_sparepart'] ?></span></td>
                    <td><strong><?= $s['nama_sparepart'] ?></strong></td>
                    <td>
                        <span class="fw-bold <?= ($s['stok'] < 5) ? 'text-danger' : '' ?>">
                            <?= $s['stok'] ?>
                        </span>
                    </td>
                    <td><?= $s['satuan'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-light text-primary"><i class="fas fa-plus-circle"></i></button>
                        <button class="btn btn-sm btn-light text-danger"><i class="fas fa-trash"></i></button>
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
                    <h5 class="modal-title">Tambah Sparepart Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Sparepart</label>
                        <input type="text" name="kode_sparepart" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Sparepart</label>
                        <input type="text" name="nama_sparepart" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="satuan" class="form-control" placeholder="Pcs/Unit/Botol">
                        </div>
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
