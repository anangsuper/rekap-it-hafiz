<?php
require_once 'models/Divisi.php';
$divisiModel = new Divisi($conn);
$divisis = $divisiModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = ['nama_divisi' => $_POST['nama_divisi']];
    if ($divisiModel->create($data)) {
        header("Location: index.php?page=divisi&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Manajemen Divisi</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tambah Divisi
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Divisi</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($divisis as $index => $d): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><strong><?= $d['nama_divisi'] ?></strong></td>
                    <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                    <td>
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
                    <h5 class="modal-title">Tambah Divisi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Divisi</label>
                        <input type="text" name="nama_divisi" class="form-control" required>
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
