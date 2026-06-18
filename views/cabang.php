<?php
require_once 'models/Cabang.php';
$cabangModel = new Cabang($conn);
$cabangs = $cabangModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'nama_cabang' => $_POST['nama_cabang'],
        'alamat' => $_POST['alamat']
    ];
    if ($cabangModel->create($data)) {
        header("Location: index.php?page=cabang&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Manajemen Cabang</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tambah Cabang
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Cabang</th>
                    <th>Alamat</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cabangs as $index => $c): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><strong><?= $c['nama_cabang'] ?></strong></td>
                    <td><?= $c['alamat'] ?></td>
                    <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-light text-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-light text-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Cabang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Cabang</label>
                        <input type="text" name="nama_cabang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"></textarea>
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
