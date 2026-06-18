<?php
require_once 'models/Asset.php';
require_once 'models/KategoriAset.php';
require_once 'models/Cabang.php';
require_once 'models/Divisi.php';
require_once 'models/Karyawan.php';

$assetModel = new Asset($conn);
$kategoriModel = new KategoriAset($conn);
$cabangModel = new Cabang($conn);
$divisiModel = new Divisi($conn);
$karyawanModel = new Karyawan($conn);

$id_cabang_filter = isset($_GET['filter_cabang']) ? $_GET['filter_cabang'] : null;

$assets = $assetModel->getAll($id_cabang_filter);
$kategoris = $kategoriModel->getAll();
$cabangs = $cabangModel->getAll();
$divisis = $divisiModel->getAll();
$karyawans = $karyawanModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'kode_aset' => $_POST['kode_aset'],
        'nama_aset' => $_POST['nama_aset'],
        'serial_number' => $_POST['serial_number'],
        'id_kategori' => $_POST['id_kategori'],
        'merk' => $_POST['merk'],
        'model' => $_POST['model'],
        'id_cabang' => $_POST['id_cabang'],
        'id_divisi' => $_POST['id_divisi'],
        'id_karyawan' => $_POST['id_karyawan'],
        'kondisi' => $_POST['kondisi']
    ];
    if ($assetModel->create($data)) {
        header("Location: index.php?page=inventaris&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <h4 class="fw-bold me-4 mb-0">Data Inventaris Aset</h4>
        <form method="GET" action="index.php" class="d-flex align-items-center">
            <input type="hidden" name="page" value="inventaris">
            <select name="filter_cabang" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                <option value="">-- Semua Cabang --</option>
                <?php foreach ($cabangs as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($id_cabang_filter == $c['id']) ? 'selected' : '' ?>>
                        <?= $c['nama_cabang'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tambah Aset
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode Aset</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Cabang / Divisi</th>
                    <th>Pemegang</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $a): ?>
                <tr>
                    <td><span class="badge bg-light text-dark"><?= $a['kode_aset'] ?></span></td>
                    <td>
                        <div class="fw-bold"><?= $a['nama_aset'] ?></div>
                        <div class="small text-muted"><?= $a['merk'] ?> <?= $a['model'] ?></div>
                    </td>
                    <td><?= $a['nama_kategori'] ?></td>
                    <td>
                        <div class="small fw-bold"><?= $a['nama_cabang'] ?></div>
                        <div class="small text-muted"><?= $a['nama_divisi'] ?></div>
                    </td>
                    <td><?= $a['nama_karyawan'] ?? '-' ?></td>
                    <td>
                        <?php 
                        $color = 'success';
                        if ($a['kondisi'] == 'Rusak Ringan') $color = 'warning';
                        if ($a['kondisi'] == 'Rusak Berat') $color = 'danger';
                        ?>
                        <span class="badge bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> badge-status">
                            <?= $a['kondisi'] ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-light text-primary"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-light text-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah (Simplified for demo, but structure is ready for all fields) -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Aset Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Aset</label>
                            <input type="text" name="kode_aset" class="form-control" placeholder="AST-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Nama Aset</label>
                            <input type="text" name="nama_aset" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select">
                                <?php foreach ($kategoris as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Merk</label>
                            <input type="text" name="merk" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cabang</label>
                            <select name="id_cabang" class="form-select">
                                <?php foreach ($cabangs as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['nama_cabang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Divisi</label>
                            <select name="id_divisi" class="form-select">
                                <?php foreach ($divisis as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pemegang</label>
                            <select name="id_karyawan" class="form-select">
                                <option value="">-- Tanpa Pemegang --</option>
                                <?php foreach ($karyawans as $kr): ?>
                                    <option value="<?= $kr['id'] ?>"><?= $kr['nama_karyawan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Aset</button>
                </div>
            </form>
        </div>
    </div>
</div>
