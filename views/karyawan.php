<?php
require_once 'models/Karyawan.php';
require_once 'models/Cabang.php';
require_once 'models/Divisi.php';

$karyawanModel = new Karyawan($conn);
$cabangModel = new Cabang($conn);
$divisiModel = new Divisi($conn);

$karyawans = $karyawanModel->getAll();
$cabangs = $cabangModel->getAll();
$divisis = $divisiModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'nama_karyawan' => $_POST['nama_karyawan'],
        'nip' => $_POST['nip'],
        'id_cabang' => $_POST['id_cabang'],
        'id_divisi' => $_POST['id_divisi'],
        'jabatan' => $_POST['jabatan']
    ];
    if ($karyawanModel->create($data)) {
        header("Location: index.php?page=karyawan&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Manajemen Karyawan</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tambah Karyawan
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama Karyawan</th>
                    <th>Cabang</th>
                    <th>Divisi</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($karyawans as $k): ?>
                <tr>
                    <td><?= $k['nip'] ?></td>
                    <td><strong><?= $k['nama_karyawan'] ?></strong></td>
                    <td><?= $k['nama_cabang'] ?></td>
                    <td><?= $k['nama_divisi'] ?></td>
                    <td><?= $k['jabatan'] ?></td>
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
                    <h5 class="modal-title">Tambah Karyawan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>
                        <input type="text" name="nama_karyawan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cabang</label>
                        <select name="id_cabang" class="form-select">
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= $c['nama_cabang'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select name="id_divisi" class="form-select">
                            <?php foreach ($divisis as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control">
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
